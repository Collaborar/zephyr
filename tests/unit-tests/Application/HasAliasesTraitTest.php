<?php

namespace WPZephyrTests\Application;

use Mockery;
use WPZephyr\Application\HasAliasesTrait;
use WPZephyrTestTools\TestCase;

class HasAliasesTraitTest extends TestCase
{
    protected $subject;

    protected $resolver;

    public function setUp(): void
    {
        parent::setUp();

        $this->resolver = Mockery::mock();
        $this->subject = new HasAliasesTraitTestImplementation();
        $this->subject->resolver = $this->resolver;
    }

    public function tearDown(): void
    {
        unset($this->resolver);
        unset($this->subject);

        parent::tearDown();
    }

    public function testHasAlias() {
        $this->assertFalse($this->subject->hasAlias('foo'));
        $this->subject->alias('foo', 'bar');
        $this->assertTrue($this->subject->hasAlias('foo'));
    }

    public function testGetAlias()
    {
        $this->assertNull($this->subject->getAlias('foo'));
        $this->subject->alias('foo', 'bar', 'baz');
        $this->assertEquals([
            'name' => 'foo',
            'target' => 'bar',
            'method' => 'baz',
        ], $this->subject->getAlias('foo'));
    }

    public function testSetAlias_String_ResolveFromContainer()
    {
        $alias = 'test';
        $serviceKey = 'test_service';
        $service = new \WPZephyrTestTools\TestService();

        $this->resolver->shouldReceive('resolve')
            ->with($serviceKey)
            ->andReturn($service);

        $this->subject->setAlias([
            'name'   => $alias,
            'target' => $serviceKey,
        ]);

        $this->assertSame( $service, $this->subject->{$alias}() );
    }

    public function testSetAlias_StringWithMethod_ResolveFromContainer()
    {
        $alias = 'test';
        $serviceKey = 'test_service';
        $service = new \WPZephyrTestTools\TestService();

        $this->resolver->shouldReceive('resolve')
            ->with($serviceKey)
            ->andReturn($service);

        $this->subject->setAlias([
            'name'   => $alias,
            'target' => $serviceKey,
            'method' => 'getTest',
        ]);

        $this->assertSame('foobar', $this->subject->{$alias}());
    }

    public function testSetAlias_Closure_CallClosure() {
        $expected = 'foo';
        $alias = 'test';
        $closure = function () use ( $expected ) {
            return $expected;
        };

        $this->subject->setAlias([
            'name' => $alias,
            'target' => $closure,
        ]);

        $this->assertEquals($expected, $this->subject->{$alias}());
    }
}

class HasAliasesTraitTestImplementation
{
    use HasAliasesTrait;

    public $resolver = null;

    public function resolve(string $key): mixed
    {
        return $this->resolver->resolve($key);
    }
}
