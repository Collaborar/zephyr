<?php

namespace ZephyrTests\Application;

use Mockery;
use DI\Container;
use DI\NotFoundException;
use Zephyr\Application\Application;
use Zephyr\Exceptions\ApplicationException;
use Zephyr\ServiceProviders\ServiceProviderInterface;
use ZephyrTestTools\TestCase;

class ApplicationTest extends TestCase
{
    protected Container $container;

    protected Application $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->container = new Container();
        $this->subject = new Application($this->container);
        $this->container->set(Application::class, $this->subject);
    }

    public function tearDown(): void
    {
        unset($this->container);
        unset($this->subject);

        parent::tearDown();
    }

    public function testConstruct()
    {
        $container = new Container();
        $subject = new Application($container);

        $this->assertSame($container, $subject->container());
    }

    public function testIsBootstrapped()
    {
        $this->assertEquals(false, $this->subject->isBootstrapped());
        $this->subject->bootstrap([] ,false);
        $this->assertEquals(true, $this->subject->isBootstrapped());
    }

    public function testBootstrap_CalledMultipleTimes_ThrowException()
    {
        $this->expectException(ApplicationException::class);
        $this->expectExceptionMessage('already bootstrapped');
        $this->subject->bootstrap([], false);
        $this->subject->bootstrap([], false);
    }

    public function testBootstrap_RegisterServiceProviders()
    {
        $this->subject->bootstrap([
            'providers' => [
                ApplicationTestServiceProviderMock::class,
            ]
        ], false);

        $this->assertTrue(true);
    }

    public function testResolve_NonexistentKey_ReturnNull()
    {
        $containerKey = 'nonexistentcontainerkey';

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('No entry or class found for');
        $this->subject->bootstrap([], false);
        $this->subject->resolve($containerKey);
    }

    public function testResolve_ExistingKey_IsResolved()
    {
        $expected = 'foobar';
        $containerKey = 'test';

        $container = $this->subject->container();
        $container->set($containerKey, $expected);

        $this->subject->bootstrap([], false);
        $this->assertSame($expected, $this->subject->resolve( $containerKey ));
    }
}

class ApplicationTestServiceProviderMock implements ServiceProviderInterface {
    public $mock;

    public function __construct()
    {
        $this->mock = Mockery::mock(ServiceProviderInterface::class);
        $this->mock->shouldReceive('register')
            ->once();
        $this->mock->shouldReceive('bootstrap')
            ->once();
    }

    public function register(Container $container): void
    {
        $this->mock->register($container);
    }

    public function bootstrap(Container $container): void
    {
        $this->mock->bootstrap($container);
    }
}
