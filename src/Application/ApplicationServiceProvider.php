<?php

declare(strict_types=1);

namespace WPZephyr\Application;

use DI\Container;
use WPZephyr\ServiceProviders\ExtendsConfigTrait;
use WPZephyr\ServiceProviders\ServiceProviderInterface;

class ApplicationServiceProvider implements ServiceProviderInterface
{
    use ExtendsConfigTrait;

    /**
	 * {@inheritDoc}
	 */
    public function register(Container $container): void
    {
        $this->extendConfig($container, 'providers', []);
    }

    /**
	 * {@inheritDoc}
	 */
    public function bootstrap(Container $container): void
    {
        // Nothing to bootstrap.
    }
}