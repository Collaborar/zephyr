<?php

declare(strict_types=1);

namespace WPZephyr\Application;

use DI\Container;
use WPZephyr\ServiceProviders\ExtendsConfigTrait;
use WPZephyr\ServiceProviders\ServiceProviderInterface;

/**
 * Application Service Provider.
 */
class ApplicationServiceProvider implements ServiceProviderInterface
{
    use ExtendsConfigTrait;

    /**
     * {@inheritDoc}
     */
    public function register(Container $container): void
    {
        $this->extendConfig($container, 'providers', []);

        $app = $container->get(Application::class);
        $app->alias('app', Application::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bootstrap(Container $container): void
    {
        // Nothing to bootstrap.
    }
}
