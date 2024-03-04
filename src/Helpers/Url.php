<?php

declare(strict_types=1);

namespace Zephyr\Helpers;

/**
 * Collection of tools to deal with URLs.
 */
class Url
{
    /**
     * Ensure url does not have a trailing slash
     *
     * @param  string $url
     *
     * @return string
     */
    public static function removeTrailingSlash(string $url): string
    {
        return \untrailingslashit($url);
    }
}
