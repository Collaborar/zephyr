<?php

declare(strict_types=1);

namespace Zephyr\Filesystem;

use DI\Container;
use Zephyr\ServiceProviders\ServiceProviderInterface;

/**
 * Provide Filesystem dependencies.
 *
 * @todo Remove it from ignoreFiles on Psalm.
 */
class FilesystemServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $container): void
    {
        $container->set(
            Filesystem::class,
            static function (): mixed {
                // phpcs:ignore
                global $wp_filesystem;

                require_once ABSPATH.'/wp-admin/includes/file.php';

                \WP_Filesystem();

                // phpcs:ignore
                return $wp_filesystem;
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    public function bootstrap(Container $container): void
    {
        // Nothing to bootstrap.
    }
}
