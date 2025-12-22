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

describe('Vector\\dot()', function () {
    test('calculates dot product', function ($expected, $vector, $other) {
        $value = Vector\dot_product($vector, $other);
        expect($value)->toBe($expected);
    })->with([
        '3D vector' => [
              5 * -13 + -7 * 17 + 11 * 19 ,
            [ 5       , -7      , 11      ],
            [     -13 ,      17 ,      19 ],
        ],
        '2D vector' => [
              5.11 * -13.17 + -19.23 * 27.29 ,
            [ 5.11          , -19.23         ],
            [        -13.17 ,          27.29 ],
        ],
        '1D vector' => [
              5.11 * -13.17 ,
            [ 5.11          ],
            [        -13.17 ],
        ],
    ]);
});
