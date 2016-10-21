<?php

namespace Schemer\Test\Validator;

use Schemer\Validator\Real;

describe(Real::class, function () {
    context('__construct', function () {
        it('accepts float values', function () {
            expect(
                (new Real)
                    ->validate(2.5)
                    ->errors()
            )->toBe([]);
        });

        it('rejects non-floats', function () {
            expect(
                (new Real)
                    ->validate(true)
                    ->errors()
            )->toBe(['not a float']);
        });

        it('rejects non-floats, with custom error', function () {
            expect(
                (new Real('kinda?'))
                    ->validate('ice cream float')
                    ->errors()
            )->toBe(['kinda?']);
        });
    });

    context('->exactly', function () {
        it('accepts equal floats', function () {
            expect(
                (new Real)
                    ->exactly(2)
                    ->validate(2.0)
                    ->errors()
            )->toBe([]);
        });

        it('rejects different floats', function () {
            expect(
                (new Real)
                    ->exactly(2)
                    ->validate(2.5)
                    ->errors()
            )->toBe(['not exactly 2']);
        });

        it('rejects different floats, with custom error', function () {
            expect(
                (new Real)
                    ->exactly(2, "that's ... not what I expected")
                    ->validate(2.5)
                    ->errors()
            )->toBe(["that's ... not what I expected"]);
        });

        it('rejects different floats, with custom error format', function () {
            expect(
                (new Real)
                    ->exactly(2, '%d be or not %1$d be')
                    ->validate(2.5)
                    ->errors()
            )->toBe(['2 be or not 2 be']);
        });
    });

    context('->max', function () {
        it('accepts equal floats', function () {
            expect(
                (new Real)
                    ->max(2)
                    ->validate(2.0)
                    ->errors()
            )->toBe([]);
        });

        it('accepts lower floats', function () {
            expect(
                (new Real)
                    ->max(2)
                    ->validate(1.9)
                    ->errors()
            )->toBe([]);
        });

        it('rejects higher floats', function () {
            expect(
                (new Real)
                    ->max(2)
                    ->validate(2.5)
                    ->errors()
            )->toBe(['not at most 2']);
        });

        it('rejects higher floats, with custom error', function () {
            expect(
                (new Real)
                    ->max(9000, "It's over 9000!")
                    ->validate(9000.1)
                    ->errors()
            )->toBe(["It's over 9000!"]);
        });
    });

    context('->min', function () {
        it('accepts equal floats', function () {
            expect(
                (new Real)
                    ->min(2)
                    ->validate(2.0)
                    ->errors()
            )->toBe([]);
        });

        it('rejects lower floats', function () {
            expect(
                (new Real)
                    ->min(2)
                    ->validate(1.9)
                    ->errors()
            )->toBe(['not at least 2']);
        });

        it('rejects lower floats, with custom error', function () {
            expect(
                (new Real)
                    ->min(1, 'smaller than %d')
                    ->validate(0.5)
                    ->errors()
            )->toBe(['smaller than 1']);
        });

        it('accepts higher floats', function () {
            expect(
                (new Real)
                    ->min(2)
                    ->validate(2.5)
                    ->errors()
            )->toBe([]);
        });
    });

    context('->negative', function () {
        it('accepts 0', function () {
            expect(
                (new Real)
                    ->negative()
                    ->validate(0.0)
                    ->errors()
            )->toBe([]);
        });

        it('rejects > 0', function () {
            expect(
                (new Real)
                    ->negative()
                    ->validate(0.5)
                    ->errors()
            )->toBe(['not at most 0']);
        });

        it('rejects > 0, with custom error', function () {
            expect(
                (new Real)
                    ->negative('positive')
                    ->validate(0.5)
                    ->errors()
            )->toBe(['positive']);
        });

        it('accepts < 0', function () {
            expect(
                (new Real)
                    ->negative()
                    ->validate(-0.5)
                    ->errors()
            )->toBe([]);
        });
    });

    context('->positive', function () {
        it('accepts 0', function () {
            expect(
                (new Real)
                    ->positive()
                    ->validate(0.0)
                    ->errors()
            )->toBe([]);
        });

        it('accepts > 0', function () {
            expect(
                (new Real)
                    ->positive()
                    ->validate(0.5)
                    ->errors()
            )->toBe([]);
        });

        it('rejects < 0', function () {
            expect(
                (new Real)
                    ->positive()
                    ->validate(-0.5)
                    ->errors()
            )->toBe(['not at least 0']);
        });

        it('rejects < 0, with custom error', function () {
            expect(
                (new Real)
                    ->positive('negative')
                    ->validate(PHP_INT_MIN - 1)
                    ->errors()
            )->toBe(['negative']);
        });
    });
});
