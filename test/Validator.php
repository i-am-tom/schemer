<?php

namespace Schemer\Test;

use Schemer\Validator;
use Schemer\Validator\Any;
use Schemer\Validator\Assoc;
use Schemer\Validator\Boolean;
use Schemer\Validator\Collection;
use Schemer\Validator\Integer;
use Schemer\Validator\Real;
use Schemer\Validator\Text;

use stdClass;

describe(Validator::class, function () {
    context('::any', function () {
        it('constructs an Any instance', function () {
            expect(Validator::any())->toBeAnInstanceOf(Any::class);
        });

        context('Type-checking', function () {
            it('allows a boolean type', function () {
                expect(Validator::any()->validate(true)->errors())->toBe([]);
            });

            it('allows a real type', function () {
                expect(Validator::any()->validate(1.0)->errors())->toBe([]);
            });

            it('allows a string type', function () {
                expect(Validator::any()->validate('hello')->errors())->toBe([]);
            });
        });
    });

    context('::allow', function () {
        it('constructs an Any instance', function () {
            expect(
                Validator::allow(['test', 1.0, true, []])
            )->toBeAnInstanceOf(Any::class);
        });

        context('Valid checking', function () {
            it('matches strings', function () {
                expect(
                    Validator::allow(['test', 1.0, true, []])
                        ->validate('test')
                        ->errors()
                )->toBe([]);
            });

            it('matches floats', function () {
                expect(
                    Validator::allow(['test', 1.0, true, []])
                        ->validate(1.0)
                        ->errors()
                )->toBe([]);
            });

            it('matches booleans', function () {
                expect(
                    Validator::allow(['test', 1.0, true, []])
                        ->validate(true)
                        ->errors()
                )->toBe([]);
            });

            it('matches arrays', function () {
                expect(
                    Validator::allow(['test', 1.0, true, []])
                        ->validate([])
                        ->errors()
                )->toBe([]);
            });
        });

        context('Invalid checking', function () {
            it('rejects bad strings', function () {
                expect(
                    Validator::allow(['test', 1.0, true, []])
                        ->validate('toast')
                        ->errors()
                )->toBe(['not in the whitelist']);
            });

            it('rejects bad floats', function () {
                expect(
                    Validator::allow(['test', 1.0, true, []])
                        ->validate(2.0)
                        ->errors()
                )->toBe(['not in the whitelist']);
            });

            it('rejects bad booleans', function () {
                expect(
                    Validator::allow(['test', 1.0, true, []])
                        ->validate(false)
                        ->errors()
                )->toBe(['not in the whitelist']);
            });

            it('rejects bad arrays', function () {
                expect(
                    Validator::allow(['test', 1.0, true, []])
                        ->validate(['le gib'])
                        ->errors()
                )->toBe(['not in the whitelist']);
            });
        });
    });

    context('::assoc', function () {
        it('constructs an Assoc instance', function () {
            expect(
                Validator::assoc(['test' => Validator::boolean()])
            )->toBeAnInstanceOf(Assoc::class);
        });

        context('Property validation', function () {
            it('validates single properties', function () {
                expect(
                    Validator::assoc(['test' => Validator::boolean()])
                        ->validate(['test' => true])
                        ->errors()
                )->toBe([]);
            });

            it('Returns failures', function () {
                expect(
                    Validator::assoc(['test' => Validator::boolean()])
                        ->validate(['test' => 'hello'])
                        ->errors()
                )->toBe(['test: not a boolean']);
            });
        });

        context('Failing top-level validation', function () {
            it('fatals for non-arrays', function () {
                expect(
                    Validator::assoc(['test' => Validator::boolean()])
                        ->validate(2)
                        ->isFatal()
                )->toBe(true);
            });

            it('returns errors', function () {
                expect(
                    Validator::assoc(['test' => Validator::boolean()])
                        ->validate(2)
                        ->errors()
                )->toBe(['not an array']);
            });
        });
    });

    context('::boolean', function () {
        it('constructs a Boolean instance', function () {
            expect(Validator::boolean())->toBeAnInstanceOf(Boolean::class);
        });

        context('Valid values', function () {
            it('accepts a true value', function () {
                expect(
                    Validator::boolean()
                        ->validate(true)
                        ->errors()
                )->toBe([]);
            });

            it('accepts a false value', function () {
                expect(
                    Validator::boolean()
                        ->validate(false)
                        ->errors()
                )->toBe([]);
            });
        });

        context('Invalid values', function () {
            it('rejects a float', function () {
                expect(
                    Validator::boolean()
                        ->validate(1.0)
                        ->errors()
                )->toBe(['not a boolean']);
            });

            it('rejects a string', function () {
                expect(
                    Validator::boolean()
                        ->validate('hello')
                        ->errors()
                )->toBe(['not a boolean']);
            });
        });
    });

    context('::collection', function () {
        it('constructs a Collection instance', function () {
            expect(
                Validator::collection(Validator::text())
            )->toBeAnInstanceOf(Collection::class);
        });

        context('Valid values', function () {
            it('accepts an empty array', function () {
                expect(
                    Validator::collection(Validator::text())
                        ->validate([])
                        ->errors()
                )->toBe([]);
            });

            it('accepts a non-empty array', function () {
                expect(
                    Validator::collection(Validator::text())
                        ->validate(['test', 'blah'])
                        ->errors()
                )->toBe([]);
            });
        });

        context('Invalid values', function () {
            it('rejects a bad value at index 0', function () {
                expect(
                    Validator::collection(Validator::text())
                        ->validate([2])
                        ->errors()
                )->toBe(['0: not a string']);
            });

            it('rejects a bad value at non-zero index', function () {
                expect(
                    Validator::collection(Validator::text())
                        ->validate(['', '', true])
                        ->errors()
                )->toBe(['2: not a string']);
            });
        });
    });

    context('::instanceOf', function () {
        it('constructs an Any instance', function () {
            expect(
                Validator::instanceOf(stdClass::class)
            )->toBeAnInstanceOf(Any::class);
        });

        context('Validation', function () {
            it('accepts classes of the correct type', function () {
                expect(
                    Validator::instanceOf(stdClass::class)
                        ->validate(new stdClass)
                        ->errors()
                )->toBe([]);
            });

            it('rejects classes of the wrong type', function () {
                expect(
                    Validator::instanceOf(stdClass::class)
                        ->validate(new Any)
                        ->errors()
                )->toBe(['not an instance of stdClass']);
            });

            it('rejects non-objects', function () {
                expect(
                    Validator::instanceOf(stdClass::class)
                        ->validate(1.0)
                        ->errors()
                )->toBe(['not an object']);
            });
        });
    });

    context('::integer', function () {
        it('constructs an Integer instance', function () {
            expect(
                Validator::integer()
            )->toBeAnInstanceOf(Integer::class);
        });

        context('Validation', function () {
            it('accepts integers', function () {
                expect(
                    Validator::integer()
                        ->validate(2)
                        ->errors()
                )->toBe([]);
            });

            it('rejects non-integers', function () {
                expect(
                    Validator::integer()
                        ->validate('test')
                        ->errors()
                )->toBe(['not an integer']);
            });
        });
    });

    context('::oneOf', function () {
        it('constructs an Any instance', function () {
            expect(Validator::oneOf([]))->toBeAnInstanceOf(Any::class);
        });

        context('Empty validator lists', function () {
            it('fails for truthy values', function () {
                expect(
                    Validator::oneOf([])
                        ->validate(1)
                        ->errors()
                )->toBe(['matches none of the options']);
            });

            it('fails for truthy values', function () {
                expect(
                    Validator::oneOf([])
                        ->validate(0)
                        ->errors()
                )->toBe(['matches none of the options']);
            });
        });

        context('Non-empty validator lists', function () {
            it('fails for invalid values', function () {
                expect(
                    Validator::oneOf([Validator::text()])
                        ->validate(2.0)
                        ->errors()
                )->toBe(['matches none of the options']);
            });

            it('passes for valid values', function () {
                expect(
                    Validator::oneOf([Validator::text()])
                        ->validate('test')
                        ->errors()
                )->toBe([]);
            });
        });
    });

    context('::real', function () {
        it('constructs a Real instance', function () {
            expect(
                Validator::real()
            )->toBeAnInstanceOf(Real::class);
        });

        context('Validation', function () {
            it('accepts real', function () {
                expect(
                    Validator::real()
                        ->validate(2.5)
                        ->errors()
                )->toBe([]);
            });

            it('rejects non-reals', function () {
                expect(
                    Validator::real()
                        ->validate('test')
                        ->errors()
                )->toBe(['not a float']);
            });
        });
    });

    context('::reject', function () {
        it('constructs an Any instance', function () {
            expect(
                Validator::reject(['test', 1.0, true, []])
            )->toBeAnInstanceOf(Any::class);
        });

        context('Valid checking', function () {
            it('matches strings', function () {
                expect(
                    Validator::reject(['test', 1.0, true, []])
                        ->validate('toast')
                        ->errors()
                )->toBe([]);
            });

            it('matches floats', function () {
                expect(
                    Validator::reject(['test', 1.0, true, []])
                        ->validate(2.0)
                        ->errors()
                )->toBe([]);
            });

            it('matches booleans', function () {
                expect(
                    Validator::reject(['test', 1.0, true, []])
                        ->validate(false)
                        ->errors()
                )->toBe([]);
            });

            it('matches arrays', function () {
                expect(
                    Validator::reject(['test', 1.0, true, []])
                        ->validate(['le gib'])
                        ->errors()
                )->toBe([]);
            });
        });

        context('Invalid checking', function () {
            it('rejects bad strings', function () {
                expect(
                    Validator::reject(['test', 1.0, true, []])
                        ->validate('test')
                        ->errors()
                )->toBe(['in the blacklist']);
            });

            it('rejects bad floats', function () {
                expect(
                    Validator::reject(['test', 1.0, true, []])
                        ->validate(1.0)
                        ->errors()
                )->toBe(['in the blacklist']);
            });

            it('rejects bad booleans', function () {
                expect(
                    Validator::reject(['test', 1.0, true, []])
                        ->validate(true)
                        ->errors()
                )->toBe(['in the blacklist']);
            });

            it('rejects bad arrays', function () {
                expect(
                    Validator::reject(['test', 1.0, true, []])
                        ->validate([])
                        ->errors()
                )->toBe(['in the blacklist']);
            });
        });
    });

    context('::text', function () {
        it('constructs a Text instance', function () {
            expect(Validator::text())->toBeAnInstanceOf(Text::class);
        });

        context('Validation', function () {
            it('allows strings', function () {
                expect(
                    Validator::text()
                        ->validate('hello')
                        ->errors()
                )->toBe([]);
            });

            it('rejects non-strings', function () {
                expect(
                    Validator::text()
                        ->validate(2.0)
                        ->errors()
                )->toBe(['not a string']);
            });
        });
    });
});
