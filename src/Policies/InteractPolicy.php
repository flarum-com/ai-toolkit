<?php

namespace Flarum\Ai\Policies;

use Flarum\Ai\Agent;
use Flarum\Ai\Interaction\Feature;
use Flarum\Post\CommentPost;
use Flarum\User\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class InteractPolicy
{
    public function __construct(protected Feature $feature, protected Agent\Collection $agents)
    {}

    public function reply(Agent $agent, CommentPost $post): bool
    {
        $tags = $post->discussion->tags ?? Collection::make();

        $authorizations = $this->tagAuthorizations($agent, $tags);

        if ($this->ignoreOtherAgent($authorizations, $post->user)) return false;

        return $authorizations
            ->filter(fn (Agent\Authorization $authorization) => $authorization->reply === true)
            ->isNotEmpty();
    }

    public function respond(Agent $agent, CommentPost $post): bool
    {
        $tags = $post->discussion->tags ?? Collection::make();

        if (! $this->feature->mention()) return false;

        $authorizations = $this->tagAuthorizations($agent, $tags);

        if ($this->ignoreOtherAgent($authorizations, $post->user)) return false;

        return $authorizations
            ->filter(fn (Agent\Authorization $authorization) => $authorization->respond === true)
            ->isNotEmpty();
    }

    protected function tagAuthorizations(Agent $agent, Collection $tags): Collection
    {
        $authorizations = Collection::make($agent->authorizations);

        if (! $this->feature->tags()) return $authorizations;

        return $authorizations
            ->filter(fn (Agent\Authorization $authorization) => ! $this->tagWildCarded($authorization) || $authorization->tags()->intersect($tags)->isNotEmpty());
    }

    protected function tagWildCarded(Agent\Authorization $authorization): bool
    {
        return Arr::first($authorization->tags) === '*';
    }

    protected function ignoreOtherAgent(Collection $authorizations, User $author): bool
    {
        $isAgent = $this->agents->user($author)->isNotEmpty();

        // Only ignore if the author is an Agent.
        if (! $isAgent) return false;

        // If the author is an Agent check the authorizations whether we need to ignore them.
        return $authorizations
            ->filter(fn (Agent\Authorization $authorization) => $authorization->ignoreBots)
            ->isNotEmpty();
    }
}
