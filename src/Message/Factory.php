<?php

namespace Flarum\Ai\Message;

use Flarum\Ai\Agent;
use Flarum\Ai\Agent\Role;
use Flarum\Discussion\Discussion;
use Flarum\Post\CommentPost;
use Flarum\Post\Post;
use Flarum\User\Guest;
use Illuminate\Support\Collection;

class Factory
{
    public static function buildInstructions(Discussion $discussion): ?Message
    {
        if ($discussion->comments->isEmpty()) return null;

        $instructions = $discussion
            ->comments
            ->pluck('content')
            ->join(' ');

        return new Message(
            Role::system,
            $instructions
        );
    }

    public static function buildFromPost(Agent $agent, Post $post): Message
    {
        return new Message(
            $agent->user()->is($post->user) ? Role::assistant : Role::user,
            $post->user->display_name . ' wrote: ' . $post->content
        );
    }

    /**
     * @param Post $post
     * @return Collection<Message>
     */
    public static function buildFromHistory(Agent $agent, Post $post): Collection
    {
        $messages = Collection::make();

        $post->discussion->comments()
            ->whereVisibleTo($agent->user())
            ->each(fn (CommentPost $comment) => $messages->push(self::buildFromPost($agent, $comment)));

        return $messages;
    }

    protected static function buildFromMentions(Post $post): Collection
    {
        $collect = $post->mentionsPosts ?? Collection::make();

        $collect->each(fn (Post $post) => $collect->merge($post->mentionsPost ?? Collection::make()));

        return $collect->unique();
    }
}
