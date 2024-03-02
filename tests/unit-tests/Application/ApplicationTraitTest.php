<?php

namespace ZephyrTests\Application;

use BadMethodCallException;
use Zephyr\Application\ApplicationTrait;
use Zephyr\Exceptions\ApplicationException;
use ZephyrTestTools\TestCase;

class ApplicationTraitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        FooApp::setApplication(null);
        BarApp::setApplication(null);
    }

    public function testMake_NullInstance_NewInstance() {
        $this->assertNull(FooApp::getApplication());
        $app = FooApp::make();
        $this->assertSame($app, FooApp::getApplication());
    }

    public function testMake_OldInstance_NewInstance() {
        $old = FooApp::make();
        $this->assertSame($old, FooApp::getApplication());
        $new = FooApp::make();
        $this->assertSame($new, FooApp::getApplication());
    }

    public function testMake_MultipleApps_DifferentInstances() {
        $this->assertNull(FooApp::getApplication());
        $this->assertNull(BarApp::getApplication());

        $foo = FooApp::make();

        $this->assertSame($foo, FooApp::getApplication());
        $this->assertNull(BarApp::getApplication());

        $bar = BarApp::make();

        $this->assertSame($foo, FooApp::getApplication());
        $this->assertSame($bar, BarApp::getApplication());
        $this->assertNotSame(FooApp::getApplication(), BarApp::getApplication());
    }

    public function testCallStatic_NullInstance_Exception() {
        $this->expectException(ApplicationException::class);
        $this->expectExceptionMessage('Application instance not created');
        FooApp::foo();
    }

    public function testCallStatic_InvalidMethod_Exception() {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('does not exist');
        FooApp::make();
        FooApp::traitTestMagicMethod();
    }

    public function testCallStatic_Method_MethodCalled() {
        FooApp::make();
        FooApp::alias('traitTestMagicMethod', fn () => 'foo');

        $this->assertTrue(FooApp::hasAlias('traitTestMagicMethod'));
    }

    public function testCallStatic_MagicMethod_MethodCalled() {
        FooApp::make();
        FooApp::alias('traitTestMagicMethod', fn () => 'foo');

        $this->assertSame( 'foo', FooApp::traitTestMagicMethod() );
    }
}

class FooApp
{
    use ApplicationTrait;
}

class BarApp
{
    use ApplicationTrait;
}
