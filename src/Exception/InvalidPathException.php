<?php
/*
 * This file is part of array-mapper.
 *
 * (c) CJ Development Studios <contact@cjdevstudios.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CJDevStudios\ArrayMapper\Exception;

class InvalidPathException extends MappingException
{

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct(sprintf('The array path "%s" is invalid or does not exist.', $path));
    }
}
