<?php

namespace Schemer\Test\Formatter;

use Schemer\Formatter\Real;

describe(Real::class, function () {
    context('Unaltered', function () {
        it('waives float values', function () {
            expect((new Real)->format(0.5))->toBe(0.5);
        });

        it('casts non-real values', function () {
            expect((new Real)->format(null))->toBe(0.0);
        });
    });

    context('->abs', function () {
        it('waives positive floats', function () {
            expect((new Real)->abs()->format(1.5))->toBe(1.5);
        });

        it('negates negative floats', function () {
            expect((new Real)->abs()->format(-1.5))->toBe(1.5);
        });
    });

    context('->max', function () {
        it('waives floats within boundaries', function () {
            expect((new Real)->max(20)->format('15'))->toBe(15.0);
        });

        it('caps floats outside boundaries', function () {
            expect((new Real)->max(10.2)->format(10.3))->toBe(10.2);
        });
    });

    context('->min', function () {
        it('caps floats outside boundaries', function () {
            expect((new Real)->min(20)->format('15'))->toBe(20.0);
        });

        it('waives floats within boundaries', function () {
            expect((new Real)->min(10.2)->format(10.3))->toBe(10.3);
        });
    });
});
