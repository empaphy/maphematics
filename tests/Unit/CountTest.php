<?php

/**
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 */

declare(strict_types=1);

use empaphy\maphematics\Count;

describe('have_same_count()', function () {
    test('checks if countables are the same count', function (array|Countable $countables, bool $expected) {
        $length = Count\have_same_count(...$countables);

        expect($length)->toBe($expected);
    })->with([
        [
            'countables' => [[], []],
            'expected'   => true,
        ],
        [
            'countables' => [[1], []],
            'expected'   => false,
        ],
        [
            'countables' => [[1, 2], [1, 2], [1, 2]],
            'expected'   => true,
        ],
    ]);
});