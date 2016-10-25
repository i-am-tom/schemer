<?php

namespace Schemer\Test\Formatter;

use Schemer\Formatter\Boolean;

describe(Boolean::class, function () {
    it('waives boolean values', function () {
        expect((new Boolean)->format(false))->toBe(false);
    });

    it('handles the string "true"', function () {
        expect((new Boolean)->format('true'))->toBe(true);
    });

    it('handles the string "false"', function () {
        expect((new Boolean)->format('false'))->toBe(false);
    });

    it('handles the string "1"', function () {
        expect((new Boolean)->format('1'))->toBe(true);
    });

    it('handles the string "0"', function () {
        expect((new Boolean)->format('0'))->toBe(false);
    });

    it('handles the empty string', function () {
        expect((new Boolean)->format(''))->toBe(false);
    });

    it('casts non-boolean values', function () {
        expect((new Boolean)->format(112358))->toBe(true);
    });
});
