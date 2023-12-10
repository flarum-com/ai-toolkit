<?php

namespace Blomstra\Ai\Extend;

use Blomstra\Ai\Agent;
use Blomstra\Ai\Agent\Authorization;
use Blomstra\Ai\Agent\Collection;
use Blomstra\Ai\Agent\Model;
use Blomstra\Ai\Boot;
use Flarum\Discussion\Discussion;
use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use Flarum\User\User;
use Illuminate\Contracts\Container\Container;

class Ai implements ExtenderInterface
{
    private array $authorizations = [];

    public function __construct(
        protected string $key,
        protected readonly int|string $represents,
        protected readonly Model|string $model,
        protected int|null $instructions,
    )
    {}

    public function authorize(): Authorization
    {
        $authorization = new Authorization($this);

        $this->authorizations[] = $authorization;

        return $authorization;
    }

    public function extend(Container $container, Extension $extension = null)
    {
        // Prevent loading unnecessary code/features into memory if no agents are used.
        $this->boot($container);

        $agent = new Agent(
            key: $this->key,
            userKey: $this->represents,
            model: $this->resolveModel(),
            authorizations: $this->authorizations,
            instructions: $this->instructions
        );

        /** @var Collection $collection */
        $collection = $container->make(Collection::class);

        $collection->put($this->key, $agent);
    }

    private function resolveModel(): ?Model
    {
        return match(true) {
            is_string($this->model) => Model::tryFrom($this->model),
            default => $this->model
        };
    }

    private function boot(Container $container): void
    {
        $boot = $container->make(Boot::class);

        $boot($container);
    }
}
