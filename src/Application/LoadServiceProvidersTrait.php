<?php

declare(strict_types=1);

namespace Zephyr\Application;

use DI\Container;
use Zephyr\Exceptions\ApplicationException;
use Zephyr\ServiceProviders\ServiceProviderInterface;
use Zephyr\Support\Arr;

/**
 * Load Service Providers
 */
trait LoadServiceProvidersTrait
{
    /**
     * Service Providers collection.
     *
     * @var array
     */
    protected array $serviceProviders = [
        ApplicationServiceProvider::class,
    ];

    /**
     * Load Service Providers.
     *
     * @param Container $container
     *
     * @return void
     */
    protected function loadProviders(Container $container): void
    {
        $config = $container->get(ZEPHYR_CONFIG_KEY);
        $container->set(
            ZEPHYR_SERVICE_PROVIDERS_KEY,
            array_merge(
                $this->serviceProviders,
                Arr::get($config, 'providers', [])
            )
        );

        $serviceProviders = array_map(
            static function (string $provider) use ($container) {
                if (!is_subclass_of($provider, ServiceProviderInterface::class)) {
                    throw new ApplicationException(sprintf(
                        'The following class is not defined or does not implement `%s`: %s',
                        ServiceProviderInterface::class,
                        $provider
                    ));
                }

                // Provide container access to the service provider instance
                // so bootstrap hooks can be unhooked e.g.:
                // remove_action( 'some_action', [\App::resolve( SomeServiceProvider::class ), 'methodAddedToAction'] );
                $container->set($provider, new $provider());

                return $container->get($provider);
            },
            $container->get(ZEPHYR_SERVICE_PROVIDERS_KEY)
        );

        $this->execRegister($serviceProviders, $container);
        $this->execBootstrap($serviceProviders, $container);
    }

    /**
     * Execute register throught service providers.
     *
     * @param array     $serviceProviders
     * @param Container $container
     *
     * @return void
     */
    protected function execRegister(array $serviceProviders, Container $container): void
    {
        foreach ($serviceProviders as $provider) {
            $provider->register($container);
        }
    }

    /**
     * Execute bootstrap throught service providers.
     *
     * @param array     $serviceProviders
     * @param Container $container
     *
     * @return void
     */
    protected function execBootstrap(array $serviceProviders, Container $container): void
    {
        foreach ($serviceProviders as $provider) {
            $provider->bootstrap($container);
        }
    }
}
