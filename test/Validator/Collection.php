<?php

namespace Schemer\Test\Validator;

use Schemer\Validator\Any;
use Schemer\Validator\Boolean;
use Schemer\Validator\Collection;
use Schemer\Validator\Integer;
use Schemer\Validator\Text;

describe(Collection::class, function () {
    context('__construct', function () {
        it('accepts standard arrays', function () {
            expect(
                (new Collection(new Any))
                    ->validate([1, 2, 3])
                    ->errors()
            )->toBe([]);
        });

        it('rejects non-arrays', function () {
            expect(
                (new Collection(new Any))
                    ->validate(true)
                    ->errors()
            )->toBe(['not an array']);
        });

        it('rejects associative arrays', function () {
            expect(
                (new Collection(new Any))
                    ->validate(['a' => 'b'])
                    ->errors()
            )->toBe(['not a standard array']);
        });

        context('Type-Checking', function () {
            it('rejects arrays with nonconforming elements', function () {
                expect(
                    (new Collection(new Boolean))
                        ->validate([true, true, 2.0])
                        ->errors()
                )->toBe(['not a boolean at index 2']);
            });
        });
    });

    context('->length', function () {
        it('accepts lists of the right length', function () {
            expect(
                (new Collection(new Boolean))
                    ->length(3)
                    ->validate([true, true, true])
                    ->errors()
            )->toBe([]);
        });

        it('rejects lists of the wrong length', function () {
            expect(
                (new Collection(new Boolean))
                    ->length(2)
                    ->validate([true, true, true])
                    ->errors()
            )->toBe(['not exactly 2 elements']);
        });
    });

    context('->max', function () {
        it('accepts lists of the right length', function () {
            expect(
                (new Collection(new Boolean))
                    ->max(3)
                    ->validate([true, true, true])
                    ->errors()
            )->toBe([]);
        });

        it('rejects longer lists', function () {
            expect(
                (new Collection(new Boolean))
                    ->max(2)
                    ->validate([true, true, true])
                    ->errors()
            )->toBe(['not at most 2 elements']);
        });

        it('accepts shorter lists', function () {
            expect(
                (new Collection(new Boolean))
                    ->max(2)
                    ->validate([true])
                    ->errors()
            )->toBe([]);
        });
    });

    context('->min', function () {
        it('accepts lists of the right length', function () {
            expect(
                (new Collection(new Boolean))
                    ->min(3)
                    ->validate([true, true, true])
                    ->errors()
            )->toBe([]);
        });

        it('accepts lists too long', function () {
            expect(
                (new Collection(new Boolean))
                    ->min(2)
                    ->validate([true, true, true])
                    ->errors()
            )->toBe([]);
        });

        it('rejects lists too short', function () {
            expect(
                (new Collection(new Boolean))
                    ->min(2)
                    ->validate([true])
                    ->errors()
            )->toBe(['not at least 2 elements']);
        });
    });

    context('->ordered', function () {
        context('Without custom comparator', function () {
            context('Valid lists', function () {
                it('accepts an ordered string list', function () {
                    expect(
                        (new Collection(new Text))
                            ->ordered()
                            ->validate(['a', 'bat', 'cat'])
                            ->errors()
                    )->toBe([]);
                });

                it('accepts an ordered number list', function () {
                    expect(
                        (new Collection(new Integer))
                            ->ordered()
                            ->validate([1, 2, 4])
                            ->errors()
                    )->toBe([]);
                });
            });

            context('Invalid lists', function () {
                it('rejects an unordered string list', function () {
                    expect(
                        (new Collection(new Text))
                            ->ordered()
                            ->validate(['cat', 'ant', 'bat'])
                            ->errors()
                    )->toBe(['not ordered']);
                });

                it('rejects an unordered integer list', function () {
                    expect(
                        (new Collection(new Integer))
                            ->ordered()
                            ->validate([12, 8, 9])
                            ->errors()
                    )->toBe(['not ordered']);
                });
            });
        });

        context('With custom comparator', function () {
            $reverse = function ($a, $b) {
                return $b <=> $a;
            };

            context('Valid lists', function () use ($reverse) {
                it('accepts an ordered string list', function () use ($reverse) {
                    expect(
                        (new Collection(new Text))
                            ->ordered($reverse)
                            ->validate(['cat', 'bat', 'a'])
                            ->errors()
                    )->toBe([]);
                });

                it('accepts an ordered number list', function () use ($reverse) {
                    expect(
                        (new Collection(new Integer))
                            ->ordered($reverse)
                            ->validate([4, 2, 1])
                            ->errors()
                    )->toBe([]);
                });
            });

            context('Invalid lists', function () use ($reverse) {
                it('rejects an unordered string list', function () use ($reverse) {
                    expect(
                        (new Collection(new Text))
                            ->ordered($reverse)
                            ->validate(['cat', 'ant', 'bat'])
                            ->errors()
                    )->toBe(['not ordered']);
                });

                it('rejects an unordered integer list', function () use ($reverse) {
                    expect(
                        (new Collection(new Integer))
                            ->ordered($reverse)
                            ->validate([12, 8, 9])
                            ->errors()
                    )->toBe(['not ordered']);
                });
            });
        });
    });

    context('->unique', function () {
        it('accepts a list of unique values', function () {
            expect(
                (new Collection(new Integer))
                    ->unique()
                    ->validate([1, 2, 3, 4])
                    ->errors()
            )->toBe([]);
        });

        it('rejects a list with duplicate values', function () {
            expect(
                (new Collection(new Integer))
                    ->unique()
                    ->validate([1, 2, 3, 1, 4])
                    ->errors()
            )->toBe(['not all unique elements']);
        });
    });
});
