<?php

/**
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 * @package   Arrays
 */

declare(strict_types=1);

namespace empaphy\maphematics\array;

/**
 * Check that two or more arrays are all the same length.
 *
 * @param  array<mixed>  $array
 * @param  array<mixed>  ...$arrays
 * @return int
 *
 * @throws \RangeException
 */
function check_lengths(array $array, array ...$arrays): int
{
    $n = \count($array);

    foreach ($arrays as $a) {
        if (\count($a) !== $n) {
            throw new \RangeException('Lengths of arrays are not equal');
        }
    }

    return $n;
}
