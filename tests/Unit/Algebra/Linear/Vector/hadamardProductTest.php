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

describe('Vector\\hadamard_product()', function () {
    test('performs an element-wise product', function ($expected, $vector, ...$vectors) {
        $value = Vector\hadamard_product($vector, ...$vectors);
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
