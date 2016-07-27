<?php

namespace Schemer\Test\Validator;

use Schemer\Validator\{Assoc,Integer};

describe(Assoc::class, function () {
    context('__construct', function () {
        it('accepts arrays', function () {
            expect(
                (new Assoc)
                    ->validate([])
                    ->errors()
            )->toBe([]);
        });

        it('rejects non-arrays', function () {
            expect(
                (new Assoc)
                    ->validate(true)
                    ->errors()
            )->toBe(['not an array']);
        });

        context('With a schema', function () {
            it('accepts schema-matching arrays', function () {
                $schema = [
                    'wins' => new Integer,
                    'losses' => new Integer
                ];

                expect(
                    (new Assoc($schema))
                        ->validate(['wins' => 12, 'losses' => 4])
                        ->errors()
                )->toBe([]);
            });

            it('rejects arrays missing keys', function () {
                $schema = [
                    'wins' => new Integer,
                    'losses' => new Integer
                ];

                expect(
                    (new Assoc($schema))
                        ->validate(['wins' => 12])
                        ->errors()
                )->toBe(['missing \'losses\'']);
            });

            it('rejects arrays with invalid values', function () {
                $schema = [
                    'wins' => new Integer,
                    'losses' => new Integer
                ];

                expect(
                    (new Assoc($schema))
                        ->validate(['wins' => 12, 'losses' => 'hi, mum'])
                        ->errors()
                )->toBe(['losses: not an integer']);
            });
        });
    });

    context('->length', function () {
        it('accepts arrays with the right element count', function () {
            expect(
                (new Assoc)
                    ->length(3)
                    ->validate(['a' => 1, 'b' => 2, 'c' => 3])
                    ->errors()
            )->toBe([]);
        });

        it('rejects arrays with the wrong element count', function () {
            expect(
                (new Assoc)
                    ->length(3)
                    ->validate(['a' => 1, 'b' => 2])
                    ->errors()
            )->toBe(['doesn\'t have exactly 3 entries']);
        });
    });

    context('->max', function () {
        it('rejects longer lists', function () {
            expect(
                (new Assoc)
                    ->max(2)
                    ->validate(['a' => 1, 'b' => 2, 'c' => 3])
                    ->errors()
            )->toBe(['has over 2 entries']);
        });

        it('accepts lists of that length', function () {
            expect(
                (new Assoc)
                    ->max(2)
                    ->validate(['a' => 1, 'b' => 2])
                    ->errors()
            )->toBe([]);
        });

        it('accepts shorter lengths', function () {
            expect(
                (new Assoc)
                    ->max(2)
                    ->validate(['a' => 1])
                    ->errors()
            )->toBe([]);
        });
    });

    context('->min', function () {
        it('accepts longer lists', function () {
            expect(
                (new Assoc)
                    ->min(2)
                    ->validate(['a' => 1, 'b' => 2, 'c' => 3])
                    ->errors()
            )->toBe([]);
        });

        it('accepts lists of that length', function () {
            expect(
                (new Assoc)
                    ->min(2)
                    ->validate(['a' => 1, 'b' => 2])
                    ->errors()
            )->toBe([]);
        });

        it('rejects shorter lengths', function () {
            expect(
                (new Assoc)
                    ->min(1)
                    ->validate([])
                    ->errors()
            )->toBe(['has under 1 entry']);
        });
    });

    context('->never', function () {
        it('accepts arrays without these keys', function () {
            expect(
                (new Assoc)
                    ->never(['a', 'b'])
                    ->validate(['c' => 2])
                    ->errors()
            )->toBe([]);
        });

        it('rejects arrays with these keys', function () {
            expect(
                (new Assoc)
                    ->never(['a', 'b'])
                    ->validate(['a' => 2])
                    ->errors()
            )->toBe(['contains \'a\'']);
        });
    });

    context('->optional', function () {
        it('allows absent optional values', function () {
            expect(
                (new Assoc)
                    ->optional(['a' => new Integer])
                    ->validate(['b' => 'test'])
                    ->errors()
            )->toBe([]);
        });

        it('allows valid optional values', function () {
            expect(
                (new Assoc)
                    ->optional(['a' => new Integer])
                    ->validate(['a' => 2])
                    ->errors()
            )->toBe([]);
        });

        it('rejects invalid optional values', function () {
            expect(
                (new Assoc)
                    ->optional(['a' => new Integer])
                    ->validate(['a' => 'test'])
                    ->errors()
            )->toBe(['a: not an integer']);
        });
    });

    context('->only', function () {
        it('allows keys within the list', function () {
            expect(
                (new Assoc)
                    ->only(['a', 'b'])
                    ->validate(['a' => 2])
                    ->errors()
            )->toBe([]);
        });

        it('rejects keys outside the list', function () {
            expect(
                (new Assoc)
                    ->only(['a', 'b'])
                    ->validate(['c' => 2])
                    ->errors()
            )->toBe(['contains \'c\'']);
        });
    });
});
