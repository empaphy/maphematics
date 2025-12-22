<?php

/**
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 *
 * @noinspection StaticClosureCanBeUsedInspection
 */

declare(strict_types=1);

namespace Pest\Unit\Algebra\Linear\Matrix;

use empaphy\maphematics\Algebra\Linear\Matrix;

describe('is_matrix()', function () {
    test('returns `true` if value is a matrix', function (mixed $value) {
        $isMatrix = Matrix\is_matrix($value);
        expect($isMatrix)->toBeTrue();
    })->with([
        '1x1 matrix' => [[
            [ 3 ],
        ]],
        '2x2 matrix' => [[
            [  3   ,  5.7  ],
            [ 11.0 , 17.19 ],
        ]],
        '3x2 matrix' => [[
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
        ]],
        '2x3 matrix' => [[
            [  3 ,  5 ],
            [  7 , 11 ],
            [ 13 , 17 ],
        ]],
    ]);

    test('returns `false` if value is not a matrix', function (mixed $value) {
        $isMatrix = Matrix\is_matrix($value);
        expect($isMatrix)->toBeFalse();
    })->with([
        'empty matrix' => [[
            [],
        ]],
        'non-square matrix' => [[
            [  3 ,  5 ,  7 ],
            [ 11 , 13 ,    ],
            [ 17 , 19 , 23 ],
        ]],
        'triangular matrix' => [[
            [  3 ,  5 ,  7 ],
            [ 11 , 13      ],
            [ 17           ],
        ]],
        'matrix containing `null`' => [[
            [  3 ,  5 ,  7 ],
            [ 11 , 13 ,null],
            [ 17 , 19 , 23 ],
        ]],
        'matrix containing `string`' => [[
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 'X'],
            [ 17 , 19 , 23 ],
        ]],
        'vector' => [[3, 5, 7]],
    ]);

    test('returns `true` if all values are matrices', function (...$values) {
        $isMatrix = Matrix\is_matrix(...$values);
        expect($isMatrix)->toBeTrue();
    })->with([
        '1x1 matrices' => [[
            [ 3 ],
        ], [
            [ 5 ],
        ], [
            [ 7 ],
        ]],
        '2x2 matrices' => [[
            [  3.5  ,  7.11 ],
            [ 13.17 , 19.23 ],
        ], [
            [  3    ,  5.7  ],
            [ 11.13 , 17    ],
        ], [
            [  3.5  ,  7    ],
            [ 11    , 13.17 ],
        ]],
        '3x2 and 2x3 matrices' => [[
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
        ], [
            [  3 ,  5 ],
            [  7 , 11 ],
            [ 13 , 17 ],
        ]],
        '2x3, 3x3 and 3x2 matrices' => [[
            [  3 ,  5 ],
            [  7 , 11 ],
            [ 13 , 17 ],
        ], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
            [ 19 , 23 , 27 ],
        ], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
        ]],
        'containing empty matrix' => [[
            [  3 ,  5 ],
            [  7 , 11 ],
            [ 13 , 17 ],
        ], [], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
        ]],
    ]);

    test('returns `false` if any values are not matrices', function (...$values) {
        $isMatrix = Matrix\is_matrix(...$values);
        expect($isMatrix)->toBeFalse();
    })->with([
        'empty matrices' => [[
            [],
        ], [
            [],
        ], [
            [],
        ]],
        'containing non-square matrix' => [[
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
            [ 19 , 23 , 27 ],
        ], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13 ,    ],
            [ 19 , 23 , 27 ],
        ], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
            [ 19 , 23 , 27 ],
        ]],
        'containing triangular matrix' => [[
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
            [ 19 , 23 , 27 ],
        ], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13      ],
            [ 17           ],
        ], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
            [ 19 , 23 , 27 ],
        ]],
        'containing `null`' => [[
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
            [ 19 , 23 , 27 ],
        ], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13 ,null],
            [ 17 , 19 , 23 ],
        ], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
            [ 19 , 23 , 27 ],
        ]],
        'containing `string`' => [[
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
            [ 19 , 23 , 27 ],
        ], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 'X'],
            [ 19 , 23 , 27 ],
        ], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
            [ 19 , 23 , 27 ],
        ]],
        'containing `vector`' => [[
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
            [ 19 , 23 , 27 ],
        ], [3, 5, 7], [
            [  3 ,  5 ,  7 ],
            [ 11 , 13 , 17 ],
            [ 19 , 23 , 27 ],
        ]],
    ]);
});
