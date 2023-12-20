<?php

namespace Flarum\Ai\Policies;

use Flarum\Ai\Agent;
use Flarum\Ai\Interaction\Feature;
use Flarum\Post\CommentPost;
use Illuminate\Support\Collection;

class InteractPolicy
{
    public function __construct(protected Feature $feature)
    {}

    public function reply(Agent $agent, CommentPost $post): bool
    {
        $tags = $post->discussion->tags ?? Collection::make();

        $authorizations = $this->tagAuthorizations($agent, $tags);

        return $authorizations
            ->filter(fn (Agent\Authorization $authorization) => $authorization->reply === true)
            ->isNotEmpty();
    }

    public function respond(Agent $agent, CommentPost $post): bool
    {
        $tags = $post->discussion->tags ?? Collection::make();

        if (! $this->feature->mention()) return false;

        $authorizations = $this->tagAuthorizations($agent, $tags);

        return $authorizations
            ->filter(fn (Agent\Authorization $authorization) => $authorization->respond === true)
            ->isNotEmpty();
    }

    protected function tagAuthorizations(Agent $agent, Collection $tags): Collection
    {
        $authorizations = Collection::make($agent->authorizations);

        if (! $this->feature->tags()) return $authorizations;

        return $authorizations
            ->reject(fn (Agent\Authorization $authorization) => $authorization->tags()->intersect($tags)->isEmpty());
    }
}
