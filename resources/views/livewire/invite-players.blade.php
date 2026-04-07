<div wire:poll.5s class="space-y-6">
    <section class="relative overflow-hidden rounded-[2rem] border border-red-200 bg-gradient-to-br from-red-50 via-amber-50 to-white p-6 shadow-sm shadow-red-200/50">
        <div class="absolute inset-y-0 right-0 hidden w-1/3 bg-[radial-gradient(circle_at_top_right,_rgba(220,38,38,0.18),_transparent_60%)] lg:block"></div>

        <div class="relative space-y-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-red-600">
                        <flux:icon.dices class="size-4" variant="mini" />
                        Monopoly Lobby
                    </div>

                    <div>
                        <h1 class="font-serif text-4xl text-neutral-900">{{ $game->name }}</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-neutral-600">
                            Choose your token, add the rest of the table, get everyone ready, and start when the lobby is full.
                        </p>
                    </div>
                </div>

                <div class="rounded-3xl border border-neutral-200 bg-white/90 px-4 py-3 text-sm text-neutral-600 shadow-sm">
                    <p class="font-semibold text-neutral-900">{{ $game->players->count() }} / {{ $game->max_players }} players joined</p>
                    <p>{{ $seatsRemaining }} seat{{ $seatsRemaining === 1 ? '' : 's' }} remaining</p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-3xl border border-white/70 bg-white/85 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-neutral-500">Your Token</p>
                    <p class="mt-3 text-2xl font-semibold text-neutral-900">{{ $tokenOptions[$currentPlayer->token] ?? $currentPlayer->token }}</p>
                </div>

                <div class="rounded-3xl border border-white/70 bg-white/85 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-neutral-500">Your Status</p>
                    <p class="mt-3 text-2xl font-semibold {{ $currentPlayer->isReady() ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $currentPlayer->isReady() ? 'Ready' : 'Waiting' }}
                    </p>
                </div>

                <div class="rounded-3xl border border-white/70 bg-white/85 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-neutral-500">Lobby Status</p>
                    <p class="mt-3 text-2xl font-semibold {{ $canStart ? 'text-emerald-600' : 'text-neutral-900' }}">
                        {{ $canStart ? 'Ready to Start' : 'Preparing' }}
                    </p>
                </div>

                <div class="rounded-3xl border border-white/70 bg-white/85 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-neutral-500">Chat</p>
                    <p class="mt-3 text-2xl font-semibold text-neutral-900">{{ $chatMessages->count() }}</p>
                </div>
            </div>
        </div>
    </section>

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">The lobby still needs a few fixes.</p>

            <ul class="mt-2 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[1.2fr,0.8fr]">
        <div class="space-y-6">
            <section class="rounded-[2rem] border border-neutral-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-serif text-2xl text-neutral-900">Choose Your Token</h2>
                        <p class="mt-1 text-sm text-neutral-600">Every player needs a unique piece before the game can begin.</p>
                    </div>

                    <button
                        type="button"
                        wire:click="toggleReady"
                        class="{{ $currentPlayer->isReady() ? 'bg-emerald-600 hover:bg-emerald-500' : 'bg-amber-500 hover:bg-amber-400' }} inline-flex items-center justify-center rounded-2xl px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition"
                    >
                        {{ $currentPlayer->isReady() ? 'Mark Not Ready' : 'Mark Ready' }}
                    </button>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($tokenOptions as $tokenValue => $tokenLabel)
                        @php
                            $isSelected = $selectedToken === $tokenValue;
                            $claimedBy = $claimedTokens[$tokenValue] ?? null;
                            $isClaimed = $claimedBy !== null;
                        @endphp

                        <button
                            type="button"
                            wire:click="$set('selectedToken', '{{ $tokenValue }}')"
                            wire:key="token-{{ $tokenValue }}"
                            @disabled($isClaimed)
                            @class([
                                'group rounded-3xl border p-4 text-left transition',
                                'border-red-500 bg-red-50 shadow-sm shadow-red-200' => $isSelected,
                                'border-neutral-200 bg-neutral-50 hover:border-red-300 hover:bg-red-50/50' => ! $isSelected && ! $isClaimed,
                                'cursor-not-allowed border-neutral-200 bg-neutral-100 text-neutral-400 opacity-70' => $isClaimed,
                            ])
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-lg font-semibold text-neutral-900">{{ $tokenLabel }}</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.2em] {{ $isClaimed ? 'text-neutral-400' : 'text-neutral-500' }}">
                                        {{ $isClaimed ? 'Claimed' : ($isSelected ? 'Selected' : 'Available') }}
                                    </p>
                                </div>

                                <div class="rounded-full border px-2 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] {{ $isSelected ? 'border-red-500 text-red-600' : 'border-neutral-300 text-neutral-500' }}">
                                    {{ $loop->iteration }}
                                </div>
                            </div>

                            <p class="mt-4 text-sm {{ $isClaimed ? 'text-neutral-400' : 'text-neutral-600' }}">
                                {{ $isClaimed ? 'Taken by '.$claimedBy : 'Tap to lock this token in for your seat.' }}
                            </p>
                        </button>
                    @endforeach
                </div>
            </section>

            <section class="rounded-[2rem] border border-neutral-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-serif text-2xl text-neutral-900">Players</h2>
                        <p class="mt-1 text-sm text-neutral-600">Invite existing users by email until the table is full.</p>
                    </div>

                    <div class="rounded-full bg-neutral-100 px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-neutral-600">
                        {{ $game->players->count() }} joined
                    </div>
                </div>

                <form wire:submit="invitePlayer" class="mt-6 flex flex-col gap-3 lg:flex-row">
                    <x-input.text
                        wire:model="inviteEmail"
                        type="email"
                        placeholder="player@example.com"
                        class="h-12 flex-1 rounded-2xl"
                    />

                    <button
                        type="submit"
                        class="inline-flex h-12 items-center justify-center rounded-2xl bg-red-600 px-5 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-red-500"
                    >
                        Add Player
                    </button>
                </form>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @foreach ($game->players as $player)
                        <article wire:key="player-{{ $player->id }}" class="rounded-3xl border border-neutral-200 bg-neutral-50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-lg font-semibold text-neutral-900">{{ $player->user?->name ?? 'Unknown Player' }}</p>
                                    <p class="mt-1 text-sm text-neutral-500">{{ $player->user?->email }}</p>
                                </div>

                                <div class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $player->isReady() ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $player->isReady() ? 'Ready' : 'Waiting' }}
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-between rounded-2xl bg-white px-4 py-3 text-sm">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.2em] text-neutral-500">Token</p>
                                    <p class="mt-1 font-semibold text-neutral-900">{{ $tokenOptions[$player->token] ?? $player->token }}</p>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs uppercase tracking-[0.2em] text-neutral-500">Turn Order</p>
                                    <p class="mt-1 font-semibold text-neutral-900">#{{ $player->turn_order }}</p>
                                </div>
                            </div>

                            @if ((int) $player->user_id === (int) $currentPlayer->user_id)
                                <p class="mt-3 text-xs font-semibold uppercase tracking-[0.2em] text-red-600">You</p>
                            @endif
                        </article>
                    @endforeach

                    @for ($slot = 0; $slot < $seatsRemaining; $slot++)
                        <article wire:key="open-seat-{{ $slot }}" class="rounded-3xl border border-dashed border-neutral-300 bg-neutral-50/60 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-neutral-500">Open Seat</p>
                            <p class="mt-3 text-lg font-semibold text-neutral-900">Waiting for another player</p>
                            <p class="mt-1 text-sm text-neutral-500">Add a registered user by email to claim this spot.</p>
                        </article>
                    @endfor
                </div>
            </section>
        </div>

        <div class="space-y-6">
            <section class="rounded-[2rem] border border-neutral-200 bg-white p-6 shadow-sm">
                <div>
                    <h2 class="font-serif text-2xl text-neutral-900">Game Chat</h2>
                    <p class="mt-1 text-sm text-neutral-600">Use the lobby chat to coordinate before the board opens.</p>
                </div>

                <div class="mt-6 space-y-3 rounded-[1.5rem] bg-neutral-50 p-4">
                    @forelse ($chatMessages as $message)
                        <article wire:key="message-{{ $message->id }}" class="rounded-2xl bg-white px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-neutral-900">{{ $message->user?->name ?? 'Unknown Player' }}</p>
                                <p class="text-xs uppercase tracking-[0.2em] text-neutral-400">{{ $message->created_at?->diffForHumans() }}</p>
                            </div>

                            <p class="mt-2 text-sm leading-6 text-neutral-700">{{ $message->message }}</p>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-neutral-300 px-4 py-6 text-center text-sm text-neutral-500">
                            No messages yet. Break the ice before the first roll.
                        </div>
                    @endforelse
                </div>

                <form wire:submit="sendMessage" class="mt-4 space-y-3">
                    <textarea
                        wire:model="chatMessage"
                        rows="4"
                        placeholder="Message the table..."
                        class="w-full rounded-[1.5rem] border-2 border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 placeholder:text-neutral-400 focus:border-red-500 focus:outline-none"
                    ></textarea>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-neutral-900 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-neutral-700"
                    >
                        Send Message
                    </button>
                </form>
            </section>

            <section class="rounded-[2rem] border border-neutral-200 bg-neutral-950 p-6 text-white shadow-sm">
                <div class="space-y-3">
                    <h2 class="font-serif text-2xl">Start Game</h2>
                    <p class="text-sm leading-6 text-neutral-300">
                        The board unlocks when every seat is filled and every player is marked ready.
                    </p>
                </div>

                <div class="mt-6 space-y-3 text-sm text-neutral-300">
                    <div class="flex items-center justify-between rounded-2xl bg-white/5 px-4 py-3">
                        <span>Seats filled</span>
                        <span>{{ $seatsRemaining === 0 ? 'Yes' : 'No' }}</span>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-white/5 px-4 py-3">
                        <span>Everyone ready</span>
                        <span>{{ $game->players->every(fn ($player) => $player->isReady()) ? 'Yes' : 'No' }}</span>
                    </div>
                </div>

                @if ($game->isInProgress())
                    <a
                        href="{{ route('monopoly.show', $game) }}"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-5 py-4 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-emerald-400"
                    >
                        Go to Board
                    </a>
                @else
                    <button
                        type="button"
                        wire:click="startGame"
                        @disabled(! $canStart)
                        class="mt-6 inline-flex w-full items-center justify-center rounded-2xl px-5 py-4 text-sm font-semibold uppercase tracking-[0.2em] transition {{ $canStart ? 'bg-red-500 text-white hover:bg-red-400' : 'cursor-not-allowed bg-white/10 text-neutral-500' }}"
                    >
                        Start Game
                    </button>
                @endif
            </section>
        </div>
    </div>
</div>
