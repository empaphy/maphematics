<?php

/**
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 * @package   Vectors
 *
 * @noinspection GrazieInspection
 * @noinspection NonAsciiCharacters
 */

declare(strict_types=1);

namespace empaphy\maphematics\LinearAlgebra\Vector;

use empaphy\maphematics\Count;
use RangeException;

use function array_fill;
use function array_map;
use function array_walk;
use function assert;
use function count;
use function empaphy\maphematics\LinearAlgebra\Matrix\is_matrix;
use function is_array;
use function is_float;
use function is_int;

/**
 * Add two or more vectors together.
 *
 *       v       w       …         add(v)
 *     ⎡ -1 ⎤   ⎡ 1 ⎤   ⎡ … ⎤   ⎡ -1 + 1 + … ⎤
 *     ⎢ -2 ⎥ + ⎢ 2 ⎥ + ⎢ … ⎥ = ⎢ -2 + 2 + … ⎥
 *     ⎣ -3 ⎦   ⎣ 3 ⎦   ⎣ … ⎦   ⎣ -3 + 3 + … ⎦
 *
 * @param  array<int|float>  $v
 *   A vector.
 *
 * @param  array<int|float>  $w
 *   Another vector.
 *
 * @param  array<int|float>  ...$…
 *   More vectors.
 *
 * @return array<int|float>
 *   Returns the sum of the added vectors.
 */
function add(array $v, array $w, array ...$…): array
{
    assert(
        is_vector($v),
        __FUNCTION__ . '(): Argument #1 ($v) must be a vector'
    );
    assert(
        is_vector($w),
        __FUNCTION__ . '(): Argument #2 ($w) must be a vector'
    );
    assert(
        array_walk($…, is_vector(...)),
        __FUNCTION__ . '(): All variadic arguments must be vectors'
    );

    foreach ($v as $i => $v⟦i⟧) {
        foreach ([$w, ...$…] as $x) {
            $v[$i] += $x[$i];
        }
    }

    return $v;
}

/**
 * Subtracts one or more vectors from the first one.
 *
 *       v       w       …      subtract(v)
 *     ⎡ -1 ⎤   ⎡ 1 ⎤   ⎡ … ⎤   ⎡ -1 - 1 - … ⎤
 *     ⎢ -2 ⎥ - ⎢ 2 ⎥ - ⎢ … ⎥ = ⎢ -2 - 2 - … ⎥
 *     ⎣ -3 ⎦   ⎣ 3 ⎦   ⎣ … ⎦   ⎣ -3 - 3 - … ⎦
 *
 * @param  array  $v
 *   The vector being subtracted from.
 *
 * @param  array  $w
 *   A vector being subtracted from __v__.
 *
 * @param  array  ...$…
 *   Additional vectors being subtracted from __v__.
 *
 * @return array
 *   The difference between __w__ and __v__.
 */
function subtract(array $v, array $w, array ...$…): array
{
    assert(
        is_vector($v),
        __FUNCTION__ . '(): Argument #1 ($v) must be a vector'
    );
    assert(
        is_vector($w),
        __FUNCTION__ . '(): Argument #2 ($w) must be a vector'
    );
    assert(
        array_walk($…, is_vector(...)),
        __FUNCTION__ . '(): All variadic arguments must be vectors'
    );

    foreach ($v as $i => $v⟦i⟧) {
        $v[$i] -= $w[$i];

        foreach ($… as $x) {
            $v[$i] -= $x[$i];
        }
    }

    return $v;
}

/**
 * Multiplies each component of a vector by a scalar.
 *
 *     k   v       k⋅v
 *       ⎡ 1 ⎤   ⎡ 4⋅1 ⎤
 *     4⋅⎢ 2 ⎥ = ⎢ 4⋅2 ⎥
 *       ⎣ 3 ⎦   ⎣ 4⋅3 ⎦
 *
 * @param  array<int|float>  $v
 *   The vector to scale.
 *
 * @param  int|float  $k
 *   Value multiplied with each component of the vector.
 *
 * @return array<int|float>
 *   The scaled vector.
 */
function scale(array $v, int|float $k): array
{
    assert(
        is_vector($v),
        __FUNCTION__ . '(): Argument #1 ($v) must be a vector'
    );

    return array_map(
        static fn (int|float $c): int|float => $c * $k,
        $v,
    );
}

/**
 * Divides each component of a vector by the provided scalar.
 *
 *       v   k     v÷k
 *     ⎡ 1 ⎤     ⎡ 1÷4 ⎤
 *     ⎢ 2 ⎥÷4 = ⎢ 2÷4 ⎥
 *     ⎣ 3 ⎦     ⎣ 3÷4 ⎦
 *
 * @param  array<int|float>  $v
 *   The vector to divide.
 *
 * @param  int|float  $k
 *   Each component of the vector will be divided by this value.
 *
 * @return array<int|float>
 *   The divided vector.
 */
function divide(array $v, int|float $k): array
{
    assert(
        is_vector($v),
        __FUNCTION__ . '(): Argument #1 ($v) must be a vector'
    );

    return array_map(
        static fn (int|float $c): int|float => $c / $k,
        $v,
    );
}

