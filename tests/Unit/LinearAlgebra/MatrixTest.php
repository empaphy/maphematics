<?php

/**
 * Matrix Tests.
 *
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 */

declare(strict_types=1);

use empaphy\maphematics\LinearAlgebra\Matrix;

describe('is_matrix()', function () {
    test('finds whether value is a matrix', function (bool $expected, mixed $value) {
        $isMatrix = Matrix\is_matrix($value);

        if ($expected) {
            expect($isMatrix)->toBeTrue();
        } else {
            expect($isMatrix)->toBeFalse();
        }
    })->with([
        '1x1 matrix' => [true, [
            [3],
        ]],
        '2x2 matrix' => [true, [
            [ 3  ,  5.7 ],
            [11.0, 17.19],
        ]],
        '3x2 matrix' => [true, [
            [ 3,  5,  7],
            [11, 13, 17],
        ]],
        '2x3 matrix' => [true, [
            [ 3,  5],
            [ 7, 11],
            [13, 17],
        ]],
        'empty matrix' => [false, [
            [],
        ]],
        'not square' => [false, [
            [ 3,  5,  7],
            [11, 13    ],
            [17, 19, 23],
        ]],
        'triangular' => [false, [
            [ 3,  5,  7],
            [11, 13    ],
            [17        ],
        ]],
        'with null' => [false, [
            [ 3,  5,  7],
            [11, 13, null],
            [17, 19, 23],
        ]],
        'with string' => [false, [
            [ 3,  5,  7],
            [11, 13, 'foo'],
            [17, 19, 23],
        ]],
        'vector' => [false, [3, 5, 7]],
    ]);
});

describe('transpose()', function () {
    test('transposes', function (array $expected, array $matrix) {
        $transposed = Matrix\transpose($matrix);

        expect($transposed)->toBe($expected);
    })->with([
        '3x3 matrix' => [[
            [ 3, 11, 19],
            [ 5, 13, 23],
            [ 7, 17, 27],
        ], [
            [ 3,  5,  7],
            [11, 13, 17],
            [19, 23, 27],
        ]],
        '3x2 matrix' => [[
            [ 3, 11],
            [ 5, 13],
            [ 7, 17],
        ], [
            [ 3,  5,  7],
            [11, 13, 17],
        ]],
        '2x3 matrix' => [[
            [ 3,  5,  7],
            [11, 13, 17],
        ], [
            [ 3, 11],
            [ 5, 13],
            [ 7, 17],
        ]],
        '1x1 matrix' => [[
            [3],
        ], [
            [3],
        ]],
    ]);
});
