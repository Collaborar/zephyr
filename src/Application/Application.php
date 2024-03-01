<?php

declare(strict_types=1);

namespace WPZephyr\Application;

use DI\Container;
use WPZephyr\Exceptions\ApplicationException;

class Application
{
    use HasContainerTrait;
    use LoadServiceProvidersTrait;

    /**
     * Indicates if the application has been bootstrapped.
     *
     * @var bool
     */
    protected bool $bootstrapped = false;

    public function __construct(Container $container)
    {
        $this->setContainer($container);
        // Register the application into container.
        $this->container()->set(self::class, $this);
    }

    /**
     * Make a new application.
     *
     * @return static
     */
    public static function make(): static
    {
        return new static(new Container());
    }

    public function bootstrap(array $config = []): void
    {
        if ($this->isBootstrapped()) {
            throw new ApplicationException(
                static::class . ' already bootstrapped.'
            );
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
