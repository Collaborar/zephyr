<?php

declare(strict_types=1);

namespace Zephyr\Helpers;

use Zephyr\Exceptions\ClassNotFoundException;

/**
 * Mixed Type
 */
class MixedType
{
    /**
     * Converts a value to an array containing this value unless it is an array.
     * This will not convert objects like (array) casting does.
     *
     * @param  mixed $argument
     *
     * @return array
     */
    public static function toArray(mixed $argument): array
    {
        return is_array($argument) ? $argument : [$argument];
    }

    /**
     * Executes a value depending on what type it is and returns the result
     * Callable: call; return result
     * Instance: call method; return result
     * Class:    instantiate; call method; return result
     * Other:    return value without taking any action
     *
     * @noinspection PhpDocSignatureInspection
     *
     * @param  mixed           $entity
     * @param  array           $arguments
     * @param  string          $method
     * @param  callable|string $instantiator
     *
     * @return mixed
     */
    public static function value(
        mixed $entity,
        array $arguments = [],
        string $method = '__invoke',
        callable|string $instantiator = 'static::instantiate'
    ): mixed {
        return match (true) {
            is_callable($entity)     => call_user_func_array($entity, $arguments),
            is_object($entity)       => call_user_func_array([$entity, $method], $arguments),
            static::isClass($entity) => call_user_func_array([call_user_func($instantiator, $entity), $method], $arguments),
            default                  => $entity
        };
    }

    /**
     * Check if a value is a valid class name
     *
     * @param  mixed $class
     *
     * @return boolean
     */
    public static function isClass(mixed $class): bool
    {
        return (is_string($class) && class_exists($class));
    }

    /**
     * Create a new instance of the given class.
     *
     * @param string $class
     *
     * @return object
     */
    public static function instantiate(string $class): object
    {
        if (!class_exists($class)) {
            throw new ClassNotFoundException(sprintf(
                'Class not found: %s',
                $class
            ));
        }

        return new $class();
    }

    /**
     * Normalize a path's slashes according to the current OS.
     * Solves mixed slashes that are sometimes returned by WordPress core functions.
     *
     * @param  string $path
     * @param  string $slash
     *
     * @return string
     */
    public static function normalizePath(string $path, string $slash = DIRECTORY_SEPARATOR): string
    {
        return preg_replace('~['.preg_quote('/\\', '~').']+~', $slash, $path);
    }

    /**
     * Ensure path has a trailing slash.
     *
     * @param  string $path
     * @param  string $slash
     *
     * @return string
     */
    public static function addTrailingSlash(string $path, string $slash = DIRECTORY_SEPARATOR): string
    {
        $path = static::normalizePath($path, $slash);
        $path = preg_replace('~'.preg_quote($slash, '~').'*$~', $slash, $path);

        return $path;
    }

    /**
     * Ensure path does not have a trailing slash.
     *
     * @param  string $path
     * @param  string $slash
     *
     * @return string
     */
    public static function removeTrailingSlash(string $path, string $slash = DIRECTORY_SEPARATOR): string
    {
        $path = static::normalizePath($path, $slash);
        $path = preg_replace('~'.preg_quote($slash, '~').'+$~', '', $path);

        return $path;
    }
}
