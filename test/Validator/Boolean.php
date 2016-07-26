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

        it('rejects false', function () {
            expect(
                (new Boolean)
                    ->true()
                    ->validate(false)
                    ->errors()
            )->toBe(['not true']);
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

        it('rejects true', function () {
            expect(
                (new Boolean)
                    ->false()
                    ->validate(true)
                    ->errors()
            )->toBe(['not false']);
        });
    });
});
