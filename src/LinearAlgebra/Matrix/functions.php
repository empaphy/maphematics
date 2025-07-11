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
 * Multiply two matrices.
 *
 *    A       B
 * ⎡ a  c ⎤⎡ w  y ⎤   ⎡ wa + xc   ya + zc ⎤
 * ⎣ b  d ⎦⎣ x  z ⎦ = ⎣ wb + xd   yb + zd ⎦
 */
function multiply(array $A, array $B): array
{
    $R = [];
    $Bᵀ = [];
    
    // Transpose B inline for performance.
    foreach ($B as $i => $rowB⟦i⟧) {
        foreach ($rowB⟦i⟧ as $j => $B⟦i⟧⟦j⟧) {
            $Bᵀ[$j][$i] = $B⟦i⟧⟦j⟧;
        }
    }

    // Multiply the matrixes.
    foreach ($A as $i => $rowA⟦i⟧) {
        $R[$i] = array_fill(0, count($B[0]), 0);
        foreach ($Bᵀ as $j => $colB⟦j⟧) {
            foreach ($rowA⟦i⟧ as $k => $A⟦i⟧⟦k⟧) {
                $R[$i][$j] += $A⟦i⟧⟦k⟧ * $colB⟦j⟧[$k];
            }
        }
    }

    return $R;
}

/**
 * Transpose the given matrix.
 *
 * @param  array<int|float>[]  $A
 *   The matrix to transpose.
 *
 * @return array<int|float>[]
 *   Returns a new matrix with the transposed values.
 */
function transpose(array $A): array
{
    $Aᵀ = [];

    foreach ($A as $i => $rowA⟦i⟧) {
        foreach ($rowA⟦i⟧ as $j => $A⟦i⟧⟦j⟧) {
            $Aᵀ[$j][$i] = $A⟦i⟧⟦j⟧;
        }
    }

    return $Aᵀ;
}
