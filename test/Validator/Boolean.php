<?php

namespace Schema\Test\Validator;

use Schemer\Validator\{Boolean,ValidatorAbstract};

describe(Boolean::class, function () {
    context('Standard', function () {
        it('accepts booleans', function () {
            expect(
                (new Boolean)
                    ->validate(true)
                    ->errors()
            )->toBe([]);
        });

        it('rejects non-booleans', function () {
            expect(
                (new Boolean)
                    ->validate('hello, Wimbledon')
                    ->errors()
            )->toBe(['not a boolean']);
        });

        it('rejects non-booleans, with custom error', function () {
            expect(
                (new Boolean('George says no'))
                    ->validate('hello, Wimbledon')
                    ->errors()
            )->toBe(['George says no']);
        });
    });

    context('True-only', function () {
        it('accepts true', function () {
            expect(
                (new Boolean)
                    ->true()
                    ->validate(true)
                    ->errors()
            )->toBe([]);
        });

        it('accepts true, with custom error', function () {
            expect(
                (new Boolean('Oh dear'))
                    ->true('Oh dear oh dear')
                    ->validate(true)
                    ->errors()
            )->toBe([]);
        });

        it('rejects false', function () {
            expect(
                (new Boolean)
                    ->true()
                    ->validate(false)
                    ->errors()
            )->toBe(['not true']);
        });

        it('rejects false, with custom error', function () {
            expect(
                (new Boolean('Oh dear oh dear oh dear'))
                    ->true('Oh deary dear')
                    ->validate(false)
                    ->errors()
            )->toBe(['Oh deary dear']);
        });
    });

    context('False-only', function () {
        it('accepts false', function () {
            expect(
                (new Boolean)
                    ->false()
                    ->validate(false)
                    ->errors()
            )->toBe([]);
        });

        it('accepts false, with custom error', function () {
            expect(
                (new Boolean())
                    ->false('Whoops!')
                    ->validate(false)
                    ->errors()
            )->toBe([]);
        });

        it('rejects true', function () {
            expect(
                (new Boolean)
                    ->false()
                    ->validate(true)
                    ->errors()
            )->toBe(['not false']);
        });

        it('rejects true, with custom error', function () {
            expect(
                (new Boolean('Lions and tigers and bears?'))
                    ->false('Oh my!')
                    ->validate(true)
                    ->errors()
            )->toBe(['Oh my!']);
        });
    });
});
