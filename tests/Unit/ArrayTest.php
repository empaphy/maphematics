<?php

/**
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 */

declare(strict_types=1);

use empaphy\maphematics\array;

describe('check_lengths()', function () {
    test('returns appropriate length', function (array $arrays, int $expected) {
        $length = array\check_lengths(...$arrays);

        expect($length)->toBe($expected);
    })->with([
        [
            [[], []],         // arrays
            0,                // expected
        ],
        [
            [[1, 2], [1, 2]], // arrays
            2,                // expected
        ],
    ]);

    test('throws RangeException when lengths differ', function (array $arrays) {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Lengths of arrays are not equal');

        array\check_lengths(...$arrays);
    })->with([
        [
            [[], [1]],
        ],
        [
            [[1], []],
        ],
        [
            [[3, 5], [7, 11, 13]],
        ],
        [
            [[3, 5, 7], [11, 13]],
        ],
    ]);
});