<?php

namespace Schemer\Test;

use Schemer\Schemer;
use Schemer\Validator\{
    Any,
    Assoc,
    Boolean,
    Collection,
    Integer,
    Real,
    Text
};

class Test {}

describe(Schemer::class, function () {
    context('::any', function () {
        it('constructs an Any instance', function () {
            expect(Schemer::any())->toBeAnInstanceOf(Any::class);
        });

        context('Type-checking', function () {
            it('allows a boolean type', function () {
                expect(Schemer::any()->validate(true)->errors())->toBe([]);
            });

            it('allows a real type', function () {
                expect(Schemer::any()->validate(1.0)->errors())->toBe([]);
            });

            it('allows a string type', function () {
                expect(Schemer::any()->validate('hello')->errors())->toBe([]);
            });
        });
    });

    context('::allow', function () {
        it('constructs an Any instance', function () {
            expect(
                Schemer::allow(['test', 1.0, true, []]))
                    ->toBeAnInstanceOf(Any::class);
        });

        context('Valid checking', function () {
            it('matches strings', function () {
                expect(
                    Schemer::allow(['test', 1.0, true, []])
                        ->validate('test')
                        ->errors()
                )->toBe([]);
            });

            it('matches floats', function () {
                expect(
                    Schemer::allow(['test', 1.0, true, []])
                        ->validate(1.0)
                        ->errors()
                )->toBe([]);
            });

            it('matches booleans', function () {
                expect(
                    Schemer::allow(['test', 1.0, true, []])
                        ->validate(true)
                        ->errors()
                )->toBe([]);
            });

            it('matches arrays', function () {
                expect(
                    Schemer::allow(['test', 1.0, true, []])
                        ->validate([])
                        ->errors()
                )->toBe([]);
            });
        });

        context('Invalid checking', function () {
            it('rejects bad strings', function () {
                expect(
                    Schemer::allow(['test', 1.0, true, []])
                        ->validate('toast')
                        ->errors()
                )->toBe(['not in the whitelist']);
            });

            it('rejects bad floats', function () {
                expect(
                    Schemer::allow(['test', 1.0, true, []])
                        ->validate(2.0)
                        ->errors()
                )->toBe(['not in the whitelist']);
            });

            it('rejects bad booleans', function () {
                expect(
                    Schemer::allow(['test', 1.0, true, []])
                        ->validate(false)
                        ->errors()
                )->toBe(['not in the whitelist']);
            });

            it('rejects bad arrays', function () {
                expect(
                    Schemer::allow(['test', 1.0, true, []])
                        ->validate(['le gib'])
                        ->errors()
                )->toBe(['not in the whitelist']);
            });
        });
    });

    context('::assoc', function () {
        it('constructs an Assoc instance', function () {
            expect(
                Schemer::assoc(['test' => Schemer::boolean()])
            )->toBeAnInstanceOf(Assoc::class);
        });

        context('Property validation', function () {
            it('validates single properties', function () {
                expect(
                    Schemer::assoc(['test' => Schemer::boolean()])
                        ->validate(['test' => true])
                        ->errors()
                )->toBe([]);
            });

            it('Returns failures', function () {
                expect(
                    Schemer::assoc(['test' => Schemer::boolean()])
                        ->validate(['test' => 'hello'])
                        ->errors()
                )->toBe(['test: not a boolean']);
            });
        });

        context('Failing top-level validation', function () {
            it('fatals for non-arrays', function () {
                expect(
                    Schemer::assoc(['test' => Schemer::boolean()])
                        ->validate(2)
                        ->isFatal()
                )->toBe(true);
            });

            it('returns errors', function () {
                expect(
                    Schemer::assoc(['test' => Schemer::boolean()])
                        ->validate(2)
                        ->errors()
                )->toBe(['not an array']);
            });
        });
    });

    context('::boolean', function () {
        it('constructs a Boolean instance', function () {
            expect(Schemer::boolean())->toBeAnInstanceOf(Boolean::class);
        });

        context('Valid values', function () {
            it('accepts a true value', function () {
                expect(
                    Schemer::boolean()
                        ->validate(true)
                        ->errors()
                )->toBe([]);
            });

            it('accepts a false value', function () {
                expect(
                    Schemer::boolean()
                        ->validate(false)
                        ->errors()
                )->toBe([]);
            });
        });

        context('Invalid values', function () {
            it('rejects a float', function () {
                expect(
                    Schemer::boolean()
                        ->validate(1.0)
                        ->errors()
                )->toBe(['not a boolean']);
            });

            it('rejects a string', function () {
                expect(
                    Schemer::boolean()
                        ->validate('hello')
                        ->errors()
                )->toBe(['not a boolean']);
            });
        });
    });

    context('::collection', function () {
        it('constructs a Collection instance', function () {
            expect(
                Schemer::collection(Schemer::text())
            )->toBeAnInstanceOf(Collection::class);
        });

        context('Valid values', function () {
            it('accepts an empty array', function () {
                expect(
                    Schemer::collection(Schemer::text())
                        ->validate([])
                        ->errors()
                )->toBe([]);
            });

            it('accepts a non-empty array', function () {
                expect(
                    Schemer::collection(Schemer::text())
                        ->validate(['test', 'blah'])
                        ->errors()
                )->toBe([]);
            });
        });

        context('Invalid values', function () {
            it('rejects a bad value at index 0', function () {
                expect(
                    Schemer::collection(Schemer::text())
                        ->validate([2])
                        ->errors()
                )->toBe(['not a string at index 0']);
            });

            it('rejects a bad value at non-zero index', function () {
                expect(
                    Schemer::collection(Schemer::text())
                        ->validate(['', '', true])
                        ->errors()
                )->toBe(['not a string at index 2']);
            });
        });
    });

    context('::instanceOf', function () {
        it('constructs an Any instance', function () {
            expect(
                Schemer::instanceOf(Test::class)
            )->toBeAnInstanceOf(Any::class);
        });

        context('Validation', function () {
            it('accepts classes of the correct type', function () {
                expect(
                    Schemer::instanceOf(Test::class)
                        ->validate(new Test)
                        ->errors()
                )->toBe([]);
            });

            it('rejects classes of the wrong type', function () {
                expect(
                    Schemer::instanceOf(Test::class)
                        ->validate(new \stdclass)
                        ->errors()
                )->toBe(['not an instance of Schemer\Test\Test']);
            });

            it('rejects non-objects', function () {
                expect(
                    Schemer::instanceOf(Test::class)
                        ->validate(1.0)
                        ->errors()
                )->toBe(['not an object']);
            });
        });
    });

    context('::integer', function () {
        it('constructs an Integer instance', function () {
            expect(
                Schemer::integer()
            )->toBeAnInstanceOf(Integer::class);
        });

        context('Validation', function () {
            it('accepts integers', function () {
                expect(
                    Schemer::integer()
                        ->validate(2)
                        ->errors()
                )->toBe([]);
            });

            it('rejects non-integers', function () {
                expect(
                    Schemer::integer()
                        ->validate('test')
                        ->errors()
                )->toBe(['not an integer']);
            });
        });
    });

    context('::oneOf', function () {
        it('constructs an Any instance', function () {
            expect(Schemer::oneOf([]))->toBeAnInstanceOf(Any::class);
        });

        context('Empty validator lists', function () {
            it('fails for truthy values', function () {
                expect(
                    Schemer::oneOf([])
                        ->validate(1)
                        ->errors()
                )->toBe(['matches none of the options']);
            });

            it('fails for truthy values', function () {
                expect(
                    Schemer::oneOf([])
                        ->validate(0)
                        ->errors()
                )->toBe(['matches none of the options']);
            });
        });

        context('Non-empty validator lists', function () {
            it('fails for invalid values', function () {
                expect(
                    Schemer::oneOf([Schemer::text()])
                        ->validate(2.0)
                        ->errors()
                )->toBe(['matches none of the options']);
            });

            it('passes for valid values', function () {
                expect(
                    Schemer::oneOf([Schemer::text()])
                        ->validate('test')
                        ->errors()
                )->toBe([]);
            });
        });
    });

    context('::real', function () {
        it('constructs an Real instance', function () {
            expect(
                Schemer::real()
            )->toBeAnInstanceOf(Real::class);
        });

        context('Validation', function () {
            it('accepts real', function () {
                expect(
                    Schemer::real()
                        ->validate(2.5)
                        ->errors()
                )->toBe([]);
            });

            it('rejects non-reals', function () {
                expect(
                    Schemer::real()
                        ->validate('test')
                        ->errors()
                )->toBe(['not a float']);
            });
        });
    });

    context('::reject', function () {
        it('constructs an Any instance', function () {
            expect(
                Schemer::reject(['test', 1.0, true, []]))
                    ->toBeAnInstanceOf(Any::class);
        });

        context('Valid checking', function () {
            it('matches strings', function () {
                expect(
                    Schemer::reject(['test', 1.0, true, []])
                        ->validate('toast')
                        ->errors()
                )->toBe([]);
            });

            it('matches floats', function () {
                expect(
                    Schemer::reject(['test', 1.0, true, []])
                        ->validate(2.0)
                        ->errors()
                )->toBe([]);
            });

            it('matches booleans', function () {
                expect(
                    Schemer::reject(['test', 1.0, true, []])
                        ->validate(false)
                        ->errors()
                )->toBe([]);
            });

            it('matches arrays', function () {
                expect(
                    Schemer::reject(['test', 1.0, true, []])
                        ->validate(['le gib'])
                        ->errors()
                )->toBe([]);
            });
        });

        context('Invalid checking', function () {
            it('rejects bad strings', function () {
                expect(
                    Schemer::reject(['test', 1.0, true, []])
                        ->validate('test')
                        ->errors()
                )->toBe(['in the blacklist']);
            });

            it('rejects bad floats', function () {
                expect(
                    Schemer::reject(['test', 1.0, true, []])
                        ->validate(1.0)
                        ->errors()
                )->toBe(['in the blacklist']);
            });

            it('rejects bad booleans', function () {
                expect(
                    Schemer::reject(['test', 1.0, true, []])
                        ->validate(true)
                        ->errors()
                )->toBe(['in the blacklist']);
            });

            it('rejects bad arrays', function () {
                expect(
                    Schemer::reject(['test', 1.0, true, []])
                        ->validate([])
                        ->errors()
                )->toBe(['in the blacklist']);
            });
        });
    });

    context('::text', function () {
        it('constructs a Text instance', function () {
            expect(Schemer::text())->toBeAnInstanceOf(Text::class);
        });

        context('Validation', function () {
            it('allows strings', function () {
                expect(
                    Schemer::text()
                        ->validate('hello')
                        ->errors()
                )->toBe([]);
            });

            it('rejects non-strings', function () {
                expect(
                    Schemer::text()
                        ->validate(2.0)
                        ->errors()
                )->toBe(['not a string']);
            });
        });
    });
});
