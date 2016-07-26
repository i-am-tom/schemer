<?php

namespace Schemer\Test\Validator;

use Schemer\Validator\Integer;

describe(Integer::class, function () {
    context('__construct', function () {
        it('accepts integer values', function () {
            expect(
                (new Integer)
                    ->validate(2)
                    ->errors()
            )->toBe([]);
        });

        it('rejects non-integers', function () {
            expect(
                (new Integer)
                    ->validate(2.5)
                    ->errors()
            )->toBe(['not an integer']);
        });
    });

    context('->exactly', function () {
        it('accepts equal integers', function () {
            expect(
                (new Integer)
                    ->exactly(2)
                    ->validate(2)
                    ->errors()
            )->toBe([]);
        });

        it('rejects different integers', function () {
            expect(
                (new Integer)
                    ->exactly(2)
                    ->validate(3)
                    ->errors()
            )->toBe(['not exactly 2']);
        });
    });

    context('->max', function () {
        it('accepts equal integers', function () {
            expect(
                (new Integer)
                    ->max(2)
                    ->validate(2)
                    ->errors()
            )->toBe([]);
        });

        it('accepts lower integers', function () {
            expect(
                (new Integer)
                    ->max(2)
                    ->validate(1)
                    ->errors()
            )->toBe([]);
        });

        it('rejects higher integers', function () {
            expect(
                (new Integer)
                    ->max(2)
                    ->validate(3)
                    ->errors()
            )->toBe(['not at most 2']);
        });
    });

    context('->min', function () {
        it('accepts equal integers', function () {
            expect(
                (new Integer)
                    ->min(2)
                    ->validate(2)
                    ->errors()
            )->toBe([]);
        });

        it('rejects lower integers', function () {
            expect(
                (new Integer)
                    ->min(2)
                    ->validate(1)
                    ->errors()
            )->toBe(['not at least 2']);
        });

        it('accepts higher integers', function () {
            expect(
                (new Integer)
                    ->min(2)
                    ->validate(3)
                    ->errors()
            )->toBe([]);
        });
    });

    context('->negative', function () {
        it('accepts 0', function () {
            expect(
                (new Integer)
                    ->negative()
                    ->validate(0)
                    ->errors()
            )->toBe([]);
        });

        it('rejects > 0', function () {
            expect(
                (new Integer)
                    ->negative()
                    ->validate(1)
                    ->errors()
            )->toBe(['not at most 0']);
        });

        it('accepts < 0', function () {
            expect(
                (new Integer)
                    ->negative()
                    ->validate(-1)
                    ->errors()
            )->toBe([]);
        });
    });

    context('->positive', function () {
        it('accepts 0', function () {
            expect(
                (new Integer)
                    ->positive()
                    ->validate(0)
                    ->errors()
            )->toBe([]);
        });

        it('accepts > 0', function () {
            expect(
                (new Integer)
                    ->positive()
                    ->validate(1)
                    ->errors()
            )->toBe([]);
        });

        it('rejects < 0', function () {
            expect(
                (new Integer)
                    ->positive()
                    ->validate(-1)
                    ->errors()
            )->toBe(['not at least 0']);
        });
    });
});
