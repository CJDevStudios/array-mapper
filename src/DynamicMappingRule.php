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

class DynamicMappingRule extends MappingRule
{

    /**
     * @var callable
     */
    private $callable;

    public function __construct(callable $callable, string $target_path)
    {
        parent::__construct(null, $target_path);
        $this->callable = $callable;
    }

    public function apply(array $source, array &$target)
    {
        $target_value = call_user_func($this->callable, $source);
        ArrayPathAccessor::set($target, $this->target_path, $target_value);
    }
}
