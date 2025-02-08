<?php

/**
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 * @package   Vectors
 */

declare(strict_types=1);

namespace empaphy\maphematics\vector;

use empaphy\maphematics\array;

/**
 * Add two or more vectors together.
 *
 *     ⎡ a ⎤   ⎡ x ⎤   ⎡ a + x ⎤
 *     ⎢ b ⎥ + ⎢ y ⎥ = ⎢ b + y ⎥
 *     ⎣ c ⎦   ⎣ z ⎦   ⎣ c + z ⎦
 *
 * @param  array<int|float>  $vector
 * @param  array<int|float>  ...$vectors
 * @return array<int|float>
 */
function add(array $vector, array ...$vectors): array
{
    $vectors = [$vector, ...$vectors];
    $length  = array\check_lengths(...$vectors);
    $count   = \count($vectors);
    $result  = \array_fill(0, $length, 0);

    for ($i = 0; $i < $length; $i++) {
        for ($j = 0; $j < $count; $j++) {
            $result[$i] += $vectors[$j][$i];
        }
    }

    return $result;
}
