<?php

/**
 * Matrix Tests.
 *
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 *
 * @noinspection NonAsciiCharacters
 * @noinspection PhpIdempotentOperationInspection
 * @noinspection StaticClosureCanBeUsedInspection
 */

declare(strict_types=1);

use empaphy\maphematics\LinearAlgebra\Matrix;
describe('multiply()', function () {
    test('multiplies', function (array $expected, array $A, array $B) {
        $R = Matrix\multiply($A, $B);
        expect($R)->toBe($expected);
    })->with([
        '2×2 ⋅ 2×2' => [
            'expected' => [
                [ 2 , 0 ],
                [ 1 ,-2 ],
            ],
            'A' => [
                [ 0 , 2 ],
                [ 1 , 0 ],
            ],
            'B' => [
                [ 1 ,-2 ],
                [ 1 , 0 ],
            ],
        ],
        '2×3 ⋅ 3×2' => [
            'expected' => [
                [ 3 , 2340 ],
                [ 0 , 1000 ],
            ],
            'A' => [
                [ 2 , 3 , 4 ],
                [ 1 , 0 , 0 ],
            ],
            'B' => [
                [ 0 , 1000 ],
                [ 1 ,  100 ],
                [ 0 ,   10 ],
            ],
        ],
        '3×2 ⋅ 2×3 matrix' => [
            'expected' => [
                [ -1*1 + -2*2 , -1*3 + -2*4 , -1*5 + -2*6 ],
                [ -3*1 + -4*2 , -3*3 + -4*4 , -3*5 + -4*6 ],
                [ -5*1 + -6*2 , -5*3 + -6*4 , -5*5 + -6*6 ],
            ],
            'A' => [
                [ -1   , -2 ],
                [ -3   , -4 ],
                [ -5   , -6 ],
            ],
            'B' => [
                [      1      ,    3        ,    5        ],
                [           2 ,           4 ,           6 ],
            ],
        ],
        '3×3 ⋅ 3×3' => [
            'expected' => [
                [ (-3.109) * 41.71                  +  11.131  * 37.73                  +  19.149  * -31.79                  ,
                  (-3.109) *        -47.83          +  11.131  *         43.89          +  19.149  *          53.97          ,
                  (-3.109) *                 59.107 +  11.131  *                -67.103 +  19.149  *                  61.101 ],
                [  13.113  * 41.71                  +  23.137  * 37.73                  + (-5.151) * -31.79                  ,
                   13.113  *        -47.83          +  23.137  *         43.89          + (-5.151) *          53.97          ,
                   13.113  *                 59.107 +  23.137  *                -67.103 + (-5.151) *                  61.101 ],
                [  29.127  * 41.71                  + (-7.139) * 37.73                  +  17.157  * -31.79                  ,
                   29.127  *        -47.83          + (-7.139) *         43.89          +  17.157  *          53.97          ,
                   29.127  *                 59.107 + (-7.139) *                -67.103 +  17.157  *                  61.101 ],
            ],
            'A' => [
                [  -3.109  ,                           11.131  ,                           19.149                            ],
                [  13.113  ,                           23.137  ,                           -5.151                            ],
                [  29.127  ,                           -7.139  ,                           17.157                            ],
            ],
            'B' => [
                [            41.71 ,-47.83 , 59.107                                                                          ],
                [                                                37.73 , 43.89 ,-67.103                                      ],
                [                                                                                    -31.79 , 53.97 , 61.101 ],
            ],
        ],
    ]);
});

describe('transpose()', function () {
    test('transposes', function (array $expected, array $matrix) {
        $Aᵀ = Matrix\transpose($matrix);

        expect($Aᵀ)->toBe($expected);
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
