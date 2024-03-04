<?php

declare(strict_types=1);

namespace Zephyr\Application;

use DI\Container;

/**
 * Allows use Dependency Injection.
 */
trait HasContainerTrait
{
    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Define the container.
     *
     * @param Container $container
     *
     * @return void
     */
    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    /**
     * Retrieves the container.
     *
     * @return Container
     */
    public function container(): Container
    {
        return $this->container;
    }

    /**
     * Resolve dependency from container.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function resolve(string $key): mixed
    {
        return $this->container()->get($key);
    }
}
