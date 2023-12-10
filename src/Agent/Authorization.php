<?php

namespace Blomstra\Ai\Agent;

use Blomstra\Ai\Extend\Ai;
use Flarum\Database\Eloquent\Collection as EloquentCollection;
use Flarum\Tags\Tag;

class Authorization
{
    public array $tags = [];
    public bool $reply = false;
    public bool $initiate = false;
    public bool $respond = false;

    public function __construct(private Ai $return)
    {}

    public function in(int|string $tag): self
    {
        $this->tags[] = $tag;

        return $this;
    }

    public function can(
        bool $initiateDiscussions = false,
        bool $replyToPosts = false,
        bool $respondToMentions = false,
    ): self
    {
        $this->initiate = $initiateDiscussions;
        $this->reply = $replyToPosts;
        $this->respond = $respondToMentions;

        return $this;
    }

    public function reply(bool $replyToPosts = true): self
    {
        $this->reply = $replyToPosts;

        return $this;
    }

    public function respond(bool $respondToMentions = true): self
    {
        $this->respond = $respondToMentions;

        return $this;
    }

    public function initiate(bool $initiateDiscussions = true): self
    {
        $this->initiate = $initiateDiscussions;

        return $this;
    }

    public function return(): Ai
    {
        return $this->return;
    }

    public function tags()
    {
        return Tag::query()
            ->whereIn('id', $this->tags)
            ->orWhereIn('slug', $this->tags)
            ->get();
    }
}
