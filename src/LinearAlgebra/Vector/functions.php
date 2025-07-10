<?php

/**
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 * @package   Vectors
 */

declare(strict_types=1);

namespace empaphy\maphematics\LinearAlgebra\Vector;

use empaphy\maphematics\Array;

use RangeException;

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
    $length  = Array\check_lengths(...$vectors);
    $result  = array_fill(0, $length, 0);

    for ($i = 0; $i < $length; $i++) {
        foreach ($vectors as $value) {
            $result[$i] += $value[$i];
        }
    }

    return $result;
}

/**
 * Divides each component of a vector by the provided scalar.
 *
 *     ⎡ x ⎤     ⎡ x÷s ⎤
 *     ⎢ y ⎥÷s = ⎢ y÷s ⎥
 *     ⎣ z ⎦     ⎣ z÷s ⎦
 *
 * @param  array<int|float>  $vector
 *   The vector to divide.
 *
 * @param  int|float  $divisor
 *   Each component of the vector will be divided by this value.
 *
 * @return array<int|float>
 *   The divided vector.
 */
function divide(array $vector, int|float $divisor): array
{
    return array_map(
        static fn (int|float $c): int|float => $c / $divisor,
        $vector,
    );
}

/**
 * Calculates the dot product of two vectors.
 *
 *     ⎡ a ⎤   ⎡ x ⎤
 *     ⎢ b ⎥ · ⎢ y ⎥ = ax + by + cz
 *     ⎣ c ⎦   ⎣ z ⎦
 *
 * @param  array  $vector
 *   The first vector.
 *
 * @param  array  $other
 *   The second vector.
 *
 * @return int|float
 *   The dot product of the given vectors.
 */
function dot(array $vector, array $other): int|float
{
    if (count($vector) === count($other)) {
        $result = 0;
        foreach ($vector as $i => $v) {
            $result += $v * $other[$i];
        }

        return $result;
    }

    throw new RangeException('Vector dimensions do not match');
}

/**
 * Finds whether the given value is a vector.
 *
 * @param  mixed  $value
 *   The value being evaluated.
 *
 * @return bool
 *   Returns `true` if __value__ is a vector, `false` otherwise.
 *
 * @phpstan-assert-if-true array<int|float> $value
 */
function is_vector(mixed $value): bool
{
    if (is_array($value)) {
        foreach ($value as $v) {
            if (is_int($v) || is_float($v)) {
                continue;
            }

            return false;
        }

        return isset($v);
    }

    return false;
}

/**
 * Multiplies each component of a vector with the provided scalar.
 *
 *       ⎡ x ⎤   ⎡ s⋅x ⎤
 *     s·⎢ y ⎥ = ⎢ s⋅y ⎥
 *       ⎣ z ⎦   ⎣ s⋅z ⎦
 *
 * @param  array<int|float>  $vector
 *   The vector to scale.
 *
 * @param  int|float  $scalar
 *   Value multiplied with each component of the vector.
 *
 * @return array<int|float>
 *   The scaled vector.
 */
function scale(array $vector, int|float $scalar): array
{
    return array_map(
        static fn (int|float $c): int|float => $c * $scalar,
        $vector,
    );
}

/**
 * Performs a linear transformation on the given vector using a given matrix.
 *
 * ⎡ x ⎤   ⎡ a  d  g ⎤    ⎡ a ⎤    ⎡ d ⎤    ⎡ g ⎤   ⎡ xa + yd + zg ⎤
 * ⎢ y ⎥ · ⎢ b  e  h ⎥ = x⎢ b ⎥ + y⎢ e ⎥ + z⎢ h ⎥ = ⎢ xb + ye + zh ⎥
 * ⎣ z ⎦   ⎣ c  f  i ⎦    ⎣ c ⎦    ⎣ f ⎦    ⎣ i ⎦   ⎣ xc + yf + zi ⎦
 *
 * @param  array<int|float>  $vector
 *   The vector to transform.
 *
 * @param  array<int|float>[]  $matrix
 *   The transformation matrix to apply to the vector.
 *
 * @return array<int|float>
 *   The new vector after applying the transformation.
 */
function transform(array $vector, array $matrix): array
{
    if (count($matrix[0]) !== count($vector)) {
        throw new RangeException('Vector and matrix column count must match.');
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
 * ⎡ a ⎤   ⎡ x ⎤   ⎡ … ⎤   ⎡ a • x • .. ⎤
 * ⎢ b ⎥ ⊙ ⎢ y ⎥ ⊙ ⎢ … ⎥ = ⎢ b • y • .. ⎥
 * ⎣ c ⎦   ⎣ z ⎦   ⎣ … ⎦   ⎣ c • z • .. ⎦
 *
 * @param  array<int|float>  ...$vector
 * @param  array<int|float>  ...$vectors
 * @return array<int|float>
 */
function hadamard(array $vector, array ...$vectors): array
{
    $vectors = [$vector, ...$vectors];
    $length  = Array\check_lengths(...$vectors);
    $result  = array_fill(0, $length, 1);

    for ($i = 0; $i < $length; $i++) {
        foreach ($vectors as $value) {
            $result[$i] *= $value[$i];
        }
    }

    return $result;
}
