<?php

/**
 * Vector tests.
 *
 * @noinspection StaticClosureCanBeUsedInspection
 */

use empaphy\maphematics\vector;

describe('vector\add', function () {
    it('works', function ($vector, $vectors, $expected) {
        expect(vector\add($vector, ...$vectors))->toBe($expected);
    })->with([
        'two vectors'         => [[3, -5], [[2, 1]], [3 + 2, -5 + 1]],
        'three vectors'       => [[1, 2], [[3, 5], [7, 11]], [1 + 3 + 7, 2 + 5 + 11]],
        'vectors with floats' => [[3.0, -5.0], [[2.0, 1.0]], [3.0 + 2.0, -5.0 + 1.0]],
    ]);
});
