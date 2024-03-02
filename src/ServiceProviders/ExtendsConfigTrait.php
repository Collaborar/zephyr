<?php

declare(strict_types=1);

namespace Zephyr\ServiceProviders;

use DI\Container;
use Zephyr\Support\Arr;

/**
 * Allows objects to extend the config.
 */
trait ExtendsConfigTrait
{
    /**
     * Recursively replace default values with the passed config.
     * - If either value is not an array, the config value will be used.
     * - If both are an indexed array, the config value will be used.
     * - If either is a keyed array, array_replace will be used with config having priority.
     *
     * @param  mixed $default
     * @param  mixed $config
     *
     * @return mixed
     */
    protected function replaceConfig(mixed $default, mixed $config): mixed
    {
        if (!is_array($default) || !is_array($config)) {
            return $config;
        }

        $defaultIsIndexed = array_keys($default) === range(0, count($default) - 1);
        $configIsIndexed = array_keys($config) === range(0, count($config) - 1);

        if ($defaultIsIndexed && $configIsIndexed) {
            return $config;
        }

        $result = $default;

        foreach ($config as $key => $value) {
            $result[$key] = $this->replaceConfig(Arr::get($default, $key), $value);
        }

        return $result;
    }

    /**
     * Extends the Zephyr config in the container with a new key.
     *
     * @param  Container $container
     * @param  string    $key
     * @param  mixed     $default
     *
     * @return void
     */
    public function extendConfig(Container $container, string $key, mixed $default): void
    {
        $config = $container->get(ZEPHYR_CONFIG_KEY);
        $config = isset($config) ? $config : [];
        $config = Arr::get($config, $key, $default);

        $container->set(
            ZEPHYR_CONFIG_KEY,
            array_merge(
                $container->get(ZEPHYR_CONFIG_KEY),
                [$key => $this->replaceConfig($default, $config)]
            )
        );
    }
}
