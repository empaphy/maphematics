<?php

/**
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 *
 * @noinspection NonAsciiCharacters
 * @noinspection StaticClosureCanBeUsedInspection
 */

declare(strict_types=1);

namespace Pest\Unit\Algebra\Linear\Vector;

use empaphy\maphematics\Algebra\Linear\Vector;

describe('Vector\\transform()', function () {
    test('transforms', function ($expected, $x, $A, ...$…) {
        $value = Vector\transform($x, $A, ...$…);
        expect($value)->toBe($expected);
    })->with([
        '2x2 matrix' => [
            [
                -1 * -3 + 2 * 5 ,  // expected
                -1 *  7 + 2 * 0 ,
            ],
            [   -1      , 2     ], // x
            [                      // A
                [    -3 ,     5 ],
                [     7 ,     0 ],
            ],
        ],
        '2x2, 2x2 matrix' => [[
                (-1 * -3 + 2 * 5) * 11 + (-1 *  7 + 2 * 0) * 17,   // expected
                (-1 * -3 + 2 * 5) * 19 + (-1 *  7 + 2 * 0) * 13,
            ], [ -1      , 2 ], [                                  // x
                [     -3 ,     5 ],                                // A
                [      7 ,     0 ],
            ], [
                [                   11 ,                     17 ],   // …
                [                   19 ,                     13 ],
            ],
        ],
        '2x3 matrix' => [
            [
                -1 *  -3 + 2 *  5 ,  // expected
                -1 *  -7 + 2 * 11 ,
                -1 * -13 + 2 * 17 ,
            ],
            [   -1       , 2      ], // x
            [
                [     -3 ,      5 ], // A
                [     -7 ,     11 ],
                [    -13 ,     17 ],
            ],
        ],
        '3x2 matrix' => [
            [
                -3 *  11 + 5 * -13 + -7 *  17 ,  // expected
                -3 * -19 + 5 *  23 + -7 * -27 ,
            ],
            [   -3       , 5       , -7       ], // x
            [
                [     11 ,     -13 ,       17 ], // A
                [    -19 ,      23 ,      -27 ],
            ],
        ],
    ]);
});
