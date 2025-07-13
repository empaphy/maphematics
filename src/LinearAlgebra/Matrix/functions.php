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

use function abs;
use function array_fill;
use function array_merge;
use function array_slice;
use function assert;
use function count;
use function empaphy\usephul\is_zero;
use function is_array;
use function is_float;
use function is_int;
use function min;

const ε = 0.00000000001;

/**
 * Calculates the inverse for a numeric matrix.
 *
 * @param  array<int|float>[]  $A
 *   The numeric matrix for which you wish to know the inverse.
 *
 * @param  float  $ε
 *   Zero tolerance.
 *
 * @return array<int|float>[]
 *   Returns the inverse of numeric matrix __A__.
 */
function invert(array $A, float $ε = ε): array
{
    assert(is_matrix($A), 'Argument #1 ($A) must be a matrix');
    assert(is_square_matrix($A), 'Argument #1 ($A) must be a square matrix');
    assert(! is_singular($A), 'Argument #1 ($A) is a singular; not invertible');

    $m   = count($A);
    $n   = count($A[0]);
    $│A│ = det($A);

    switch ($m) {
        case 1:
            return [[1 / $A[0][0]]];

        case 2:
            return divide([
                [$A[1][1], -$A[0][1]],
                [-$A[1][0], $A[0][0]],
            ], $│A│);

        default:
            $R = augment($A, identity($m));
            $R = reduced_row_echelon_form($R);
            $A⁻¹ = [];
            for ($i = 0; $i < $n; $i++) {
                $A⁻¹[$i] = array_slice($R[$i], $n);
            }

            return $A⁻¹;
    }
}

/**
 * Creates an identity matrix.
 *
 * @param  int  $n
 *   Size of the identity matrix.
 *
 * @return array<int>[]
 *   Returns a numeric identity matrix.
 */
function identity(int $n): array
{
    assert($n >= 0, 'Argument #1 ($n) must be greater than or equal to 0');

    $R = [];

    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n; $j++) {
            $R[$i][$j] = $i === $j ? 1 : 0;
        }
    }

    return $R;
}

/**
 * Augments a matrix.
 *
 *          A           B          (A|B)
 *     ⎛ 1  4  7 ⎞   ⎛ -1 ⎞   ⎛ 1  4  7  -1 ⎞
 *     ⎜ 2  5  8 ⎟,  ⎜ -2 ⎟,  ⎜ 2  5  8  -2 ⎟
 *     ⎝ 3  6  9 ⎠   ⎝ -3 ⎠   ⎝ 3  6  9  -3 ⎠
 *
 * @template A of array[]
 * @template B of array[]
 *
 * @param  A  $A
 *   A matrix.
 *
 * @param  B  $B
 *   The matrix to augment __A__ with.
 *
 * @return (A|B)
 *   Returns a new, augmented matrix.
 *
 * @noinspection PhpDocSignatureInspection
 */
function augment(array $A, array $B): array
{
    assert(is_matrix($A), 'Argument #1 ($A) must be a matrix');
    assert(is_matrix($B), 'Argument #2 ($B) must be a matrix');
    assert(
        count($A[0]) === count($B),
        __FUNCTION__ . '(): Argument #1 ($A) and argument #2 ($B) must have'
          . ' the same number of rows',
    );

    $⟮A∣B⟯ = [];
    foreach ($A as $i => $A⟦i⟧) {
        $⟮A∣B⟯[$i] = array_merge($A⟦i⟧, $B[$i]);
    }

    return $⟮A∣B⟯;
}

/**
 * Multiplies a matrix by a scalar.
 *
 * @param  array<int|float>[]  $A
 *
 * @param  int|float  $k
 *   The scalar by which to scale __A__
 *
 * @return array<int|float>[]
 */
function scale(array $A, int|float $k): array
{
    foreach ($A as $i => $rowA⟦i⟧) {
        foreach ($rowA⟦i⟧ as $j => $e) {
            $A[$i][$j] *= $k;
        }
    }

    return $A;
}

/**
 * Divides all matrix elements by a scalar.
 *
 * @param  array<int|float>[]  $A
 *
 * @param  int|float  $k
 *   The scalar by which to divide the elements in __A__
 *
 * @return array<int|float>[]
 */
