<?php

namespace Flarum\Ai\Agent;

use Flarum\Ai\Agent;
use Flarum\User\User;
use Illuminate\Support\Collection as LaravelCollection;

class Collection extends LaravelCollection
{
    public function operational(): self
    {
        return $this->filter(fn (Agent $agent) => $agent->operational());
    }

    public function user(User $user): self
    {
        return $this->filter(fn (Agent $agent) => $agent->user()->is($user));
    }
}
