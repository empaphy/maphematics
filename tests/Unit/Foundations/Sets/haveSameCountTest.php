<?php

/**
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 *
 * @noinspection StaticClosureCanBeUsedInspection
 */

declare(strict_types=1);

namespace Pest\Unit;

use empaphy\maphematics\Foundations\Sets;

describe('have_same_count()', function () {
    test('checks if countables have the same count', function (array $args, bool $expected) {
        $length = Sets\have_same_count(...$args);

        expect($length)->toBe($expected);
    })->with([
        [
            'args'     => [[], []],
            'expected' => true,
        ],
        [
            'args'     => [[1], []],
            'expected' => false,
        ],
        [
            'args'     => [[1, 2], [1, 2], [1, 2]],
            'expected' => true,
        ],
    ]);
});