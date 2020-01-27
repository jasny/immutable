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

    public function setUp(): void
    {
        $this->object = new class ()
        {
            use Immutable\With;

            public $foo;
            public $colors = [];
            public $items = [];

            public function withFoo($value): self
            {
                return $this->withProperty('foo', $value);
            }

            public function withoutFoo(): self
            {
                return $this->withoutProperty('foo');
            }

            public function withColor($key, $value): self
            {
                return $this->withPropertyKey('colors', $key, $value);
            }

            public function withoutColor($key): self
            {
                return $this->withoutPropertyKey('colors', $key);
            }

            public function withAddedItem($value): self
            {
                return $this->withPropertyItem('items', $value);
            }

            public function withAddedUniqueItem($value): self
            {
                return $this->withPropertyItem('items', $value, true);
            }

            public function withRemovedItem($value): self
            {
                return $this->withoutPropertyItem('items', $value);
            }


            public function withBar($value): self
            {
                return $this->withProperty('bar', $value);
            }

            public function withBarKey($key, $value): self
            {
                return $this->withPropertyKey('bar', $key, $value);
            }

            public function withBarItem($value): self
            {
                return $this->withPropertyItem('bar', $value);
            }

            public function withoutBar(): self
            {
                return $this->withoutProperty('bar');
            }

            public function withoutBarKey($key): self
            {
                return $this->withoutPropertyKey('bar', $key);
            }

            public function withoutBarItem($value): self
            {
                return $this->withoutPropertyItem('bar', $value);
            }
        };
    }

    public function testWithProperty()
    {
        $foo42 = $this->object->withFoo(42);
        $this->assertInstanceOf(get_class($this->object), $foo42);
        $this->assertNotSame($this->object, $foo42);
        $this->assertEquals(42, $foo42->foo);

        $this->assertSame($foo42, $foo42->withFoo(42));

        $foo99 = $foo42->withFoo(99);
        $this->assertInstanceOf(get_class($this->object), $foo99);
        $this->assertNotSame($foo42, $foo99);
        $this->assertEquals(99, $foo99->foo);

        $fooNull = $foo99->withoutFoo();
        $this->assertInstanceOf(get_class($this->object), $fooNull);
        $this->assertNotSame($foo99, $fooNull);
        $this->assertFalse(isset($fooNull->foo));

        $this->assertSame($fooNull, $fooNull->withoutFoo());
    }

    public function testWithPropertyKey()
    {
        $fooRed = $this->object->withColor('red', 0xff);
        $this->assertInstanceOf(get_class($this->object), $fooRed);
        $this->assertNotSame($this->object, $fooRed);
        $this->assertEquals(['red' => 0xff], $fooRed->colors);

        $this->assertSame($fooRed, $fooRed->withColor('red', 0xff));

        $fooPurple = $fooRed->withColor('blue', 0xd0);
        $this->assertInstanceOf(get_class($this->object), $fooPurple);
        $this->assertNotSame($fooRed, $fooPurple);
        $this->assertEquals(['red' => 0xff, 'blue' => 0xd0], $fooPurple->colors);

        $fooLila = $fooPurple->withColor('red', 0x40);
        $this->assertInstanceOf(get_class($this->object), $fooLila);
        $this->assertNotSame($fooPurple, $fooLila);
        $this->assertEquals(['red' => 0x40, 'blue' => 0xd0], $fooLila->colors);

        $fooPink = $fooLila->withoutColor('blue');
        $this->assertInstanceOf(get_class($this->object), $fooPink);
        $this->assertNotSame($fooLila, $fooPink);
        $this->assertEquals(['red' => 0x40], $fooPink->colors);

        $this->assertSame($fooPink, $fooPink->withoutColor('blue'));
    }

    public function testWithPropertyItem()
    {
        $fooApple = $this->object->withAddedItem('apple');
        $this->assertInstanceOf(get_class($this->object), $fooApple);
        $this->assertNotSame($this->object, $fooApple);
        $this->assertEquals(['apple'], $fooApple->items);

        $this->assertSame($fooApple, $fooApple->withAddedUniqueItem('apple'));

        $fooApples = $fooApple->withAddedItem('apple');
        $this->assertInstanceOf(get_class($this->object), $fooApples);
        $this->assertNotSame($fooApple, $fooApples);
        $this->assertEquals(['apple', 'apple'], $fooApples->items);

        $fooFruit = $fooApples->withAddedUniqueItem('pear');
        $this->assertInstanceOf(get_class($this->object), $fooFruit);
        $this->assertNotSame($fooApples, $fooFruit);
        $this->assertEquals(['apple', 'apple', 'pear'], $fooFruit->items);

        $fooPear = $fooFruit->withRemovedItem('apple');
        $this->assertInstanceOf(get_class($this->object), $fooPear);
        $this->assertNotSame($fooFruit, $fooPear);
        $this->assertEquals(['pear'], $fooPear->items);

        $this->assertSame($fooFruit, $fooFruit->withRemovedItem('melon'));
    }


    public function testWithPropertyOnNonExisting()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->object->withBar(1);
    }

    public function testWithoutPropertyOnNonExisting()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->object->withoutBar();
    }

    public function testWithPropertyKeyOnNonExisting()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->object->withBarKey('abc', 42);
    }

    public function testWithPropertyItemOnNonExisting()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->object->withBarItem(1);
    }


    public function testWithPropertyKeyOnScalar()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->object->colors = '#ff6010';
        $this->object->withColor('red', 0xff);
    }

    public function testWithoutPropertyKeyOnScalar()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->object->colors = '#ff6010';
        $this->object->withoutColor('red');
    }

    public function testWithPropertyItemOnScalar()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->object->items = null;
        $this->object->withAddedItem(1);
    }

    public function testWithoutPropertyItemOnScalar()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->object->items = null;
        $this->object->withRemovedItem(1);
    }

    public function testWithoutPropertyKeyOnNonExisting()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->object->withoutBarKey('abc');
    }

    public function testWithoutPropertyItemOnNonExisting()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->object->withoutBarItem(1);
    }
}
