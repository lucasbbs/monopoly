<?php

namespace App\Services;

use App\Models\BoardSpace;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\PropertyOwnership;
use DomainException;
use Illuminate\Support\Facades\DB;

class GameEngine
{
    public function startGame(Game $game): Game
    {
        return DB::transaction(function () use ($game) {
            if (! $game->isWaiting()) {
                throw new DomainException('Only waiting games can be started.');
            }

            $players = $game->players()->get();

            if ($players->isEmpty()) {
                throw new DomainException('Cannot start a game without players.');
            }

            if ($players->contains(fn (GamePlayer $player) => $player->turn_order <= 0)) {
                throw new DomainException('All players must have a valid turn_order before the game starts.');
            }

            $playersCount = $players->count();
            $distinctTurnOrders = $players->pluck('turn_order')->unique()->count();

            if ($distinctTurnOrders !== $playersCount) {
                throw new DomainException('Each player must have a unique turn_order.');
            }

            if (! $game->isSinglePlayer()) {
                if ($playersCount !== $game->max_players) {
                    throw new DomainException('Fill every seat before starting the game.');
                }

                if ($players->pluck('token')->unique()->count() !== $playersCount) {
                    throw new DomainException('Each player must choose a different token.');
                }

                if ($players->contains(fn (GamePlayer $player) => ! $player->isReady())) {
                    throw new DomainException('Every player must be ready before the game starts.');
                }
            }

            $this->initializePropertyOwnerships($game);

            $firstPlayer = $game->players()
                ->orderBy('turn_order')
                ->firstOrFail();

            $game->update([
                'status' => Game::STATUS_IN_PROGRESS,
                'current_turn_player_id' => $firstPlayer->id,
                'started_at' => now(),
                'ended_at' => null,
                'winner_player_id' => null,
            ]);

            return $game->fresh(['players', 'currentTurnPlayer']);
        });
    }

    /**
     * @return array{0: int, 1: int}
     */
    public function rollDice(): array
    {
        return [random_int(1, 6), random_int(1, 6)];
    }

    public function takeTurn(Game $game): array
    {
        return DB::transaction(function () use ($game) {
            if (! $game->isInProgress()) {
                throw new DomainException('Only games in progress can take turns.');
            }

            $player = $game->currentTurnPlayer;

            if (! $player) {
                throw new DomainException('The game does not have a current turn player.');
            }

            if ($player->isBankrupt()) {
                $game->advanceTurn();

                return [
                    'message' => 'Skipped bankrupt player.',
                    'game' => $game->fresh(['players', 'currentTurnPlayer']),
                ];
            }

            [$dice1, $dice2] = $this->rollDice();
            $steps = $dice1 + $dice2;

            $moveResult = $this->movePlayer($player, $steps);
            $space = $this->getBoardSpaceByPosition($player->position);
            $landingResult = $this->resolveLanding($game, $player, $space);

            if (! $game->fresh()->isFinished()) {
                $game->advanceTurn();
            }

            return [
                'dice' => [$dice1, $dice2],
                'steps' => $steps,
                'move' => $moveResult,
                'space' => [
                    'id' => $space->id,
                    'name' => $space->name,
                    'position' => $space->position,
                    'type' => $space->type,
                ],
                'landing' => $landingResult,
                'game' => $game->fresh(['players', 'currentTurnPlayer', 'winnerPlayer']),
            ];
        });
    }

    public function buyCurrentProperty(Game $game): PropertyOwnership
    {
        return DB::transaction(function () use ($game) {
            if (! $game->isInProgress()) {
                throw new DomainException('Only games in progress allow property purchases.');
            }

            $player = $game->currentTurnPlayer;

            if (! $player) {
                throw new DomainException('The game does not have a current turn player.');
            }

            $space = $this->getBoardSpaceByPosition($player->position);

            if (! $space->isOwnable()) {
                throw new DomainException('This board space cannot be purchased.');
            }

            $ownership = PropertyOwnership::query()
                ->where('game_id', $game->id)
                ->where('board_space_id', $space->id)
                ->firstOrFail();

            if ($ownership->isOwned()) {
                throw new DomainException('This property already has an owner.');
            }

            if (($space->price ?? 0) <= 0) {
                throw new DomainException('This property does not have a valid price.');
            }

            if ($player->cash < $space->price) {
                throw new DomainException('The player does not have enough cash to buy this property.');
            }

            $player->removeCash($space->price);
            $ownership->assignOwner($player);

            return $ownership->fresh(['boardSpace', 'owner']);
        });
    }

