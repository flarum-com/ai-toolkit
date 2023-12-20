<?php

namespace Flarum\Ai\Agent;

use Flarum\Ai\Agent;
use Illuminate\Support\Collection as LaravelCollection;

class Collection extends LaravelCollection
{
    public function operational()
    {
        return $this->filter(fn (Agent $agent) => $agent->operational());
    }
}
