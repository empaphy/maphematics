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

use function sqrt;

describe('Vector\\length()', function () {
    test('calculates vector length', function ($expected, $vector) {
        $value = Vector\length($vector);
        expect($value)->toBe($expected);
    })->with([
        '3D vector' => [
            sqrt(5 ** 2 + (-7) ** 2 + 11 ** 2),
            [    5      ,  -7       , 11     ],
        ],
        '2D vector' => [
            sqrt(5.11 ** 2 + (-19.23) ** 2),
            [    5.11      ,  -19.23      ],
        ],
        '1D vector' => [
            sqrt(5.11 ** 2),
            [    5.11     ],
        ],
    ]);
});