<?php

declare(strict_types=1);

namespace WPZephyr\Application;

use WPZephyr\Exceptions\ApplicationException;

/**
 * Provide static access to an application instance.
 *
 * @mixin ApplicationMixin
 */
trait ApplicationTrait
{
    /**
     * Application instance
     *
     * @var Application|null
     */
    public static ?Application $instance = null;

    /**
     * Make a new application instance.
     *
     * @return Application
     */
    public static function make(): Application
    {
        self::setApplication(Application::make());

        return self::getApplication();
    }

    /**
     * Retrieves the current application instance.
     *
     * @return Application|null
     */
    public static function getApplication(): ?Application
    {
        return self::$instance;
    }

    /**
     * Set the Application instance.
     *
     * @param Application|null $application
     *
     * @return void
     */
    public static function setApplication(?Application $application): void
    {
        self::$instance = $application;
    }

    /**
     * Invoke any matching instance method for the static method being called.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     *
     * @throws ApplicationException
     */
    public static function __callStatic(string $method, array $parameters): mixed
    {
        $application = self::getApplication();

        if (!$application) {
            throw new ApplicationException(sprintf(
                'Application instance not created in %s.
                Did you miss to call %s::make()?',
                self::class,
                self::class,
            ));
        }

        /** @var mixed */

        return $application->{$method}(...$parameters);
    }
}
