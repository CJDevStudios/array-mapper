<?php
/*
 * This file is part of array-mapper.
 *
 * (c) CJ Development Studios <contact@cjdevstudios.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CJDevStudios\ArrayMapper\Tests;

use CJDevStudios\ArrayMapper\ArrayPathAccessor;
use CJDevStudios\ArrayMapper\Exception\InvalidPathException;
use PHPUnit\Framework\TestCase;

class ArrayPathAccessorTest extends TestCase
{

    public function testGetSimple()
    {
        $array = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];

        $this->assertEquals('bar', ArrayPathAccessor::get($array, 'foo'));
        $this->assertEquals('qux', ArrayPathAccessor::get($array, 'baz'));
    }

    public function testGetSimpleInvalidPath()
    {
        $array = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];

        $this->expectException(InvalidPathException::class);
        ArrayPathAccessor::get($array, 'invalid');
    }

    public function testGetNested()
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
        ];

        $this->assertEquals('baz', ArrayPathAccessor::get($array, 'foo.bar'));
    }

    public function testGetNestedInvalidPath()
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
        ];

        $this->expectException(InvalidPathException::class);
        ArrayPathAccessor::get($array, 'foo.invalid');
    }

    public function testSetSimple()
    {
        $array = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];

        ArrayPathAccessor::set($array, 'foo', 'new');
        $this->assertEquals('new', $array['foo']);
    }

    public function testSetSimpleInvalidPath()
    {
        $array = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];

        $this->expectException(InvalidPathException::class);
        // Only empty paths are invalid as non-existent paths are created on the fly
        ArrayPathAccessor::set($array, '', 'new');
    }

    public function testSetNested()
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
        ];

        ArrayPathAccessor::set($array, 'foo.bar', 'new');
        $this->assertEquals('new', $array['foo']['bar']);
    }

    public function testSetNestedInvalidPath()
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
        ];

        $this->expectException(InvalidPathException::class);
        // Only empty paths are invalid as non-existent paths are created on the fly
        ArrayPathAccessor::set($array, '', 'new');
    }
}
