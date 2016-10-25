<?php

namespace Schemer\Test\Validator;

use Schemer\Validator\Text;
use Schemer\Validator\ValidatorAbstract;

describe(ValidatorAbstract::class, function () {
    context('::predicate', function () {
        it('returns success for truthy values', function () {
            expect(
                ValidatorAbstract::predicate(
                    function ($x) {
                        return $x;
                    },
                    'not a truthy value'
                )(true)->isError()
            )->toBe(false);
        });

        it('returns failure for falsy values', function () {
            expect(
                ValidatorAbstract::predicate(
                    function ($x) {
                        return $x;
                    },
                    'not a truthy value'
                )(false)->isError()
            )->toBe(true);
        });

        it('is not fatal for falsy values', function () {
            expect(
                ValidatorAbstract::predicate(
                    function ($x) {
                        return $x;
                    },
                    'not a truthy value'
                )(false)->isFatal()
            )->toBe(false);
        });

        it('returns the failure message for falsy values', function () {
            expect(
                ValidatorAbstract::predicate(
                    function ($x) {
                        return $x;
                    },
                    'not a truthy value'
                )(false)->errors()
            )->toBe(['not a truthy value']);
        });
    });

    context('::strictPredicate', function () {
        it('returns success for truthy values', function () {
            expect(
                ValidatorAbstract::strictPredicate(
                    function ($x) {
                        return $x;
                    },
                    'not a truthy value'
                )(true)->isError()
            )->toBe(false);
        });

        it('returns failure for falsy values', function () {
            expect(
                ValidatorAbstract::strictPredicate(
                    function ($x) {
                        return $x;
                    },
                    'not a truthy value'
                )(false)->isError()
            )->toBe(true);
        });

        it('is fatal for falsy values', function () {
            expect(
                ValidatorAbstract::strictPredicate(
                    function ($x) {
                        return $x;
                    },
                    'not a truthy value'
                )(false)->isFatal()
            )->toBe(true);
        });

        it('returns the failure message for falsy values', function () {
            expect(
                ValidatorAbstract::strictPredicate(
                    function ($x) {
                        return $x;
                    },
                    'not a truthy value'
                )(false)->errors()
            )->toBe(['not a truthy value']);
        });
    });

    context('->should', function () {
        it('succeeds for correct values', function () {
            expect(
                (new Text)->should(function ($x) {
                    return $x == 'Engelbert';
                }, 'Humperderp')
                    ->validate('Engelbert')
                    ->isError()
            )->toBe(false);
        });

        it('fails for incorrect values', function () {
            expect(
                (new Text)->should(function ($x) {
                    return $x == 'Engelbert';
                }, 'Humperderp')
                    ->validate('Humperdinck')
                    ->isError()
            )->toBe(true);
        });

        it('is non-fatal for incorrect values', function () {
            expect(
                (new Text)->should(function ($x) {
                    return $x == 'Engelbert';
                }, 'Humperderp')
                    ->validate('Seagulls')
                    ->isFatal()
            )->toBe(false);
        });

        it('returns the message for incorrect values', function () {
            expect(
                (new Text)->should(function ($x) {
                    return $x == 'Engelbert';
                }, 'Humperderp')
                    ->validate('Tom Jones')
                    ->errors()
            )->toBe(['Humperderp']);
        });
    });

    context('->must', function () {
        it('succeeds for correct values', function () {
            expect(
                (new Text)->must(function ($x) {
                    return $x == 'Benedict';
                }, 'Cumberblorp')
                    ->validate('Benedict')
                    ->isError()
            )->toBe(false);
        });

        it('fails for incorrect values', function () {
            expect(
                (new Text)->must(function ($x) {
                    return $x == 'Benedict';
                }, 'Cumberblorp')
                    ->validate('Biddlybop')
                    ->isError()
            )->toBe(true);
        });

        it('is non-fatal for incorrect values', function () {
            expect(
                (new Text)->must(function ($x) {
                    return $x == 'Benedict';
                }, 'Cumberblorp')
                    ->validate('Bellyflop')
                    ->isFatal()
            )->toBe(true);
        });

        it('returns the message for incorrect values', function () {
            expect(
                (new Text)->must(function ($x) {
                    return $x == 'Benedict';
                }, 'Cumberblorp')
                    ->validate('Redmayne')
                    ->errors()
            )->toBe(['Cumberblorp']);
        });
    });
});
