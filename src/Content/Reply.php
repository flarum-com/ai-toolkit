<?php

namespace Blomstra\Ai\Content;

use Blomstra\SupportAi\Event\Replying;
use Flarum\Post\Post;

class Reply
{
    public function __construct(
        protected readonly string $reply,
        protected readonly bool $shouldMention = false,
        protected readonly ?Post $inReplyTo = null,
    )
    {}

    public function __invoke(): string
    {
        $message = $this->reply;

        if ($mention = $this->getReplyToMention()) {
            $message = "$mention $message";
        }

        return $message;
    }

    protected function getReplyToMention(): ?string
    {
        if (!$this->shouldMention || !$this->inReplyTo) {
            return null;
        }

        $this->inReplyTo->load('user');

        return sprintf(
            '@"%s"#p%u',
            $this->inReplyTo->user->display_name,
            $this->inReplyTo->id
        );
    }
}

// <POSTMENTION discussionid="5" displayname="admin" id="35" number="10">@"admin"#p35</POSTMENTION>
// <POSTMENTION discussionid="5" displayname="admin" id="33" number="8">@"admin"#p33</POSTMENTION>
// <POSTMENTION discussionid="5" displayname="admin" id="26" number="1">@"admin"#p26</POSTMENTION> <USERMENTION displayname="admin" id="1">@"admin"#1</USERMENTION>
