<?php

namespace Schemer\Test\Validator;

use Schemer\Validator\Assoc;
use Schemer\Validator\Integer;

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
                )->toBe(['losses: missing key']);
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

    context('->nestedValidate', function () {
        it('validates single-level arrays', function () {
            expect(
                array_map(
                    function ($result) {
                        return $result->errors();
                    },
                    iterator_to_array(
                        (new Assoc([
                            'a' => new Integer,
                            'b' => new Integer
                        ]))->nestedValidate([
                            'a' => 3,
                            'b' => 'Chimamanda'
                        ])
                    )
                )
            )->toBe([
                'a' => [],
                'b' => ['not an integer']
            ]);
        });

        context('nested arrays', function () {
            $validator = new Assoc([
                'Ishara' => new Assoc([
                    'Tasha' => new Integer
                ]),
                'Yar' => new Assoc
            ]);

            it('validates the outer container', function () use ($validator) {
                $results = $validator->nestedValidate([
                    'Ishara' => [
                        'Tasha' => 'Wat'
                    ],
                    'Yar' => 2
                ]);

                expect($results->outer()->errors())->toBe([]);
            });

            it('validates nested values', function () use ($validator) {
                $results = $validator->nestedValidate([
                    'Ishara' => [
                        'Tasha' => 'Wat'
                    ],
                    'Yar' => 2
                ]);

                expect(
                    $results['Ishara']['Tasha']
                        ->errors()
                )->toBe(['not an integer']);
            });

            it('validates inner nestables', function () use ($validator) {
                $results = $validator->nestedValidate([
                    'Ishara' => [
                        'Tasha' => 'Wat'
                    ],
                    'Yar' => 2
                ]);

                expect($results['Yar']->errors())->toBe(['not an array']);
            });
        });
    });

    context('->validate', function () {
        it('validates single-level associative arrays', function () {
            expect(
                (new Assoc(['a' => new Integer]))
                    ->validate(['a' => 'Worf'])
                    ->errors()
            )->toBe(['a: not an integer']);
        });

        context('nested arrays', function () {
            $validator = new Assoc([
                'Riley' => new Assoc([
                    'J' => new Integer
                ]),
                'Dennis' => new Assoc
            ]);

            it('validates the outer container', function () use ($validator) {
                expect($validator->validate([])->errors())->toBe([
                    'Riley: missing key', 'Dennis: missing key'
                ]);
            });

            it('validates nested values', function () use ($validator) {
                expect(
                    $validator
                        ->validate([
                            'Riley' => [
                                'J' => '???'
                            ],
                            'Dennis' => '!!!'
                        ])
                        ->errors()
                )->toBe([
                    'Riley: J: not an integer',
                    'Dennis: not an array'
                ]);
            });
        });
    });
});
