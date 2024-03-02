<?php

declare(strict_types=1);

namespace Zephyr\Application;

use DI\Container;
use Zephyr\ServiceProviders\ExtendsConfigTrait;
use Zephyr\ServiceProviders\ServiceProviderInterface;

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
