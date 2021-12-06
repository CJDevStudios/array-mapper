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

use CJDevStudios\ArrayMapper\Exception\MappingException;
use CJDevStudios\ArrayMapper\Mapper;
use CJDevStudios\ArrayMapper\MappingRule;
use CJDevStudios\ArrayMapper\MappingSchema;
use PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{

    /**
     * Test mapping an array with no rules and no default mapper.
     * The result should be an empty array.
     */
    public function testEmptyMap() {
        $schema = new MappingSchema();
        $mapper = new Mapper($schema);
        $result = $mapper->map(['a' => 1, 'b' => 2, 'c' => ['d' => 3]]);
        $this->assertEquals([], $result);
    }

    /**
     * Test mapping an array with no rules and a default mapper.
     * The result should be an array with the same keys and values.
     */
    public function testDefaultMap()
    {
        $schema = new MappingSchema();
        $schema->setDefaultMapper(static function ($source_path, $source_value) {
            return [$source_path, $source_value];
        });
        $mapper = new Mapper($schema);
        $result = $mapper->map(['a' => 1, 'b' => 2, 'c' => ['d' => 3]]);
        $this->assertEquals(['a' => 1, 'b' => 2, 'c' => ['d' => 3]], $result);
    }

    public function testAllowMissingSourcePath()
    {
        // Test ignoring missing source paths.
        $schema = new MappingSchema();
        $schema->addMappingRule((new MappingRule('d', 'b'))->setIgnoreMissing(true));
        $mapper = new Mapper($schema);
        $result = $mapper->map(['a' => 1, 'b' => 2, 'c' => ['d' => 3]]);
        $this->assertEquals([], $result);
    }

    public function testNotAllowMissingSourcePath()
    {
        // Test throwing an exception when missing source paths.
        $schema = new MappingSchema();
        $schema->addMappingRule((new MappingRule('d', 'b'))->setIgnoreMissing(false));
        $mapper = new Mapper($schema);
        $this->expectException(MappingException::class);
        $mapper->map(['a' => 1, 'b' => 2, 'c' => ['d' => 3]]);
    }

    public function testNestedMap()
    {
        $schema = new MappingSchema();
        $schema->addMappingRule((new MappingRule('c.d', 'e'))->setIgnoreMissing(true));
        $mapper = new Mapper($schema);
        $result = $mapper->map(['a' => 1, 'b' => 2, 'c' => ['d' => 3]]);
        $this->assertEquals(['e' => 3], $result);
    }
}
