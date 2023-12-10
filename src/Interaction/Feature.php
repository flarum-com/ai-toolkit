<?php

namespace Blomstra\Ai\Interaction;

use Flarum\Extension\ExtensionManager;

class Feature
{
    public function __construct(private readonly ExtensionManager $extensions)
    {}

    public function mention(): bool
    {
        return $this->extensions->isEnabled('flarum-mentions');
    }

    public function flag(): bool
    {
        return $this->extensions->isEnabled('flarum-flags');
    }

    public function suspend(): bool
    {
        return $this->extensions->isEnabled('flarum-suspend');
    }

    public function tags(): bool
    {
        return $this->extensions->isEnabled('flarum-tags');
    }
}
