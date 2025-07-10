<?php

/**
 * @noinspection NonAsciiCharacters
 */

declare(strict_types=1);

namespace empaphy\maphematics\LinearAlgebra\Matrix;

use function count;
use function is_array;
use function is_float;
use function is_int;

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
 * Transpose the given matrix.
 *
 * @param  array<int|float>[]  $matrix
 *   The matrix to transpose.
 *
 * @return array<int|float>[]
 *   Returns a new matrix with the transposed values.
 */
function transpose(array $matrix): array
{
    $Aᵀ = [];

    foreach ($matrix as $i => $row) {
        foreach ($row as $j => $component) {
            $Aᵀ[$j][$i] = $component;
        }
    }

    return $Aᵀ;
}
