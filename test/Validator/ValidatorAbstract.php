<?php

namespace Schemer\Test\Validator;

use Schemer\Validator\Text;
use Schemer\Validator\ValidatorAbstract;

describe(ValidatorAbstract::class, function () {
    context('::predicate', function () {
        $id = ValidatorAbstract::predicate(
            function ($x) {
                return $x;
            },
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
            function ($x) {
                return $x;
            },
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

    context('->should', function () {
        $v = (new Text)->should(function ($x) {
            return $x == 'Engelbert';
        }, 'Humperderp');

        it('succeeds for correct values', function () use ($v) {
            expect($v->validate('Engelbert')->isError())->toBe(false);
        });

        it('fails for incorrect values', function () use ($v) {
            expect($v->validate('Humperdinck')->isError())->toBe(true);
        });

        it('is non-fatal for incorrect values', function () use ($v) {
            expect($v->validate('Seagulls')->isFatal())->toBe(false);
        });

        it('returns the message for incorrect values', function () use ($v) {
            expect($v->validate('Tom Jones')->errors())->toBe(['Humperderp']);
        });
    });

    context('->must', function () {
        $v = (new Text)->must(function ($x) {
            return $x == 'Benedict';
        }, 'Cumberblorp');

        it('succeeds for correct values', function () use ($v) {
            expect($v->validate('Benedict')->isError())->toBe(false);
        });

        it('fails for incorrect values', function () use ($v) {
            expect($v->validate('Biddlybop')->isError())->toBe(true);
        });

        it('is non-fatal for incorrect values', function () use ($v) {
            expect($v->validate('Bellyflop')->isFatal())->toBe(true);
        });

        it('returns the message for incorrect values', function () use ($v) {
            expect($v->validate('Redmayne')->errors())->toBe(['Cumberblorp']);
        });
    });
});
