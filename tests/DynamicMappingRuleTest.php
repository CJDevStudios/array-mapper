<?php

namespace CJDevStudios\ArrayMapper\Tests;

use CJDevStudios\ArrayMapper\DynamicMappingRule;
use PHPUnit\Framework\TestCase;

class DynamicMappingRuleTest extends TestCase
{

    public function testDynamicMappingRule()
    {
        $source = ['a' => 'b', 'c' => 'd'];
        $target = [];
        $rule = new DynamicMappingRule(static function($s) {
            return $s['a'] . $s['c'];
        }, 'test');
        $rule->apply($source, $target);
        $this->assertArrayHasKey('test', $target);
        $this->assertEquals('bd', $target['test']);
    }
}
