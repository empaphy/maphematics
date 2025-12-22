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
