<?php

/**
 * Vector tests.
 *
 * @noinspection StaticClosureCanBeUsedInspection
 */

use empaphy\maphematics\vector;

describe('vector\\add', function () {
    test('adds', function ($vector, $vectors, $expected) {
        expect(vector\add($vector, ...$vectors))->toBe($expected);
    })->with([
        'two vectors'         => [[3, -5], [[2, 1]], [3 + 2, -5 + 1]],
        'three vectors'       => [[1, 2], [[3, 5], [7, 11]], [1 + 3 + 7, 2 + 5 + 11]],
        'vectors with floats' => [[3.0, -5.0], [[2.0, 1.0]], [3.0 + 2.0, -5.0 + 1.0]],
    ]);
});

describe('vector\\scale', function () {
    test('scales', function ($vector, $scalar, $expected) {
        expect(vector\scale($vector, $scalar))->toBe($expected);
    })->with([
        'simple scalar' => [[3, -5], 2, [3 * 2, -5 * 2]],
    ]);
});

describe('vector\\scale_divide', function () {
    test('scales and divides', function ($vector, $scalar, $expected) {
        expect(vector\scale_divide($vector, $scalar))->toBe($expected);
    })->with([
        'simple scalar' => [[3, -5], 2, [3 / 2, -5 / 2]],
    ]);
});

describe('vector\\transform', function () {
    test('transforms', function ($vector, $matrix, $expected) {
        expect(vector\transform($vector, $matrix))->toBe($expected);
    })->with([
        [
            [-1, 2],
            [
                [1, 3],
                [-2, 0],
            ],
            [5, 2],
        ],
        [
            [-1, 2],
            [
                [1, 3],
                [-2, 0],
                [5, 7],
            ],
            [5, 2, 9],
        ],
        [
            [-1, 2, 5],
            [
                [1, 3, 7],
                [-2, 0, 11],
            ],
            [40, 57],
        ],
    ]);

    test("throws RangeException", function ($vector, $matrix) {
        expect(fn() => vector\transform($vector, $matrix))->toThrow(
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
