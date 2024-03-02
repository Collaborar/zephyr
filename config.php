<?php

/**
 * Current version.
 */
if ( ! defined( 'ZEPHYR_VERSION' ) ) {
	define( 'ZEPHYR_VERSION', '1.0.0' );
}

/**
 * Service container keys.
 */
if (!defined('ZEPHYR_CONFIG_KEY')) {
    define('ZEPHYR_CONFIG_KEY', 'zephyr.config');
}

if (!defined('ZEPHYR_SERVICE_PROVIDERS_KEY')) {
    define('ZEPHYR_SERVICE_PROVIDERS_KEY', 'zephyr.service_providers');
}
