<?php

namespace ZephyrTests\Application;

use Mockery;
use Zephyr\Helpers\Url;
use ZephyrTestTools\TestCase;

class UrlTest extends TestCase
{
    public function testRemoveTrailingSlash()
    {
        $this->assertEquals('example', Url::removeTrailingSlash('example/'));
        $this->assertEquals('example', Url::removeTrailingSlash('example'));
    }
}
