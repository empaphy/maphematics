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

describe('Vector\\scale()', function () {
    test('scales a vector', function ($expected, $vector, $scalar) {
        $value = Vector\scale($vector, $scalar);
        expect($value)->toBe($expected);
    })->with([
        'simple scalar' => [
            'expected' => [ 3 * 2 , -5 * 2 ],
            'vector'   => [ 3     , -5     ],
            'scalar'   => 2,
        ],
        '3D vector' => [
            'expected' => [ 3 * 11 , -5 * 11 , 7 * 11 ],
            'vector'   => [ 3      , -5      , 7      ],
            'scalar'   => 11,
        ],
    ]);
});
