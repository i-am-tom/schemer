<?php

namespace Schema\Test\Validator;

use Schemer\Validator\Any;
use Schemer\Validator\ValidatorAbstract;

describe(Any::class, function () {
    context('Unaltered', function () {
        it('accepts strings', function () {
            expect(
                (new Any)
                    ->validate('i-am-tom')
                    ->errors()
            )->toBe([]);
        });

        it('accepts floats', function () {
            expect(
                (new Any)
                    ->validate(3.5)
                    ->errors()
            )->toBe([]);
        });

        it('accepts anything', function () {
            expect(
                (new Any)
                    ->validate(null)
                    ->errors()
            )->toBe([]);
        });
    });
});
