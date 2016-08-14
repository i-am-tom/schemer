<?php

namespace Schemer\Test\Formatter;

use Schemer\Formatter\Any;

describe(Any::class, function () {
    context('Unaltered', function () {
        it('waives strings', function () {
            expect((new Any)->format('aj'))->toBe('aj');
        });

        it('waives floats', function () {
            expect((new Any)->format(3.5))->toBe(3.5);
        });

        it('waives everything', function () {
            expect((new Any)->format(null))->toBe(null);
        });
    });

    context('->but', function () {
        it('allows extra transformations', function () {
            expect(
                (new Any)
                    ->but('strtoupper')
                    ->format('bonjour')
            )->toBe('BONJOUR');
        });
    });
});
