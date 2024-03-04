<?php

namespace ZephyrTests\Application;

use Mockery;
use Zephyr\Helpers\MixedType;
use ZephyrTestTools\{ TestCase, TestService };

class MixedTypeTest extends TestCase
{
    public function callableStub(string $message = 'foobar')
    {
        return $message;
    }

    public function testToArray_String_ReturnArrayContainingString()
    {
        $parameter = 'foobar';
        $expected = [$parameter];

        $this->assertEquals($expected, MixedType::toArray($parameter));
    }

    public function testToArray_Array_ReturnSameArray()
    {
        $expected = ['foobar'];

        $this->assertEquals($expected, MixedType::toArray($expected));
    }

    public function testValue_Callable_CallAndReturn()
    {
        $callable = [$this, 'callableStub'];
        $expected = 'foobar';

        $this->assertEquals($expected, MixedType::value($callable));
    }

    public function testValue_CallableWithArguments_CallAndReturn()
    {
        $callable = [$this, 'callableStub'];
        $expected = 'hello world';

        $this->assertEquals($expected, MixedType::value($callable, [$expected]));
    }

    public function testValue_Instance_CallInstanceMethodAndReturn()
    {
        $expected = 'foobar';

        $this->assertEquals($expected, MixedType::value($this, [], 'callableStub'));
    }

    public function testValue_ClassName_CreateInstanceCallMethodAndReturn()
    {
        $expected = 'foobar';

        $this->assertEquals($expected, MixedType::value(TestService::class, [], 'getTest'));
    }

    public function testValue_ClassNameWithInstantiator_UsesInstantiator()
    {
        $method = 'foo';
        $expected = 'bar';
        $instantiator = function () use ($method, $expected) {
            $mock = Mockery::mock();

            $mock->shouldReceive( $method )
                ->andReturn( $expected );

            return $mock;
        };

        $this->assertEquals($expected, MixedType::value(TestService::class, [], $method, $instantiator));
    }

    public function testValue_Other_ReturnSame()
    {
        $expected = 'someStringThatIsNotACallable';

        $this->assertSame($expected, MixedType::value($expected));
    }

    public function testIsClass()
    {
        $this->assertTrue(MixedType::isClass('stdClass'));
        $this->assertTrue(MixedType::isClass(TestService::class));
        $this->assertFalse(MixedType::isClass('NonExistentClassName'));
        $this->assertFalse(MixedType::isClass(1));
        $this->assertFalse(MixedType::isClass(new \stdClass()));
        $this->assertFalse(MixedType::isClass([]));
    }

    public function testInstantiate()
    {
        $expected = TestService::class;

        $this->assertInstanceOf($expected, MixedType::instantiate(TestService::class));
    }

    public function testNormalizePath()
    {
        $ds = DIRECTORY_SEPARATOR;
        $input = '/foo\\bar/baz\\\\foobar';

        $this->assertEquals("{$ds}foo{$ds}bar{$ds}baz{$ds}foobar", MixedType::normalizePath($input));
        $this->assertEquals('/foo/bar/baz/foobar', MixedType::normalizePath($input, '/'));
        $this->assertEquals('\\foo\\bar\\baz\\foobar', MixedType::normalizePath($input, '\\'));
    }

    public function testAddTrailingSlash()
    {
        $input = '/foo';

        $this->assertEquals('/foo/', MixedType::addTrailingSlash($input, '/'));
    }

    public function testRemoveTrailingSlash()
    {
        $input = '/foo/';

        $this->assertEquals('/foo', MixedType::removeTrailingSlash($input, '/'));
    }
}