function divide(array $A, int|float $k): array
{
    foreach ($A as $i => $rowA⟦i⟧) {
        foreach ($rowA⟦i⟧ as $j => $e) {
            $A[$i][$j] /= $k;
        }
    }

    return $A;
}

/**
 * Calculate the determinant for a matrix.
 *
 * @param  array<int|float>[]  $A
 *   A numeric matrix.
 *
 * @return int|float
 *   The determinant of __A__.
 */
function det(array $A, float $ε = ε): int|float
{
    assert(is_matrix($A), 'Argument #1 ($A) must be a matrix');
    assert(is_square_matrix($A), 'Argument #1 ($A) must be a square matrix');

    $m = count($A);

    switch ($m) {
        case 1:
            return $A[0][0];

        case 2:
            return $A[0][0] * $A[1][1] - $A[0][1] * $A[1][0];

        case 3:
            return $A[0][0] * (
                $A[1][1] * $A[2][2] - $A[1][2] * $A[2][1]
            ) - $A[0][1] * (
                $A[1][0] * $A[2][2] - $A[1][2] * $A[2][0]
            ) + $A[0][2] * (
                $A[1][0] * $A[2][1] - $A[1][1] * $A[2][0]
            );

        default:
            $ref⟮A⟯ = row_echelon_form($A, $ⁿ, $ε);

            // Det(ref(A))
            $│ref⟮A⟯│ = 1;
            for ($i = 0; $i < $m; $i++) {
                $│ref⟮A⟯│ *= $ref⟮A⟯[$i][$i];
            }

            // │A│ = (-1)ⁿ │ref(A)│
            return (-1) ** $ⁿ * $│ref⟮A⟯│;
    }
}

/**
 * Calculates the reduced row echelon form for a given matrix.
 *
 * @param  array<int|float>[]  $A
 *   A numeric matrix in row echelon form.
 *
 * @return array<int|float>[]
 *   Returns __A__ in reduced low echelon form.
 *
 * @noinspection TypeUnsafeComparisonInspection
 */
function reduced_row_echelon_form(array $A, float $ε = ε): array
{
    assert(is_numeric_matrix($A), 'Argument #1 ($A) must be a numeric matrix');

    $R = row_echelon_form($A);

    $m = count($R);
    $n = count($R[0]);

    $row = 0;
    $col = 0;
    $rref = false;

    while (! $rref) {
        // No non-zero pivot, go to the next column of the same row
        if (is_zero($R[$row][$col], $ε)) {
            $col++;
            if ($row >= $m || $col >= $n) {
                $rref = true;
            }
            continue;
        }

        // Scale pivot to 1
        if ($R[$row][$col] != 1) {
            $divisor = $R[$row][$col];
            foreach ($R[$row] as $l => $e) {
                $R[$row][$l] /= $divisor;
            }
        }

        // Eliminate elements above pivot
        for ($j = $row - 1; $j >= 0; $j--) {
            $factor = $R[$j][$col];
            if (! is_zero($factor, $ε)) {
                foreach ($R[$row] as $l => $e) {
                    $R[$row][$l] += -$factor;
                }
            }
        }

        // Move on to the next row and column
        $row++;
        $col++;

        // If no more rows or columns, rref achieved
        if ($row >= $m || $col >= $n) {
            $rref = true;
        }
    }

    // Floating point adjustment for zero values
    foreach ($R as $i => $R⟦i⟧) {
        foreach ($R⟦i⟧ as $j => $R⟦i⟧⟦j⟧) {
            if (is_zero($R⟦i⟧⟦j⟧, $ε)) {
                $R[$i][$j] = 0;
            }
        }
    }

    return $R;
}

/**
 * Calculate the row echelon form of a matrix.
 *
 * @param  array<int|float>[]  $A
 *   A numeric matrix.
 *
 * @return array
 *   The row echelon form of __A__.
 *
 * @noinspection TypeUnsafeComparisonInspection
 * @noinspection PhpIllegalArrayKeyTypeInspection
 */
