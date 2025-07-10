<?php

/**
* Vector tests.
*
* @author    Alwin Garside <alwin@garsi.de>
* @copyright 2025 The Empaphy Project
* @license   MIT
*
* @noinspection StaticClosureCanBeUsedInspection
*/

use empaphy\maphematics\LinearAlgebra\Vector;

describe('add()', function () {
    test('adds', function ($expected, ...$vectors) {
        $value = Vector\add(...$vectors);
        expect($value)->toBe($expected);
    })->with([
        'two vectors' => [
            [ 1 + 3 , 2 + (-1) ],
            [ 1     , 2        ],
            [     3 ,      -1  ],
        ],
        'three vectors' => [
            [ 1 + 3 + 7 , 2 + 5 + 11 ],
            [ 1         , 2          ],
            [     3     ,     5      ],
            [         7 ,         11 ],
        ],
        '3d vectors' => [
            [ 1 + 5 + 13 , 2 + 7 + 17 , 3 + 11 + 23 ],
            [ 1          , 2          , 3           ],
            [     5      ,     7      ,     11      ],
            [         13 ,         17 ,          23 ],
        ],
        'vectors with floats' => [
            [ 1.2 + 3.5 , -7.11 + 17.23 ],
            [ 1.2       , -7.11         ],
            [       3.5 ,         17.23 ],
        ],
    ]);
});

describe('divide()', function () {
    test('divides', function ($expected, $vector, $divisor) {
        $value = Vector\divide($vector, $divisor);
        expect($value)->toBe($expected);
    })->with([
        'simple divisor' => [
            [ 3 / 2, -5 / 2],
            [ 3,     -5    ],
            2,
        ],
        '3d vector' => [
            [ 3 / 11 , -5 / 11 , 7 / 11 ],
            [ 3      , -5      , 7      ],
            11,
        ],
    ]);
});

describe('dot()', function () {
    test('calculates dot product', function ($expected, $vector, $other) {
        $value = Vector\dot($vector, $other);
        expect($value)->toBe($expected);
    })->with([
        '3D vector' => [
              5 * -13 + -7 * 17 + 11 * 19 ,
            [ 5       , -7      , 11      ],
            [     -13 ,      17 ,      19 ],
        ],
        '2D vector' => [
              5.11 * -13.17 + -19.23 * 27.29 ,
            [ 5.11          , -19.23         ],
            [        -13.17 ,          27.29 ],
        ],
        '1D vector' => [
              5.11 * -13.17 ,
            [ 5.11          ],
            [        -13.17 ],
        ],
    ]);
});

describe('scale()', function () {
    test('scales', function ($expected, $vector, $scalar) {
        $value = Vector\scale($vector, $scalar);
        expect($value)->toBe($expected);
    })->with([
        'simple scalar' => [
            [ 2 * 3 , 2 * -5 ],
            [     3 ,     -5 ],
            2,
        ],
        '3d vector' => [
            [ 3 * 11 , -5 * 11 , 7 * 11 ],
            [ 3      , -5      , 7      ],
            11,
        ],
    ]);
});

describe('is_vector()', function () {
    test('finds whether value is a vector', function ($expected, $vector) {
        $value = Vector\is_vector($vector);
        expect($value)->toBe($expected);
    })->with([
        [true, [1  , 2,  3]],
        [true, [0.1, 2, -3]],
        [true, [1]],
        [false, []],
        [false, [1, 2, 'foo']],
        [false, [1, 2, '3']],
        [false, [1, 2, '']],
        [false, [1, 2, null]],
    ]);
});

describe('transform()', function () {
    test('transforms', function ($expected, $vector, $matrix) {
        $value = Vector\transform($vector, $matrix);
        expect($value)->toBe($expected);
    })->with([
        '2x2 matrix' => [
            [
                -1 * -3 + 2 * 5 ,
                -1 *  7 + 2 * 0 ,
            ],
            [   -1,       2     ],
            [
                [    -3 ,     5 ],
                [     7 ,     0 ],
            ],
        ],
        '2x3 matrix' => [
            [
                -1 *  -3 + 2 *  5 ,
                -1 *  -7 + 2 * 11 ,
                -1 * -13 + 2 * 17 ,
            ],
            [   -1       , 2      ],
            [
                [     -3 ,      5 ],
                [     -7 ,     11 ],
                [    -13 ,     17 ],
            ],
        ],
        '3x2 matrix' => [
            [
                -3 *  11 + 5 * -13 + -7 *  17 ,
                -3 * -19 + 5 *  23 + -7 * -27 ,
            ],
            [   -3       , 5       , -7       ],
            [
                [     11 ,     -13 ,       17 ],
                [    -19 ,      23 ,      -27 ],
            ],
        ],
    ]);

    test("throws RangeException", function ($vector, $matrix) {
        expect(fn () => Vector\transform($vector, $matrix))->toThrow(
            \RangeException::class,
            'Vector and matrix column count must match.',
        );
    })->with([
        [
            [-1, 2, 3],
            [
                [1, 3],
                [-2, 0],
            ],
        ],
        [
            [-1, 2, 3],
            [
                [1, 3],
                [-2, 0],
                [5, 7],
            ],
        ],
        [
            [-1, 2],
            [
                [1, 3, 5],
                [-2, 0, 7],
            ],
        ],
    ]);
});

describe('hadamard()', function () {
    test('performs an element-wise product', function ($expected, $vector, ...$vectors) {
        $value = Vector\hadamard($vector, ...$vectors);
        expect($value)->toBe($expected);
    })->with([
        '2D vectors' => [
            [ 3 * -13 , -5 * 17 ],
            [ 3       , -5      ],
            [     -13 ,      17 ],
        ],
        '3D vectors' => [
            [ 3 * -13 , -5 * 17 , 7 * 23 ],
            [ 3       , -5      , 7      ],
            [     -13 ,      17 ,     23 ],
        ],
        'vectors with floats' => [
            [ 3.2 * -13.17 , -5.11 * 17.23 ],
            [ 3.2          , -5.11         ],
            [       -13.17 ,         17.23 ],
        ],
        'three 2D vectors' => [
            [ 3.19 * -5.23 * 7.29 , 11.31 * -13.37 * 17.41 ],
            [ 3.19                , 11.31                  ],
            [       -5.23         ,         -13.37         ],
            [                7.29 ,                  17.41 ],
        ]
    ]);
});
