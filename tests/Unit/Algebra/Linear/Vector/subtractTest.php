<?php

/**
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 *
 * @noinspection StaticClosureCanBeUsedInspection
 */

declare(strict_types=1);

namespace Pest\Unit\Algebra\Linear\Vector;

use empaphy\maphematics\Algebra\Linear\Vector;

describe('Vector\\subtract()', function () {
    test('subtracts vectors from another', function ($expected, ...$vectors) {
        $value = Vector\subtract(...$vectors);
        expect($value)->toBe($expected);
    })->with([
        'two vectors' => [
            [ 1 - 3 , 2 - (-1) ], // expected
            [ 1     , 2        ], // v
            [     3 ,      -1  ], // w
        ],
        'three vectors' => [
            [ 1 - 3 - 7 , 2 - 5 - 11 ], // expected
            [ 1         , 2          ], // v
            [     3     ,     5      ], // w
            [         7 ,         11 ], // x
        ],
        '3D vectors' => [
            [ 1 - 5 - 13 , 2 - 7 - 17 , 3 - 11 - 23 ], // expected
            [ 1          , 2          , 3           ], // v
            [     5      ,     7      ,     11      ], // w
            [         13 ,         17 ,          23 ], // x
        ],
        'vectors with floats' => [
            [ 1.2 - 3.5 , -7.11 - 17.23 ], // expected
            [ 1.2       , -7.11         ], // v
            [       3.5 ,         17.23 ], // w
        ],
    ]);
});
