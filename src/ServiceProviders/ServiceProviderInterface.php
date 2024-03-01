<?php

declare(strict_types=1);

namespace WPZephyr\ServiceProviders;

use DI\Container;

/**
 * Implement it into your service provider.
 */
interface ServiceProviderInterface
{
    /**
     * Register dependencies into container.
     *
     * @param Container $container
     *
     * @return void
     */
    public function register(Container $container): void;

    /**
     * Bootstrap anything if needed.
     *
     * @param Container $container
     *
     * @return void
     */
    public function bootstrap(Container $container): void;
}
