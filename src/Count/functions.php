<?php

/**
 * @author    Alwin Garside <alwin@garsi.de>
 * @copyright 2025 The Empaphy Project
 * @license   MIT
 * @package   Array
 *
 * @noinspection NonAsciiCharacters
 */

declare(strict_types=1);

namespace empaphy\maphematics\Count;

use Countable;

use function count;

/**
 * Finds whether two or more countables have the same count.
 *
 * @param  array|Countable  $c
 *   A countable.
 *
 * @param  array|Countable  $d
 *   Another countable.
 *
 * @param  array|Countable  ...$…
 *   More countables.
 *
 * @return bool
 *   Returns `true` if all provided countables have the same count, `false`
 *   otherwise.
 */
function have_same_count(
    array|Countable $c,
    array|Countable $d,
    array|Countable ...$…,
): bool {
    $n = count($c);

    if (count($d) !== $n) {
        return false;
    }

    foreach ($… as $e) {
        if (count($e) !== $n) {
            return false;
        }
    }

    return true;
}
