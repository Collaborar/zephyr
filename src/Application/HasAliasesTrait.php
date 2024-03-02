<?php

declare(strict_types=1);

namespace Zephyr\Application;

use BadMethodCallException;
use Closure;
use Zephyr\Support\Arr;

/**
 * Add methods to classes at runtime.
 */
trait HasAliasesTrait
{
    /**
     * Registered aliases.
     *
     * @var array<string, array{name: string, target: Closure|string, method: string}>
     */
    protected array $aliases = [];

    /**
     * Indicates if alias exists.
     *
     * @param string $alias
     *
     * @return bool
     */
    public function hasAlias(string $alias): bool
    {
        return isset($this->aliases[$alias]);
    }

    /**
     * Register alias.
     * Identical to setAlias() but with a more user-friendly interface.
     *
     * @param string         $alias
     * @param string|Closure $target
     * @param string         $method
     *
     * @return void
     */
    public function alias(string $alias, string|Closure $target, string $method = ''): void
    {
        $this->setAlias([
            'name'   => $alias,
            'target' => $target,
            'method' => $method,
        ]);
    }

    /**
     * Register an alias.
     * Useful when passed the return value of getAlias() to restore
     * a previously registered alias, for example.
     *
     * @param array{ name: string, target: Closure|string, method: string } $alias
     *
     * @return void
     */
    public function setAlias(array $alias): void
    {
        $name = Arr::get($alias, 'name');

        $this->aliases[$name] = [
            'name'   => $name,
            'target' => Arr::get($alias, 'target'),
            'method' => Arr::get($alias, 'method', ''),
        ];
    }

    /**
     * Retrieves a registered alias.
     *
     * @param string $alias
     *
     * @return array{name: string, target: Closure|string, method: string}|null
     */
    public function getAlias(string $alias): ?array
    {
        return $this->hasAlias($alias)
            ? $this->aliases[$alias]
            : null;
    }

    /**
     * Call alias if it is registered.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     *
     * @template T
     */
    public function __call(string $method, array $parameters): mixed
    {
        if (!$this->hasAlias($method)) {
            throw new BadMethodCallException(sprintf('Method %s does not exist.', $method));
        }

        /** @var array{name?: string, target: Closure|string, method: string} $alias */
        $alias = $this->aliases[$method];

        if ($alias['target'] instanceof Closure) {
            $closure = $alias['target']->bindTo($this, static::class);

            if (!($closure instanceof Closure)) {
                throw new BadMethodCallException(sprintf('Invalid target type for alias %s.', $method));
            }

            return $closure(...$parameters);
        }

        $target = $this->resolve($alias['target']);

        if (!empty($alias['method'])) {
            $methodName = $alias['method'];
            /** @var T */

            return $target->{$methodName}(...$parameters);
        }

        return $target;
    }

    /**
     * Resolve dependency from container.
     *
     * @param string $key
     *
     * @return mixed
     */
    abstract public function resolve(string $key): mixed;
}
