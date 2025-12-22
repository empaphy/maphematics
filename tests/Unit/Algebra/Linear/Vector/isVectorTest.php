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

describe('Vector\\is_vector()', function () {
    test('returns `true` if given value is a vector', function ($value) {
        $isVector = Vector\is_vector($value);
        expect($isVector)->toBeTrue();
    })->with([
        [[1  , 2,  3]],
        [[0.1, 2, -3]],
        [[1]],
        [[]],
    ]);

    test('returns `false` if given value is not a vector', function ($value) {
        $isVector = Vector\is_vector($value);
        expect($isVector)->toBeFalse();
    })->with([
        [[1, 2, 'three' => 3]],
        [[1, 2, 'foo']],
        [[1, 2, '3']],
        [[1, 2, '']],
        [[1, 2, null]],
    ]);
});
