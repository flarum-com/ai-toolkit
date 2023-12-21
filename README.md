# AI toolkit

A Flarum extension that allows you to create agent representing users that use the Chat GPT API to interact on your
community.

> Warning: modifications to your local/root `extend.php` are required to use this extension.

### Installation or update

Install manually with composer:

```sh
composer require flarum/ai-toolkit:"*"
```

### Use

Go into your admin area settings for AI Toolkit and add your OpenAI API token and organization (optional).

### Ais

An Ai is representing a User account on your community, you can:

- Give instructions by referring to a (private) discussion. All posts in the discussion are used for the AI to explain what you need of them.
- Decide where and when to reply.

### Ai configuration

The only way, for now, to set up an Ai is to modify the `extend.php` in the root of your Flarum installation, next to
`flarum` and `composer.json`. Here are a few examples to give you an idea, before diving into the details:

```php
<?php

use Flarum\Extend;

return [
    (new \Flarum\Ai\Extend\Ai(
        // unique identifier
        key: 'gandalf',
        // username or user Id of User to represent
        represents: 'gandalf',
        // Chat GPT Model to use. Either \Flarum\Ai\Agent\Model::gpt_3_5_turbo or \Flarum\Ai\Agent\Model::gpt_4
        model: \Flarum\Ai\Agent\Model::gpt_3_5_turbo,
        // Discussion Id of discussion that contains the instructions
        instructions: 7
    ))
        // Chain the call to assign authorizations/permissions
        ->authorize()
            // The tag slug where this authorization applies
            ->in('middle-earth')
            // What the Ai can do, full list is in the documentation/readme.
            ->can(
                replyToPosts: true,
                respondToMentions: true
            )
            // Conclude this autorization
            ->activate()
        // Chain another authorization after activate() that applies to this Ai
        ->authorize()
            ->in('another-tag')
            ->can(respondToMentions: true)
            ->activate(),
];
```

So, the steps:

- Define a new Agent using `\Flarum\Ai\Extend\Ai` with 
  - a unique key (required)
  - the user to represent (required)
  - the chat gpt model (optional)
  - a discussion id of the instructions for chat gpt (optional, but without it chat gpt can do whatever it likes)
- Chain into that Ai, the instructions with
  - A tag slug (optional, but I seriously recommend using this with the tags extension)
  - The permissions, of which:
    - `replyToPosts`: the Ai replies to posts
    - `respondToMentions`: the Ai responds when mentioned
    - `initiate`: the Ai can create discussions (this is not functional yet)
  - Ending the chain for permissions using `activate()`.



### Links

- [Packagist](https://packagist.org/packages/flarum/ai-toolkit)
- [GitHub](https://github.com/flarum-com/ai-toolkit)
