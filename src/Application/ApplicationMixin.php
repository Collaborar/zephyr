<?php

declare(strict_types=1);

namespace Zephyr\Application;

use DI\Container;

/**
 * Can be applied to your App class via a "@mixin" annotation for better IDE support.
 * This class is not meant to be used in any other capacity.
 */
final class ApplicationMixin
{
    /**
     * Prevent class instantiation.
     */
    private function __construct()
    {
    }

    // --- Methods --------------------------------------- //

    /**
     * Get whether the application has been bootstrapped.
     *
     * @return bool
     */
    public static function isBootstrapped()
    {
    }

    /**
     * Bootstrap the application.
     *
     * @param  array $config
     *
     * @return void
     */
    public static function bootstrap(array $config = [])
    {
    }

    /**
     * Get the IoC container instance.
     *
     * @codeCoverageIgnore
     *
     * @return Container
     */
    public static function container()
    {
    }

    /**
     * Set the IoC container instance.
     *
     * @codeCoverageIgnore
     *
     * @param  Container $container
     *
     * @return void
     */
    public static function setContainer(?Container $container)
    {
    }

    /**
     * Resolve a dependency from the IoC container.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public static function resolve(string $key)
    {
    }

    /**
     * Get the Application instance.
     *
     * @codeCoverageIgnore
     *
     * @return Application
     */
    public static function app()
    {
    }
}
