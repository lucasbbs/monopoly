<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class GameLifecycleEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $gameId,
        public string $type,
        public array $data = [],
        public ?int $actorId = null,
    ) {
        $this->actorId ??= $this->data['player_id'] ?? Auth::id();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, PrivateChannel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('monopoly'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'game_id' => $this->gameId,
            'type' => $this->type,
            'data' => array_merge($this->data, ['player_id' => $this->actorId]),
        ];
    }
}