    public function assignRandomTurnOrder(Game $game): void
    {
        DB::transaction(function () use ($game) {
            $players = $game->players()->get()->shuffle()->values();

            foreach ($players as $index => $player) {
                $player->update([
                    'turn_order' => $index + 1,
                ]);
            }
        });
    }

    protected function initializePropertyOwnerships(Game $game): void
    {
        $ownableSpaces = BoardSpace::query()
            ->whereIn('type', [
                BoardSpace::TYPE_PROPERTY,
                BoardSpace::TYPE_RAILROAD,
                BoardSpace::TYPE_UTILITY,
            ])
            ->get();

        foreach ($ownableSpaces as $space) {
            PropertyOwnership::firstOrCreate(
                [
                    'game_id' => $game->id,
                    'board_space_id' => $space->id,
                ],
                [
                    'owner_game_player_id' => null,
                    'houses' => 0,
                    'hotel' => false,
                    'mortgaged' => false,
                ]
            );
        }
    }

    protected function movePlayer(GamePlayer $player, int $steps): array
    {
        $boardSize = $this->getBoardSize();
        $oldPosition = $player->position;
        $rawPosition = $oldPosition + $steps;
        $newPosition = $rawPosition % $boardSize;
        $passedGo = $rawPosition >= $boardSize;

        if ($passedGo) {
            $player->addCash(200);
        }

        $player->moveTo($newPosition);

        return [
            'from' => $oldPosition,
            'to' => $newPosition,
            'passed_go' => $passedGo,
        ];
    }

    protected function resolveLanding(Game $game, GamePlayer $player, BoardSpace $space): array
    {
        if ($space->isTax()) {
            $amount = $space->price ?? 0;
            $player->removeCash($amount);

            return [
                'action' => 'tax',
                'amount' => $amount,
            ];
        }

        if ($space->isGoToJail()) {
            $player->sendToJail();

            return [
                'action' => 'go_to_jail',
            ];
        }

        if ($space->isChance()) {
            return [
                'action' => 'chance',
                'message' => 'Chance card resolution not implemented yet.',
            ];
        }

        if ($space->isCommunityChest()) {
            return [
                'action' => 'community_chest',
                'message' => 'Community Chest resolution not implemented yet.',
            ];
        }

        if ($space->isOwnable()) {
            $ownership = PropertyOwnership::query()
                ->where('game_id', $game->id)
                ->where('board_space_id', $space->id)
                ->firstOrFail();

            if (! $ownership->isOwned()) {
                return [
                    'action' => 'unowned_property',
                    'can_buy' => true,
                    'price' => $space->price,
                ];
            }

            if ((int) $ownership->owner_game_player_id === (int) $player->id) {
                return [
                    'action' => 'own_property',
                ];
            }

            if ($ownership->isMortgaged()) {
                return [
                    'action' => 'mortgaged_property',
                ];
            }

            $rent = $this->calculateRent($space, $ownership);

            $player->removeCash($rent);
            $ownership->owner?->addCash($rent);

            $this->checkForWinner($game);

            return [
                'action' => 'pay_rent',
                'amount' => $rent,
                'owner_game_player_id' => $ownership->owner_game_player_id,
            ];
        }

        return [
            'action' => 'none',
        ];
    }

    protected function calculateRent(BoardSpace $space, PropertyOwnership $ownership): int
    {
        $rent = $space->base_rent ?? 0;

        if ($ownership->hotel) {
            return $rent + 200;
        }

        if ($ownership->houses > 0) {
            return $rent + ($ownership->houses * 50);
        }

        return $rent;
    }

    protected function checkForWinner(Game $game): void
    {
        $activePlayers = $game->players()
            ->where('is_bankrupt', false)
            ->get();

        if ($activePlayers->count() === 1) {
            $winner = $activePlayers->first();

            $game->update([
                'status' => Game::STATUS_FINISHED,
                'winner_player_id' => $winner->id,
                'ended_at' => now(),
            ]);
        }
    }

    protected function getBoardSpaceByPosition(int $position): BoardSpace
    {
        return BoardSpace::query()
            ->where('position', $position)
            ->firstOrFail();
    }

    protected function getBoardSize(): int
    {
        return BoardSpace::query()->count();
    }
}
