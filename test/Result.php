<?php

namespace Schemer\Test;

use Schemer\Result;

describe(Result::class, function () {
    context('::success', function () {
        it('returns false on isError()', function () {
            expect(Result::success()->isError())->toBe(false);
        });

        it('returns false on isFatal()', function () {
            expect(Result::success()->isFatal())->toBe(false);
        });

        it('returns no errors', function () {
            expect(Result::success()->errors())->toBe([]);
        });
    });

    context('::failure', function () {
        it('returns true on isError()', function () {
            expect(
                Result::failure('test')
                    ->isError()
            )->toBe(true);
        });

        it('returns false on isFatal()', function () {
            expect(
                Result::failure('test')
                    ->isFatal()
            )->toBe(false);
        });

        it('returns an error', function () {
            expect(
                Result::failure('test')
                    ->errors()
            )->toBe(['test']);
        });
    });

    context('::fatal', function () {
        it('returns true on isError()', function () {
            expect(
                Result::fatal('test')
                    ->isError()
            )->toBe(true);
        });

        it('returns true on isFatal()', function () {
            expect(
                Result::fatal('test')
                    ->isFatal()
            )->toBe(true);
        });

        it('returns an error', function () {
            expect(
                Result::fatal('test')
                    ->errors()
            )->toBe(['test']);
        });
    });

    context('::concat', function () {
        context('Success-success', function () {
            it('returns no errors', function () {
                expect(
                    Result::success()
                        ->concat(Result::success())
                        ->errors()
                )->toBe([]);
            });

            it('is not fatal', function () {
                expect(
                    Result::success()
                        ->concat(Result::success())
                        ->isFatal()
                )->toBe(false);
            });

            it('is not an error', function () {
                expect(
                    Result::success()
                        ->concat(Result::success())
                        ->isError()
                )->toBe(false);
            });
        });

        context('Success-failure', function () {
            it('returns an error', function () {
                expect(
                    Result::success()
                        ->concat(Result::failure('b'))
                        ->errors()
                )->toBe(['b']);
            });

            it('is not fatal', function () {
                expect(
                    Result::success()
                        ->concat(Result::failure('b'))
                        ->isFatal()
                )->toBe(false);
            });

            it('is an error', function () {
                expect(
                    Result::success()
                        ->concat(Result::failure('b'))
                        ->isError()
                )->toBe(true);
            });
        });

        context('Success-fatal', function () {
            it('returns an error', function () {
                expect(
                    Result::success()
                        ->concat(Result::fatal('b'))
                        ->errors()
                )->toBe(['b']);
            });

            it('is fatal', function () {
                expect(
                    Result::success()
                        ->concat(Result::fatal('b'))
                        ->isFatal()
                )->toBe(true);
            });

            it('is an error', function () {
                expect(
                    Result::success()
                        ->concat(Result::fatal('b'))
                        ->isError()
                )->toBe(true);
            });
        });

        context('Failure-success', function () {
            it('returns an error', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::success())
                        ->errors()
                )->toBe(['a']);
            });

            it('is not fatal', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::success())
                        ->isFatal()
                )->toBe(false);
            });

            it('is an error', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::success())
                        ->isError()
                )->toBe(true);
            });
        });

        context('Failure-failure', function () {
            it('returns errors', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::failure('b'))
                        ->errors()
                )->toBe(['a', 'b']);
            });

            it('is not fatal', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::failure('b'))
                        ->isFatal()
                )->toBe(false);
            });

            it('is an error', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::failure('b'))
                        ->isError()
                )->toBe(true);
            });
        });

        context('Failure-fatal', function () {
            it('returns errors', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::fatal('b'))
                        ->errors()
                )->toBe(['a', 'b']);
            });

            it('is fatal', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::fatal('b'))
                        ->isFatal()
                )->toBe(true);
            });

            it('is an error', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::fatal('b'))
                        ->isError()
                )->toBe(true);
            });
        });

        context('Fatal-success', function () {
            it('returns an error', function () {
                expect(
                    Result::fatal('a')
                        ->concat(Result::success())
                        ->errors()
                )->toBe(['a']);
            });

            it('is fatal', function () {
                expect(
                    Result::fatal('a')
                        ->concat(Result::success())
                        ->isFatal()
                )->toBe(true);
            });

            it('is an error', function () {
                expect(
                    Result::fatal('a')
                        ->concat(Result::success())
                        ->isError()
                )->toBe(true);
            });
        });

        context('Fatal-failure', function () {
            it('returns errors', function () {
                expect(
                    Result::fatal('a')
                        ->concat(Result::failure('b'))
                        ->errors()
                )->toBe(['a', 'b']);
            });

            it('is fatal', function () {
                expect(
                    Result::fatal('a')
                        ->concat(Result::failure('b'))
                        ->isFatal()
                )->toBe(true);
            });

            it('is an error', function () {
                expect(
                    Result::fatal('a')
                        ->concat(Result::failure('b'))
                        ->isError()
                )->toBe(true);
            });
        });

        context('Fatal-fatal', function () {
            it('returns errors', function () {
                expect(
                    Result::fatal('a')
                        ->concat(Result::fatal('b'))
                        ->errors()
                )->toBe(['a', 'b']);
            });

            it('is fatal', function () {
                expect(
                    Result::fatal('a')
                        ->concat(Result::fatal('b'))
                        ->isFatal()
                )->toBe(true);
            });

            it('is an error', function () {
                expect(
                    Result::fatal('a')
                        ->concat(Result::fatal('b'))
                        ->isError()
                )->toBe(true);
            });
        });
    });

    context('::map', function () {
        context('Success', function () {
            it('has no errors', function () {
                expect(
                    Result::success()
                        ->map('strtoupper')
                        ->errors()
                )->toBe([]);
            });

            it('is not an error', function () {
                expect(
                    Result::success()
                        ->map('strtoupper')
                        ->isError()
                )->toBe(false);
            });

            it('is not fatal', function () {
                expect(
                    Result::success()
                        ->map('strtoupper')
                        ->isFatal()
                )->toBe(false);
            });
        });

        context('Failure', function () {
            it('has a transformed error', function () {
                expect(
                    Result::failure('a')
                        ->map('strtoupper')
                        ->errors()
                )->toBe(['A']);
            });

            it('is an error', function () {
                expect(
                    Result::failure('a')
                        ->map('strtoupper')
                        ->isError()
                )->toBe(true);
            });

            it('is not fatal', function () {
                expect(
                    Result::failure('a')
                        ->map('strtoupper')
                        ->isFatal()
                )->toBe(false);
            });
        });

        context('Fatal', function () {
            it('has a transformed error', function () {
                expect(
                    Result::fatal('a')
                        ->map('strtoupper')
                        ->errors()
                )->toBe(['A']);
            });

            it('is an error', function () {
                expect(
                    Result::fatal('a')
                        ->map('strtoupper')
                        ->isError()
                )->toBe(true);
            });

            it('is fatal', function () {
                expect(
                    Result::fatal('a')
                        ->map('strtoupper')
                        ->isFatal()
                )->toBe(true);
            });
        });

        context('Failures', function () {
            it('transforms all errors', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::failure('b'))
                        ->map('strtoupper')
                        ->errors()
                )->toBe(['A', 'B']);
            });

            it('is an error', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::failure('b'))
                        ->map('strtoupper')
                        ->isError()
                )->toBe(true);
            });

            it('is not fatal', function () {
                expect(
                    Result::failure('a')
                        ->concat(Result::failure('b'))
                        ->map('strtoupper')
                        ->isFatal()
                )->toBe(false);
            });
        });
    });
});
