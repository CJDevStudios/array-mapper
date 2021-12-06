<?php
/*
 * This file is part of array-mapper.
 *
 * (c) CJ Development Studios <contact@cjdevstudios.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CJDevStudios\ArrayMapper;

class Mapper
{

    private MappingSchema $schema;

    public function __construct(MappingSchema $schema)
    {
        $this->schema = $schema;
    }

    public function map(array $data): array
    {
        $mapped_top_level_properties = [];
        $result = [];
        foreach ($this->schema->getMappingRules() as $rule) {
            $rule->apply($data, $result);
            if (strpos($rule->getSourcePath(), ArrayPathAccessor::DELIMITER) === false) {
                $mapped_top_level_properties[] = $rule->getSourcePath();
            }
        }

        $default_mapper = $this->schema->getDefaultMapper();
        if ($default_mapper !== null) {
            $top_level_properties = array_keys($data);
            $unmapped_top_level_properties = array_diff($top_level_properties, $mapped_top_level_properties);
            foreach ($unmapped_top_level_properties as $property) {
                $default_map_result = $default_mapper($property, $data[$property]);
                if ($default_map_result !== null && count($default_map_result) === 2) {
                    ArrayPathAccessor::set($result, $default_map_result[0], $default_map_result[1]);
                }
            }
        }

        return $result;
    }
}
