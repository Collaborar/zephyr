<?php

declare(strict_types=1);

namespace WPZephyr\Application;

use DI\Container;

trait HasContainerTrait
{
    /**
     * The container.
     *
     * @var Container|null
     */
    protected ?Container $container = null;

    /**
     * Define the container.
     *
     * @param Container|null $container
     * @return void
     */
    public function setContainer(?Container $container): void
    {
        $this->container = $container;
    }

    /**
     * Retrieves the container.
     *
     * @return Container|null
     */
    public function container(): ?Container
    {
        return $this->container;
    }

    /**
     * Resolve dependency from container.
     *
     * @param string $key
     * @return mixed
     */
    public function resolve(string $key): mixed
    {
        return $this->container()->get($key);
    }
}
