<?php

namespace Schemer\Test;

use Schemer\NestableResult;
use Schemer\Result;

describe(NestableResult::class, function () {
    context('__construct', function () {
        context('Result', function () {
            it('holds a successful Result', function () {
                expect(
                    (new NestableResult(Result::success(), []))
                        ->outer->errors()
                )->toBe([]);
            });

            it('holds a failing Result', function () {
                expect(
                    (new NestableResult(Result::failure('test'), []))
                        ->outer->errors()
                )->toBe(['test']);
            });
        });

        context('Values', function () {
            it('holds inner Results', function () {
                $values = [
                    Result::success(),
                    Result::failure('test')
                ];

                expect(
                    (new NestableResult(Result::success(), $values))
                        ->inner
                )->toBe($values);
            });
        });
    });

    context('::lift', function () {
        it('lifts a successful Result', function () {
            expect(
                NestableResult::lift(Result::success())
                    ->outer->errors()
            )->toBe([]);
        });

        it('lifts an unsuccessful Result', function () {
            expect(
                NestableResult::lift(Result::failure('test'))
                    ->outer->errors()
            )->toBe(['test']);
        });
    });
});
