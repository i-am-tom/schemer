<?php

namespace Schemer\Test;

use Schemer\Formatter;
use Schemer\Formatter\Assoc;
use Schemer\Formatter\Boolean;
use Schemer\Formatter\Collection;
use Schemer\Formatter\Integer;
use Schemer\Formatter\Real;
use Schemer\Formatter\Text;

use stdClass;

describe(Formatter::class, function () {
    context('::assoc', function () {
        it('constructs an Assoc instance', function () {
            expect(
                Formatter::assoc(['test' => Formatter::boolean()])
            )->toBeAnInstanceOf(Assoc::class);
        });

        // Could test more, but Assoc's tests will cover it all.
        it('formats single properties', function () {
            expect(
                Formatter::assoc(['test' => Formatter::boolean()])
                    ->format(['test' => 1])
            )->toBe(['test' => true]);
        });
    });

    context('::boolean', function () {
        it('constructs a Boolean instance', function () {
            expect(Formatter::boolean())->toBeAnInstanceOf(Boolean::class);
        });

        it('waives boolean values', function () {
            expect(Formatter::boolean()->format(true))->toBe(true);
        });

        it('formats a truthy value', function () {
            expect(Formatter::boolean()->format([2]))->toBe(true);
        });

        it('formats a falsy value', function () {
            expect(Formatter::boolean()->format(null))->toBe(false);
        });
    });

    context('::collection', function () {
        it('constructs a Collection instance', function () {
            expect(
                Formatter::collection(Formatter::text())
            )->toBeAnInstanceOf(Collection::class);
        });

        it('waives formatted values', function () {
            expect(
                Formatter::collection(Formatter::text())
                    ->format(['h', 'i', '!'])
            )->toBe(['h', 'i', '!']);
        });

        it('converts singletons', function () {
            expect(
                Formatter::collection(Formatter::text())
                    ->format('hello')
            )->toBe(['hello']);
        });

        it('converts collection elements', function () {
            expect(
                Formatter::collection(Formatter::text())
                    ->format([2, 3.5, null, false])
            )->toBe(['2', '3.5', '', '']);
        });
    });

    context('::integer', function () {
        it('constructs an Integer instance', function () {
            expect(
                Formatter::integer()
            )->toBeAnInstanceOf(Integer::class);
        });

        it('waives integers', function () {
            expect(Formatter::integer()->format(123))->toBe(123);
        });

        it('casts non-integers', function () {
            expect(Formatter::integer()->format(true))->toBe(1);
        });

        it('floors reals', function () {
            expect(Formatter::integer()->format(2.5))->toBe(2);
        });
    });

    context('::real', function () {
        it('constructs a Real instance', function () {
            expect(Formatter::real())->toBeAnInstanceOf(Real::class);
        });

        it('waives reals', function () {
            expect(Formatter::real()->format(2.5))->toBe(2.5);
        });

        it('casts non-reals', function () {
            expect(Formatter::real()->format('1.5'))->toBe(1.5);
        });
    });

    context('::text', function () {
        it('constructs a Text instance', function () {
            expect(Formatter::text())->toBeAnInstanceOf(Text::class);
        });

        it('waives strings', function () {
            expect(Formatter::text()->format('test'))->toBe('test');
        });

        it('casts non-strings', function () {
            expect(Formatter::text()->format(3.142))->toBe('3.142');
        });
    });
});
