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

use CJDevStudios\ArrayMapper\Exception\InvalidPathException;
use CJDevStudios\ArrayMapper\Exception\MappingException;

/**
 * A rule to map one array property to another.
 * @since 1.0.0
 */
class MappingRule
{

    /**
     * @var string Path to the property in the source array.
     */
    private $source_path;

    /**
     * @var string Path to the property in the target array.
     */
    private $target_path;

    /**
     * @var callable Callable to be used to map the source value to the target value.
     * If not set, the source value will be used as the target value.
     */
    private $transform;

    /**
     * @var mixed The default value to use if the source property is missing.
     * This has no effect if {@link $ignore_missing} or {@link $no_default} are true.
     */
    private $default_value = null;

    /**
     * @var bool If true, no default value will be used if the source property is missing.
     */
    private $no_default = true;

    /**
     * @var bool If true and the target property is missing after the transform, a {@link MappingException} will be thrown.
     */
    private $required = true;

    /**
     * @var bool If true and the source property is missing, the mapping will be skipped without throwing an exception.
     */
    private $ignore_missing = false;

    /**
     * @param string $source_path
     * @param string $target_path
     * @param mixed|null $default_value
     * @param bool $no_default
     * @param bool $required
     * @param bool $ignore_missing
     */
    public function __construct(string $source_path, string $target_path, $default_value = null, bool $no_default = true, bool $required = true, bool $ignore_missing = false)
    {
        $this->source_path = $source_path;
        $this->target_path = $target_path;
        $this->default_value = $default_value;
        $this->no_default = $no_default;
        $this->required = $required;
        $this->ignore_missing = $ignore_missing;
    }

    public function getSourcePath()
    {
        return $this->source_path;
    }

    public function getTargetPath()
    {
        return $this->target_path;
    }

    public function getTransform(): ?callable
    {
        return $this->transform;
    }

    /**
     * @param callable|null $transform
     * @return self
     */
    public function setTransform(?callable $transform): self
    {
        $this->transform = $transform;
        return $this;
    }

    public function setDefault($default_value, bool $no_default = false): self
    {
        $this->default_value = $default_value;
        $this->no_default = $no_default;
        return $this;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;
        return $this;
    }

    public function setIgnoreMissing(bool $ignore_missing): self
    {
        $this->ignore_missing = $ignore_missing;
        return $this;
    }

    /**
     * @throws InvalidPathException Thrown if the target path is empty.
     * @throws MappingException
     */
    public function apply(array $source, array &$target)
    {
        try {
            $source_value = ArrayPathAccessor::get($source, $this->source_path);
        } catch (InvalidPathException $e) {
            if ($this->ignore_missing) {
                return;
            }
            if (!$this->no_default) {
                $source_value = $this->default_value;
            }
            if ($this->required && !isset($source_value)) {
                throw new MappingException("Required source property '{$this->source_path}' is missing and no default value is specified", 0, $e);
            }

            // No default but not required, so just return
            return;
        }
        $target_value = $this->transform ? call_user_func($this->transform, $source_value) : $source_value;
        ArrayPathAccessor::set($target, $this->target_path, $target_value);
    }
}
