<?php

namespace Flarum\Ai;

use Flarum\Discussion\Discussion;
use Flarum\User\Guest;
use Flarum\User\User;
use OpenAI\Client;

class Agent
{
    protected static ?Client $client = null;

    protected ?User $represents = null;

    public function __construct(
        public readonly string $key,
        public readonly int|string $userKey,
        public readonly Agent\Model $model,
        public readonly array $authorizations = [],
        public readonly ?int $instructions = null
    )
    {}

    public function operational(): bool
    {
        return static::$client !== null
            && $this->user() instanceof User
            && $this->user()->suspended_until === null;
    }

    public function represents(User $user): bool
    {
        return $this->user()->is($user);
    }

    public function user(): ?User
    {
        if (! $this->represents) {
            $this->represents = match(true) {
                is_int($this->userKey) => User::query()->findOrFail($this->userKey),
                is_string($this->userKey) => User::query()->where('username', $this->userKey)->firstOrFail()
            };
        }

        return $this->represents ?? new Guest;
    }

    public static function setClient(Client $client): void
    {
        static::$client = $client;
    }
}
