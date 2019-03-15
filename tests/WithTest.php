<?php

namespace Jasny\Immutable\Tests;

use Jasny\Immutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Immutable\With
 */
class WithTest extends TestCase
{
    /**
     * @var object
     */
    protected $object;

    public function setUp()
    {
        $this->object = new class () {
            use Immutable\With;

            protected $foo;

            public function withFoo($value)
            {
                return $this->with('foo', $value);
            }

            public function getFoo()
            {
                return $this->foo;
            }

            public function withBar($value)
            {
                return $this->with('bar', $value);
            }
        };
    }

    public function test()
    {
        $changedObject = $this->object->withFoo(42);
        $this->assertInstanceOf(get_class($this->object), $changedObject);
        $this->assertNotSame($this->object, $changedObject);
        $this->assertEquals(42, $changedObject->getFoo());

        $againObject = $changedObject->withFoo(42);
        $this->assertSame($changedObject, $againObject);

        $nextObject = $changedObject->withFoo(99);
        $this->assertInstanceOf(get_class($this->object), $nextObject);
        $this->assertNotSame($this->object, $nextObject);
        $this->assertNotSame($changedObject, $nextObject);
        $this->assertEquals(99, $nextObject->getFoo());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testBadMethodCall()
    {
        $this->object->withBar(1);
    }
}


