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

class ArrayPathAccessor
{

    public const DELIMITER = '.';

    /**
     * @param array $array
     * @param string $path
     * @return mixed
     * @throws InvalidPathException
     */
    public static function get(array $array, string $path)
    {
        if (empty($path)) {
            throw new InvalidPathException($path);
        }
        $path_array = explode(self::DELIMITER, $path);
        $current = $array;
        foreach ($path_array as $key) {
            if (!isset($current[$key])) {
                throw new InvalidPathException($path);
            }
            $current = $current[$key];
        }
        return $current;
    }

    /**
     * @param array $array
     * @param string $path
     * @param $value
     * @throws InvalidPathException
     */
    public static function set(array &$array, string $path, $value)
    {
        if (empty($path)) {
            throw new InvalidPathException($path);
        }
        $path = explode(self::DELIMITER, $path);
        $current = &$array;
        foreach ($path as $key) {
            if (!isset($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
        $current = $value;
    }
}
