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

namespace empaphy\maphematics\Algebra\Linear\Matrix;

use function array_is_list;
use function count;
use function is_array;
use function is_float;
use function is_int;

/**
 * Finds whether the given values are all matrices.
 *
 * This function will return `true` if all the provided arguments are matrices.
 *
 * @param  mixed  $value
 *   The value being evaluated.
 *
 * @param  mixed  ...$values
 *   Additional values to evaluate.
 *
 * @return bool
 *   Returns `true` if __value__ is a matrix, `false` otherwise.
 *
 * @phpstan-assert-if-true list<list<int|float>> $value
 * @phpstan-assert-if-true list<list<int|float>> $values
 */
function is_matrix(mixed $value, mixed ...$values): bool
{
    foreach ([$value, ...$values] as $matrix) {
        if (! is_array($matrix) || ! array_is_list($matrix)) {
            return false;
        }

        $width = null;

        foreach ($matrix as $row) {
            if (empty($row) || ! (is_array($row) && array_is_list($row))) {
                return false;
            }

            // Check if all rows are the same width.
            if (null === $width) {
                $width = count($row);
            } elseif (count($row) !== $width) {
                return false;
            }

            foreach ($row as $component) {
                if (! (is_float($component) || is_int($component))) {
                    return false;
                }
            }
        }
    }

    return true;
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
 * @param  list<list<int|float>>  $A
 *   A matrix.
 *
 * @param  list<list<int|float>>  $B
 *   Another matrix. The number of rows in _B_ has to match the number of
 *   columns in _A_. In other words, if _A_ is an `m × n` matrix, then _B_
 *   must be `n × p`.
 *
 * @param  list<list<int|float>>  ...$…
 *   Additional matrices. The number of rows of each of these has to match
 *   the number of columns in the previous argument.
 *
 * @return list<list<int|float>>
 *   Returns the product of matrices __A__ and __B__. The resulting matrix
 *   has the same number of rows as __A__, and the same number of columns as
 *   __B__.
 */
function multiply(array $A, array $B, array ...$…): array
{
    assert(
        is_matrix($A),
        __FUNCTION__ . '(): Argument #1 ($A) must be a matrix'
    );

    $R = $A;
    $pos = 1;
    foreach ([$B, ...$…] as $key => $M) {
        $pos++;
        assert(
            is_matrix($M),
            __FUNCTION__ . '(): Argument #%d ($%s) must be a matrix',
        );
        assert(
            count($R[0]) === count($M),
            __FUNCTION__ . "(): The row count of argument #$pos (\$$key) must "
                . 'match the column count of the previous argument',
        );

        // Transpose B (inline for performance).
        $Mᵀ = [];
        foreach ($M as $i => $rowB⟦i⟧) {
            foreach ($rowB⟦i⟧ as $j => $M⟦i⟧⟦j⟧) {
                $Mᵀ[$j][$i] = $M⟦i⟧⟦j⟧;
            }
        }

        $p = count($Mᵀ);
        $L = $R;
        /** @var list<list<int|float>> $R */
        $R = [];

        // Multiply the matrices.
        foreach ($L as $i => $rowA⟦i⟧) {
            $R[$i] = array_fill(0, $p, 0);
            foreach ($Mᵀ as $j => $colM⟦j⟧) {
                foreach ($rowA⟦i⟧ as $k => $K⟦i⟧⟦k⟧) {
                    $R[$i][$j] += $K⟦i⟧⟦k⟧ * $colM⟦j⟧[$k];
                }
            }
        }
    }

    return $R;
}
