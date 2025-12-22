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

describe('Vector\\divide()', function () {
    test('divides a vector by a scalar', function ($expected, $vector, $divisor) {
        $value = Vector\divide($vector, $divisor);
        expect($value)->toBe($expected);
    })->with([
        'simple divisor' => [
            'expected' => [ 3 / 2, -5 / 2],
            'vector'   => [ 3,     -5    ],
            'divisor'  => 2,
        ],
        '3d vector' => [
            'expected' => [ 3 / 11 , -5 / 11 , 7 / 11 ],
            'vector'   => [ 3      , -5      , 7      ],
            'divisor'  => 11,
        ],
    ]);
});