function row_echelon_form(array $A, ?int &$swaps = 0, float $ε = ε): array
{
    assert(is_numeric_matrix($A), 'Argument #1 ($A) must be a numeric matrix');

    $swaps ??= 0;

    $m    = count($A);
    $n    = count($A[0]);
    $size = min($m, $n);
    $R    = $A;

    for ($k = 0; $k < $size; $k++) {
        $i_max = $k;
        for ($i = $k; $i < $m; $i++) {
            if (abs($R[$i][$k]) > abs($R[$i_max][$k])) {
                $i_max = $i;
            }
        }

        if (is_zero($R[$i_max][$k], $ε)) {
            // Guassian elimination fails for singular matrices, defer to row
            // reduction.
            $R = null;
            break;
        }

        if ($k != $i_max) {
            [$R[$k], $R[$i_max]] = [$R[$i_max], $R[$k]];
            $swaps++;
        }

        for ($i = $k + 1; $i < $m; $i++) {
            $f = (! is_zero($R[$k][$k], $ε)) ? $R[$i][$k] / $R[$k][$k] : 1;

            for ($j = $k + 1; $j < $n; $j++) {
                $R[$i][$j] = $R[$i][$j] - ($R[$k][$j] * $f);

                if (is_zero($R[$i][$j], $ε)) {
                    $R[$i][$j] = 0;
                }
            }

            $R[$i][$k] = 0;
        }
    }

    // Use row reduction as a fallback method.
    if (null === $R) {
        $R     = $A;
        $row   = 0;
        $col   = 0;
        $swaps = 0;
        $ref   = false;

        while (! $ref) {
            // If the pivot is 0, try to find a non-zero pivot in the column
            // and swap rows
            if (is_zero($R[$row][$col], $ε)) {
                for ($j = $row + 1; $j < $m; $j++) {
                    if (! is_zero($R[$j][$col], $ε)) {
                        $R = [...$R, $row => $R[$j], $j => $R[$row]];
                        $swaps++;
                        break;
                    }
                }
            }

            // No non-zero pivot, go to the next column of the same row
            if (is_zero($R[$row][$col], $ε)) {
                $col++;
                if ($row >= $m || $col >= $n) {
                    $ref = true;
                }
                continue;
            }

            // Scale pivot to 1
            $divisor = $R[$row][$col];
            foreach ($R[$row] as $l => $e) {
                $R[$row][$l] /= $divisor;
            }

            // Eliminate elements below pivot
            for ($j = $row + 1; $j < $m; $j++) {
                $factor = $R[$j][$col];

                if (! is_zero($factor, $ε)) {
                    foreach ($R[$row] as $l => $e) {
                        $R[$j][$l] += $R[$row][$l] * -$factor;
                    }

                    for ($k = 0; $k < $n; $k++) {
                        if (is_zero($R[$j][$k], $ε)) {
                            $R[$j][$k] = 0;
                        }
                    }
                }
            }

            // Move on to the next row and column
            $row++;
            $col++;

            // If no more rows or columns, ref achieved
            if ($row >= $m || $col >= $n) {
                $ref = true;
            }
        }

        // Floating point adjustment for zero values
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if (is_zero($R[$i][$j], $ε)) {
                    $R[$i][$j] = 0;
                }
            }
        }
    }

    return $R;
}

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
 * Finds whether the given value is a numeric matrix.
 *
 * @param  mixed  $value
 *   The value to check.
 *
 * @return ($value is array<int|float>[] ? true : false)
 *
 * @phpstan-assert-if-true array<int|float>[] $value
 */
function is_numeric_matrix(mixed $value): bool
{
    // TODO check whether matrix is numeric.

    return is_matrix($value);
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

/**
 * Finds whether a given matrix is invertible.
 *
 * @param  array<int|float>[]  $A
 *   A numeric matrix.
 *
 * @return bool
 *   Returns `true` if numeric matrix __A__ is invertible, `false` otherwise.
 */
function is_invertible(array $A, float $ε = ε): bool
{
    return ! is_singular($A, $ε);
}

/**
 * Finds whether a given numeric matrix is singular.
 *
 * @param  array<int|float>[]  $A
 *   A numeric matrix.
 *
 * @return bool
 *   Returns `true` if numeric matrix __A__ is singular, `false` otherwise.
 */
function is_singular(array $A, float $ε = ε): bool
{
    return ! is_zero(det($A), $ε);
}
