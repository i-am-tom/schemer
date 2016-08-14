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

    context('::but', function () {
        it('allows passes for extra restrictions', function () {
            expect(
                (new Any)
                    ->but(
                        ValidatorAbstract::predicate(
                            function ($x) {
                                return $x === 1;
                            },
                            'not correct'
                        )
                    )
                    ->validate(1)
                    ->errors()
            )->toBe([]);
        });

        it('rejects failures on extra restrictions', function () {
            expect(
                (new Any)
                    ->but(
                        ValidatorAbstract::predicate(
                            function ($x) {
                                return $x === 1;
                            },
                            'not correct'
                        )
                    )
                    ->validate(2)
                    ->errors()
            )->toBe(['not correct']);
        });
    });
});
