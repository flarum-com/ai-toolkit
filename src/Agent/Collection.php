<?php

namespace Blomstra\Ai\Agent;

use Blomstra\Ai\Agent;
use Illuminate\Support\Collection as LaravelCollection;

class Collection extends LaravelCollection
{
    public function operational()
    {
        return $this->filter(fn (Agent $agent) => $agent->operational());
    }
}
