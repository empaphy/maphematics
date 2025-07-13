<?php

/**
 * A matrix is a two-dimensional array of numbers with elements arranged in
 * rows and columns.
 *
 * In this project, matrices are written using parentheses, while vectors are
 * written using square brackets. For example,
 *
 *     ⎛ 1  4 ⎞
 *     ⎜ 2  5 ⎟
 *     ⎝ 3  6 ⎠
 *
 * denotes a matrix with three rows and two columns, also known as a "3 × 2
 * matrix". The symbols used to define matrix size in are `m × n`, where the
 * symbols represent the rows and columns respectively.
 *
 * The numbers in a matrix are called its _elements_.
 *
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 * @package   Array
 *
 * @noinspection NonAsciiCharacters
 */

declare(strict_types=1);

namespace empaphy\maphematics\LinearAlgebra\Matrix;

use function array_fill;
use function assert;
use function count;
use function is_array;
use function is_float;
use function is_int;

/**
 * Multiply two matrices.
 *
 * The number of columns in __A__ must be equal to the number of rows in __B__.
 *
 *            A          B
 *     ⎛ -1  -2  -3 ⎞⎛ 1  4 ⎞   ⎛ -1⋅1 + -2⋅2 + -3⋅3   -1⋅4 + -2⋅5 + -3⋅6 ⎞
 *     ⎝ -4  -5  -6 ⎠⎜ 2  5 ⎟ = ⎝ -4⋅1 + -5⋅2 + -6⋅3   -4⋅4 + -5⋅5 + -6⋅6 ⎠
 *                   ⎝ 3  6 ⎠
 *
 *          A         B
 *     ⎛ -1  -2 ⎞⎛ 1  3  5 ⎞   ⎛ -1⋅1 + -2⋅2   -1⋅3 + -2⋅4   -1⋅5 + -2⋅6 ⎞
 *     ⎜ -3  -4 ⎟⎝ 2  4  6 ⎠ = ⎜ -3⋅1 + -4⋅2   -3⋅3 + -4⋅4   -3⋅5 + -4⋅6 ⎟
 *     ⎝ -5  -6 ⎠              ⎝ -5⋅1 + -6⋅2   -5⋅3 + -6⋅4   -5⋅5 + -6⋅6 ⎠
 *
 * @param  array<int|float>[]  $A
 *   A matrix.
 *
 * @param  array<int|float>[]  $B
 *   Another matrix. The number of rows in _B_ has to match the number of
 *   columns in _A_. In other words, if _A_ is an `m × n` matrix, then _B_
 *   must be `n × p`.
 *
 * @return array<int|float>[]
 *   Returns the product of matrices __A__ and __B__. The resulting matrix
 *   has the same number of rows as __A__, and the same number of columns as
 *   __B__.
 */
function multiply(array $A, array $B): array
{
    assert(
        is_matrix($A),
        __FUNCTION__ . '(): Argument #1 ($A) must be a matrix'
    );
    assert(
        is_matrix($B),
        __FUNCTION__ . '(): Argument #2 ($B) must be a matrix'
    );
    assert(
        count($A[0]) === count($B),
        __FUNCTION__ . '(): Row count of $B must match column count of $A',
    );

    $R = [];
    $Bᵀ = [];

    // Transpose B (inline for performance).
    foreach ($B as $i => $rowB⟦i⟧) {
        foreach ($rowB⟦i⟧ as $j => $B⟦i⟧⟦j⟧) {
            $Bᵀ[$j][$i] = $B⟦i⟧⟦j⟧;
        }
    }

    $p = count($Bᵀ);

    // Multiply the matrices.
    foreach ($A as $i => $rowA⟦i⟧) {
        $R[$i] = array_fill(0, $p, 0);
        foreach ($Bᵀ as $j => $colB⟦j⟧) {
            foreach ($rowA⟦i⟧ as $k => $A⟦i⟧⟦k⟧) {
                $R[$i][$j] += $A⟦i⟧⟦k⟧ * $colB⟦j⟧[$k];
            }
        }
    }

    return $R;
}

/**
 * Transpose a matrix.
 *
 *     ⎛ 1  4 ⎞ᵀ  ⎛ 1 2 3 ⎞
 *     ⎜ 2  5 ⎟ = ⎝ 4 5 6 ⎠
 *     ⎝ 3  6 ⎠
 *
 * @param  array<int|float>[]  $A
 *   The matrix to transpose.
 *
 * @return array<int|float>[]
 *   Returns a new matrix with the transposed values.
 */
function transpose(array $A): array
{
    assert(
        is_matrix($A),
        __FUNCTION__ . '(): Argument #1 ($A) must be a matrix'
    );

    $Aᵀ = [];

    foreach ($A as $i => $rowA⟦i⟧) {
        foreach ($rowA⟦i⟧ as $j => $A⟦i⟧⟦j⟧) {
            $Aᵀ[$j][$i] = $A⟦i⟧⟦j⟧;
        }
    }

    return $Aᵀ;
}

/**
 * Finds whether the given value is a matrix.
 *
 * @param  mixed  $value
 *   The value being evaluated.
 *
 * @return bool
 *   Returns `true` if __value__ is a matrix, `false` otherwise.
 *
 * @phpstan-assert-if-true array<array<int|float>> $value
 */
function is_matrix(mixed $value): bool
{
    $width = null;

    if (is_array($value)) {
        foreach ($value as $row) {
            if (is_array($row)) {
                // Check if all rows are the same width.
                if (null === $width) {
                    $width = count($row);
                } elseif (count($row) !== $width) {
                    return false;
                }

                foreach ($row as $c) {
                    if (is_float($c) || is_int($c)) {
                        continue;
                    }

                    return false;
                }
            }
        }

        return isset($c);
    }

    return false;
}

/**
 * Checks whether a given matrix is square.
 *
 * @param  array<int|float>[]  $A
 *   A matrix.
 *
 * @return bool
 *   Returns `true` if the given matrix is square, `false` otherwise.
 */
function is_square_matrix(array $A): bool
{
    assert(is_matrix($A), 'Argument #1 ($A) must be a matrix');

    $m = count($A);

    foreach ($A as $row) {
        if (count($row) !== $m) {
            return false;
        }
    }

    return true;
}
