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

use function array_fill;
use function array_map;
use function count;

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
    $result  = array_fill(0, $length, 0);

    for ($i = 0; $i < $length; $i++) {
        foreach ($vectors as $value) {
            $result[$i] += $value[$i];
        }
    }

    return $result;
}

/**
 * Multiplies each component of a vector with the provided scalar.
 *
 *       ⎡ x ⎤   ⎡ s⋅x ⎤
 *     s·⎢ y ⎥ = ⎢ s⋅y ⎥
 *       ⎣ z ⎦   ⎣ s⋅z ⎦
 *
 * @param  array<int|float>  $vector
 * @param  int|float         $scalar  Value multiplied with each component of
 *                                    the vector.
 * @return array<int|float>
 */
function scale(array $vector, int | float $scalar): array
{
    return array_map(
        static fn(int | float $c): int | float => $c * $scalar,
        $vector,
    );
}

/**
 * Divides each component of a vector by the provided scalar.
 *
 *     ⎡ x ⎤     ⎡ x÷s ⎤
 *     ⎢ y ⎥÷s = ⎢ y÷s ⎥
 *     ⎣ z ⎦     ⎣ z÷s ⎦
 *
 * @param  array<int|float>  $vector
 * @param  int|float         $scalar  Value multiplied with each component of
 *                                    the vector.
 * @return array<int|float>
 */
function scale_divide(array $vector, int | float $scalar): array
{
    return array_map(
        static fn(int | float $c): int | float => $c / $scalar,
        $vector,
    );
}

/**
 * Performs a linear transformation on the given vector using a matrix.
 *
 * ⎡ x ⎤   ⎡ a  d  g ⎤    ⎡ a ⎤    ⎡ d ⎤    ⎡ g ⎤   ⎡ ax + dy + gz ⎤
 * ⎢ y ⎥ · ⎢ b  e  h ⎥ = x⎢ b ⎥ + y⎢ e ⎥ + z⎢ h ⎥ = ⎢ bx + ey + hz ⎥
 * ⎣ z ⎦   ⎣ c  f  i ⎦    ⎣ c ⎦    ⎣ f ⎦    ⎣ i ⎦   ⎣ cx + fy + iz ⎦
 *
 * @param  array<int|float>    $vector
 * @param  array<int|float>[]  $matrix
 * @return array<int|float>
 */
function transform(array $vector, array $matrix): array
{
    if (count($matrix[0]) !== count($vector)) {
        throw new \RangeException('Vector and matrix column count must match.');
    }

    $v = array_fill(0, count($matrix), 0);

    foreach ($matrix as $i => $row) {
        foreach ($row as $j => $component) {
            $v[$i] += $vector[$j] * $component;
        }
    }

    return $v;
}

/**
 * ⎡ a ⎤   ⎡ x ⎤⎡ .. ⎤   ⎡ a • x • .. ⎤
 * ⎢ b ⎥ x ⎢ y ⎥⎢ .. ⎥ = ⎢ b • y • .. ⎥
 * ⎣ c ⎦   ⎣ z ⎦⎣ .. ⎦   ⎣ c • z • .. ⎦
 *
 * @param  array<int|float>  ...$vector
 * @param  array<int|float>  ...$vectors
 * @return array<int|float>
 */
function multiply(array $vector, array ...$vectors): array
{
    $vectors = [$vector, ...$vectors];
    $length  = array\check_lengths(...$vectors);
    $result  = array_fill(0, $length, 1);

    for ($i = 0; $i < $length; $i++) {
        foreach ($vectors as $value) {
            $result[$i] *= $value[$i];
        }
    }

    return $result;
}
