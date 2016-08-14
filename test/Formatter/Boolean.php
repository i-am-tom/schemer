<?php

namespace Schemer\Test\Formatter;

use Schemer\Formatter\Boolean;

describe(Boolean::class, function () {
    it('waives boolean values', function () {
        expect((new Boolean)->format(false))->toBe(false);
    });

    it('casts non-boolean values', function () {
        expect((new Boolean)->format(112358))->toBe(true);
    });
});
