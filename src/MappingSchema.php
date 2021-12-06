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

class MappingSchema
{

    /**
     * @var MappingRule[] Array of MappingRule objects
     */
    private array $mapping_rules = [];

    /**
     * @var callable|null Callable used to map all source properties to target properties if they weren't matched by a rule.
     * The resulting array should contain 2 elements: [TARGET_PATH, TARGET_VALUE].
     * If null (Default), the source property won't be added to the target at all. For a simple copy of properties you could use:
     * <br>
     * <code>
     * <pre>
     * static function (string $source_property, mixed $source_value) {
     *     return [$source_property, $source_value];
     * }
     * </pre>
     * </code>
     *
     * The default mapper only applies to top-level properties.
     */
    private $default_mapper = null;

    public function __construct(array $mapping_rules = [], callable $default_mapper = null)
    {
        $this->mapping_rules = $mapping_rules;
        $this->default_mapper = $default_mapper;
    }

    /**
     * @return MappingRule[]
     */
    public function getMappingRules(): array
    {
        return $this->mapping_rules;
    }

    /**
     * Adds a mapping rule to the schema.
     * @param MappingRule $mapping_rule
     * @return self
     */
    public function addMappingRule(MappingRule $mapping_rule): self
    {
        $this->mapping_rules[] = $mapping_rule;
        return $this;
    }

    /**
     * Removes all mapping rules from the schema.
     */
    public function clearMappingRules(): void
    {
        $this->mapping_rules = [];
    }

    /**
     * @param callable $default_mapper
     * @return self
     */
    public function setDefaultMapper(callable $default_mapper): self
    {
        $this->default_mapper = $default_mapper;
        return $this;
    }

    /**
     * @return callable|null
     */
    public function getDefaultMapper(): ?callable
    {
        return $this->default_mapper;
    }
}
