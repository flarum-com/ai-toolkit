<?php

namespace Blomstra\Ai\Interaction;

use Blomstra\Ai\Agent;
use Blomstra\Ai\Agent\Collection;
use Blomstra\Ai\Content\InteractionJob;
use Blomstra\Ai\Policies\InteractPolicy;
use Flarum\Post\Event\Posted;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\Queue;

class ReplyToPosts
{
    public function __construct(
        protected Collection $agents,
        protected InteractPolicy $policy,
        protected Queue $queue
    ) {}

    public function subscribe(Dispatcher $events)
    {
        $events->listen(Posted::class, function (Posted $event) {
            $this->agents
                ->operational()
                // Prevent eternal loops of replying to oneself.
                ->filter(fn (Agent $agent) => $agent->user()->isNot($event->post->user))
                // Filter agents based on their tag permissions.
                ->filter(fn (Agent $agent) => $this->policy->reply($agent, $event->post) || $this->policy->respond($agent, $event->post))
                // Push sending to the queue.
                ->each(fn (Agent $agent) => $this->queue->push(new InteractionJob($agent, $event->post)));
        });
    }
}
