<?php

namespace Blomstra\Ai;

use Blomstra\Ai\Agent\Collection;
use Blomstra\Ai\Interaction\Feature;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Container\Container;
use OpenAI;

class Boot
{
    static private bool $booted = false;

    public function __invoke(Container $container): void
    {
        if (static::$booted) return;

        /** @var SettingsRepositoryInterface $settings */
        $settings = $container->make(SettingsRepositoryInterface::class);

        $apiKey = $settings->get('blomstra-ai-toolkit.openai-api-key');
        $organisation = $settings->get('blomstra-ai-toolkit.openai-api-organisation');

        if ($apiKey) {
            $client = OpenAI::client($apiKey, $organisation);
            $container->singleton('blomstra-ai-client', fn() => $client);
            Agent::setClient($client);
        }

        // Class to help identify what we can do.
        $container->singleton(Feature::class);
        // Class to help centrally register any agents.
        $container->singleton(Collection::class);

        static::$booted = true;
    }
}
