<?php

declare(strict_types=1);

namespace Jasny\Immutable\Tests;

use Jasny\Immutable\NoDynamicProperties;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Immutable\NoDynamicProperties
 */
class NoDynamicPropertiesTest extends TestCase
{
    protected $object;

    public function setUp(): void
    {
        $this->object = new class ()
        {
            use NoDynamicProperties;

            public $foo;
        };
    }

    public function testWithExistingProperty()
    {
        $this->expectNotToPerformAssertions();

        $this->object->foo = 10;
    }

    public function testWithNonExistingProperty()
    {
        $this->expectException(\LogicException::class);

        $this->object->bar = 10;
    }
}
