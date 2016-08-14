<?php

namespace Schemer\Test\Formatter;

use Schemer\Formatter\Integer;

describe(Integer::class, function () {
    context('Unaltered', function () {
        it('waives integer values', function () {
            expect((new Integer)->format(0))->toBe(0);
        });

        it('casts non-integer values', function () {
            expect((new Integer)->format(2.0))->toBe(2);
        });
    });

    context('->abs', function () {
        it('waives positive integers', function () {
            expect((new Integer)->abs()->format(1))->toBe(1);
        });

        it('negates negative integers', function () {
            expect((new Integer)->abs()->format(-25))->toBe(25);
        });
    });

    context('->max', function () {
        it('waives integers within boundaries', function () {
            expect((new Integer)->max(20)->format('15'))->toBe(15);
        });

        it('caps integers outside boundaries', function () {
            expect((new Integer)->max(14)->format(320))->toBe(14);
        });
    });

    context('->min', function () {
        it('caps integers outside boundaries', function () {
            expect((new Integer)->min(20)->format('15'))->toBe(20);
        });

        it('waives integers within boundaries', function () {
            expect((new Integer)->min(531)->format(642))->toBe(642);
        });
    });
});