/**
 * Performs a linear transformation using matrix-vector multiplication.
 *
 *          A        x                                              v
 *     ⎛ 1  4  7 ⎞⎡ -1 ⎤     ⎡ 1 ⎤     ⎡ 4 ⎤     ⎡ 7 ⎤   ⎡ -1·1 + -2·4 + -3·7 ⎤
 *     ⎜ 2  5  8 ⎟⎢ -2 ⎥ = -1⎢ 2 ⎥ + -2⎢ 5 ⎥ + -3⎢ 8 ⎥ = ⎢ -1·2 + -2·5 + -3·8 ⎥
 *     ⎝ 3  6  9 ⎠⎣ -3 ⎦     ⎣ 3 ⎦     ⎣ 6 ⎦     ⎣ 9 ⎦   ⎣ -1·3 + -2·6 + -3·9 ⎦
 *
 * @param  array<int|float>  $x
 *   The vector to transform.
 *
 * @param  array<int|float>[]  $A
 *   The transformation matrix to apply to the vector.
 *
 * @return array<int|float>
 *   The new vector after applying the transformation.
 */
function transform(array $x, array $A): array
{
    assert(
        is_vector($x),
        __FUNCTION__ . '(): Argument #1 ($x) must be a vector'
    );
    assert(
        is_matrix($A),
        __FUNCTION__ . '(): Argument #2 ($A) must be a matrix'
    );
    assert(
        count($A[0]) === count($x),
        __FUNCTION__ . '(): The count of argument #1 ($x) must match the'
          . ' column count of argument #2 ($V) but they are ' . count($x)
          . ' and ' . count($A[0]) . ' respectively',
    );

    $v = array_fill(0, count($A), 0);

    foreach ($A as $i => $rowA⟦i⟧) {
        foreach ($rowA⟦i⟧ as $j => $e) {
            $v[$i] += $x[$j] * $e;
        }
    }

    return $v;
}

/**
 * Calculates the dot product of two vectors.
 *
 *        v       w
 *     ⎡ -1 ⎤   ⎡ 1 ⎤
 *     ⎢ -2 ⎥ ⋅ ⎢ 2 ⎥ = -1⋅1 + -2⋅2 + -3⋅3
 *     ⎣ -3 ⎦   ⎣ 3 ⎦
 *
 * @param  array<int|float>  $v
 *   The first vector.
 *
 * @param  array<int|float>  $w
 *   The second vector.
 *
 * @return int|float
 *   The dot product of the vectors __a__ and __b__.
 */
function dot_product(array $v, array $w): int|float
{
    assert(
        is_vector($v),
        __FUNCTION__ . '(): Argument #1 ($v) must be a vector'
    );
    assert(
        is_vector($w),
        __FUNCTION__ . '(): Argument #2 ($w) must be a vector'
    );
    assert(
        count($v) === count($w),
        __FUNCTION__ . '(): Arguments #1 ($v) and #2 ($w) must have the same'
          . ' number of components'
    );

    $v⋅w = 0;
    foreach ($v as $i => $v⟦i⟧) {
        $v⋅w += $v⟦i⟧ * $w[$i];
    }

    return $v⋅w;
}

/**
 * Calculates the Hadamard product for two or more vectors.
 *
 *    a       b       …        a⊙b⊙…
 * ⎡ -1 ⎤   ⎡ 1 ⎤   ⎡ … ⎤   ⎡ -1⋅1⋅… ⎤
 * ⎢ -2 ⎥ ⊙ ⎢ 2 ⎥ ⊙ ⎢ … ⎥ = ⎢ -2⋅2⋅… ⎥
 * ⎣ -3 ⎦   ⎣ 3 ⎦   ⎣ … ⎦   ⎣ -3⋅3⋅… ⎦
 *
 * @param  array<int|float>  $v
 *   The first vector.
 *
 * @param  array<int|float>  $w
 *   The second vector.
 *
 * @param  array<int|float>  ...$…
 *   More vectors.
 *
 * @return array<int|float>
 *   Returns the Hadamard product of the given vectors.
 */
function hadamard_product(array $v, array $w, array ...$…): array
{
    assert(
        is_vector($v),
        __FUNCTION__ . '(): Argument #1 ($v) must be a vector'
    );
    assert(
        is_vector($w),
        __FUNCTION__ . '(): Argument #2 ($w) must be a vector'
    );
    assert(
        array_walk($…, is_vector(...)),
        __FUNCTION__ . '(): All variadic arguments must be vectors'
    );
    assert(
        count($v) === count($w),
        __FUNCTION__ . '(): Arguments #1 ($v) and #2 ($w) must have the same'
          . ' number of components'
    );
    assert(
        Count\have_same_count($v, $w, ...$…),
        __FUNCTION__ . '(): All provided vectors must have the same count',
    );

    foreach ($v as $i => $v⟦i⟧) {
        $v[$i] *= $w[$i];

        foreach ($… as $x) {
            $v[$i] *= $x[$i];
        }
    }

    return $v;
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
        foreach ($value as $c) {
            if (is_int($c) || is_float($c)) {
                continue;
            }

            return false;
        }

        return isset($c);
    }

    return false;
}
