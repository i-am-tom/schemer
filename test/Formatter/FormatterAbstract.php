<?php

namespace Schemer\Test\Formatter;

use Schemer\Test\Aux\Constructable;
use Schemer\Formatter\Integer;
use Schemer\Test\Formatter\FormatterAbstract;

describe(FormatterAbstract::class, function () {
    context('->construct', function () {
        it('constructs the given class', function () {
            expect(
                (new Integer)
                    ->construct(Constructable::class)
                    ->format('3')
            )->toBeAnInstanceOf(Constructable::class);
        });

        it('constructs with the value', function () {
            expect(
                (new Integer)
                    ->construct(Constructable::class)
                    ->format('3')
                    ->test
            )->toBe(3);
        });
    });

    context('->fallback', function () {
        it('uses the default when null', function () {
            expect(
                (new Integer)
                    ->fallback(24)
                    ->format(null)
            )->toBe(24);
        });
    });
});
