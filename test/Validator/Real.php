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

        it('accepts int values', function () {
            expect(
                (new Real)
                    ->validate(2)
                    ->errors()
            )->toBe([]);
        });

        it('rejects booleans', function () {
            expect(
                (new Real)
                    ->validate(true)
                    ->errors()
            )->toBe(['not a float']);
        });

        it('rejects numeric strings', function () {
            expect(
                (new Real)
                    ->validate('2')
                    ->errors()
            )->toBe(['not a float']);
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

        it('accepts equal ints', function () {
            expect(
                (new Real)
                    ->exactly(2)
                    ->validate(2)
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
    });
});
