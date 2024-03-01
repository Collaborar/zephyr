<?php

declare(strict_types=1);

namespace WPZephyr\Application;

use DI\Container;
use WPZephyr\Exceptions\ApplicationException;

/**
 * Application.

 */
class Application
{
    use HasAliasesTrait,
        HasContainerTrait,
        LoadServiceProvidersTrait;

    /**
     * Indicates if the application has been bootstrapped.
     *
     * @var bool
     */
    protected bool $bootstrapped = false;

    /**
     * Constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container);
        // Register the application into container.
        $this->container()->set(self::class, $this);
    }

    /**
     * Make a new application.
     *
     * @return self
     */
    public static function make(): self
    {
        return new self(new Container());
    }

    /**
     * Bootstrap the application.
     *
     * @param array $config
     *
     * @return void
     */
    public function bootstrap(array $config = []): void
    {
        if ($this->isBootstrapped()) {
            throw new ApplicationException(sprintf('%s already bootstrapped.', static::class));
        }

        $this->bootstrapped = true;
        $container = $this->container();

        $this->container()->set(WPZEPHYR_CONFIG_KEY, $config);
        $this->loadProviders($container);
    }

    /**
     * Indicates if the application has been bootstrapped.
     *
     * @return bool
     */
    public function isBootstrapped(): bool
    {
        return $this->bootstrapped;
    }
}
