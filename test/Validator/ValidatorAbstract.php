<?php

namespace Schemer\Test\Validator;

use Schemer\Validator\ValidatorAbstract;

describe(ValidatorAbstract::class, function () {
    context('::predicate', function () {
        $id = ValidatorAbstract::predicate(
            function ($x) { return $x; },
            'not a truthy value'
        );

        it('returns success for truthy values', function () use ($id) {
            expect($id(true)->isError())->toBe(false);
        });

        it('returns failure for falsy values', function () use ($id) {
            expect($id(false)->isError())->toBe(true);
        });

        it('is not fatal for falsy values', function () use ($id) {
            expect($id(false)->isFatal())->toBe(false);
        });

        it('returns the failure message for falsy values', function () use ($id) {
            expect($id(false)->errors())->toBe(['not a truthy value']);
        });
    });

    context('::strictPredicate', function () {
        $id = ValidatorAbstract::strictPredicate(
            function ($x) { return $x; },
            'not a truthy value'
        );

        it('returns success for truthy values', function () use ($id) {
            expect($id(true)->isError())->toBe(false);
        });

        it('returns failure for falsy values', function () use ($id) {
            expect($id(false)->isError())->toBe(true);
        });

        it('is fatal for falsy values', function () use ($id) {
            expect($id(false)->isFatal())->toBe(true);
        });

        it('returns the failure message for falsy values', function () use ($id) {
            expect($id(false)->errors())->toBe(['not a truthy value']);
        });
    });
});
