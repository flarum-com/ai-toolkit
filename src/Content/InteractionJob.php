<?php

namespace Blomstra\Ai\Content;

use Blomstra\Ai\Agent;
use Blomstra\Ai\Interaction\Feature;
use Blomstra\Ai\Message\Factory;
use Blomstra\Ai\Message\Message;
use Flarum\Discussion\Discussion;
use Flarum\Foundation\DispatchEventsTrait;
use Flarum\Post\CommentPost;
use Flarum\Queue\AbstractJob;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use OpenAI\Client;

class InteractionJob extends AbstractJob
{
    use DispatchEventsTrait;

    public function __construct(
        private Agent $agent,
        private CommentPost $post
    ) {}

    public function handle(Feature $feature, Dispatcher $events)
    {
        /** @var Client $api */
        $api = resolve('blomstra-ai-client');

        $messages = Collection::make();

        $messages->push($this->gatherInstructions());

        $messages = $messages->concat($this->gatherHistory());

        $response = $api->chat()->create([
            'model' => $this->agent->model->value,
            'messages' => $messages,
            'user' => "user-$this->post->user_id"
        ]);

        if (empty($response->choices)) return;

        $choice = Arr::first($response->choices);
        $respond = $choice->message->content;

        $reply = new Reply(
            reply: $respond,
            shouldMention: $feature->mention(),
            inReplyTo: $this->post
        );

        $post = CommentPost::reply(
            discussionId: $this->post->discussion_id,
            content: $reply(),
            userId: $this->agent->user()->id,
            ipAddress: '0.0.0.0',
            actor: $this->agent->user()
        );

        $post->save();

        // Update mentions data.
        $this->events = $events;
        $this->dispatchEventsFor($post, $this->agent->user());
    }

    private function gatherInstructions(): ?Message
    {
        /** @var Discussion $discussion */
        $discussion = $this->agent->instructions
            ? Discussion::query()->findOrFail($this->agent->instructions)
            : null;

        if (! $discussion) return null;

        return Factory::buildInstructions($discussion);
    }

    private function gatherHistory(): Collection
    {
        return Factory::buildFromHistory($this->agent, $this->post);
    }
}
