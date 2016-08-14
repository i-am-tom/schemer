<?php

namespace Schemer\Test\Formatter;

use Schemer\Formatter\Collection;
use Schemer\Formatter\Integer;
use Schemer\Formatter\Text;

describe(Collection::class, function () {
    context('Unaltered', function () {
        it('waives valid arrays', function () {
            expect(
                (new Collection(new Text))
                    ->format(['h', 'i'])
            )->toBe(['h', 'i']);
        });

        it('formats elements', function () {
            expect(
                (new Collection(new Text))
                    ->format([false, 3.5, 'hello'])
            )->toBe(['', '3.5', 'hello']);
        });

        it('casts single elements', function () {
            expect(
                (new Collection(new Text))
                    ->format(62)
            )->toBe(['62']);
        });
    });

    context('->sort', function () {
        context('Without comparator', function () {
            it('waives sorted arrays', function () {
                expect(
                    (new Collection(new Integer))
                        ->sort()
                        ->format([1, 2, 3])
                )->toBe([1, 2, 3]);
            });

            it('sorts the unsorted', function () {
                expect(
                    (new Collection(new Integer))
                        ->sort()
                        ->format([5, 1, 3])
                )->toBe([1, 3, 5]);
            });
        });

        context('With comparator', function () {
            it('waives sorted arrays', function () {
                expect(
                    (new Collection(new Integer))
                        ->sort(function ($a, $b) {
                            return $b <=> $a;
                        })
                        ->format([5, 3, 1])
                )->toBe([5, 3, 1]);
            });

            it('sorts the unsorted', function () {
                expect(
                    (new Collection(new Integer))
                        ->sort(function ($a, $b) {
                            return $b <=> $a;
                        })
                        ->format([6, 8, 2, 4])
                )->toBe([8, 6, 4, 2]);
            });
        });
    });

    context('->truncate', function () {
        it('waives arrays within limits', function () {
            expect(
                (new Collection(new Text))
                    ->truncate(5)
                    ->format(['Philip', 'J', 'Fry'])
            )->toBe(['Philip', 'J', 'Fry']);
        });

        it('restricts arrays to the limit', function () {
            expect(
                (new Collection(new Text))
                    ->truncate(2)
                    ->format(['James', 'T', 'Kirk'])
            )->toBe(['James', 'T']);
        });
    });

    context('->unique', function () {
        it('waives arrays with unique elements', function () {
            expect(
                (new Collection(new Integer))
                    ->unique()
                    ->format([1, 2, 3, 4, 5])
            )->toBe([1, 2, 3, 4, 5]);
        });

        it('strips duplicates', function () {
            expect(
                (new Collection(new Integer))
                    ->unique()
                    ->format([1, 1, 3, 1, 4, 2, 3])
            )->toBe([1, 3, 4, 2]);
        });
    });
});
