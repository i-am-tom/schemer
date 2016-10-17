<?php

namespace Schemer\Test\Validator;

use Schemer\Validator\Text;

describe(Text::class, function () {
    context('__construct', function () {
        it('accepts strings values', function () {
            expect(
                (new Text)
                    ->validate('agile bear')
                    ->errors()
            )->toBe([]);
        });

        it('rejects non-strings', function () {
            expect(
                (new Text)
                    ->validate(true)
                    ->errors()
            )->toBe(['not a string']);
        });
    });

    context('->alphanum', function () {
        context('Valid', function () {
            it('accepts all-character strings', function () {
                expect(
                    (new Text)
                        ->alphanum()
                        ->validate('abcdefghijklmnopqrstuvwxyz')
                        ->errors()
                )->toBe([]);
            });

            it('accepts all-digit strings', function () {
                expect(
                    (new Text)
                        ->alphanum()
                        ->validate('1234567890')
                        ->errors()
                )->toBe([]);
            });

            it('accepts a mix', function () {
                expect(
                    (new Text)
                        ->alphanum()
                        ->validate('4g1l3b34r')
                        ->errors()
                )->toBe([]);
            });
        });

        it('rejects non-alphanumeric strings', function () {
            expect(
                (new Text)
                    ->alphanum()
                    ->validate('Look at this punctuation!')
                    ->errors()
            )->toBe(['not alphanumeric']);
        });
    });

    context('->email', function () {
        it('accepts valid email strings', function () {
            expect(
                (new Text)
                    ->email()
                    ->validate('test@toast.com')
                    ->errors()
            )->toBe([]);
        });

        it('rejects invalid strings', function () {
            expect(
                (new Text)
                    ->email()
                    ->validate('test.com')
                    ->errors()
            )->toBe(['not an email']);
        });
    });

    context('->length', function () {
        it('accepts strings of the correct length', function () {
            expect(
                (new Text)
                    ->length(5)
                    ->validate('abcde')
                    ->errors()
            )->toBe([]);
        });

        it('rejects strings too long', function () {
            expect(
                (new Text)
                    ->length(5)
                    ->validate('abcdef')
                    ->errors()
            )->toBe(['not exactly 5 characters']);
        });

        it('rejects strings too short', function () {
            expect(
                (new Text)
                    ->length(5)
                    ->validate('')
                    ->errors()
            )->toBe(['not exactly 5 characters']);
        });
    });

    context('->lowercase', function () {
        it('rejects strings that are not lowercase', function () {
            expect(
                (new Text)
                    ->lowercase()
                    ->validate('Hello')
                    ->errors()
            )->toBe(['not all lowercase']);
        });

        it('accepts strings that are lowercase', function () {
            expect(
                (new Text)
                    ->lowercase()
                    ->validate('hello')
                    ->errors()
            )->toBe([]);
        });

        it('rejects strings that are not exclusively lowercase', function () {
            expect(
                (new Text)
                    ->lowercase()
                    ->validate('hello!')
                    ->errors()
            )->toBe(['not all lowercase']);
        });
    });

    context('->max', function () {
        it('rejects strings that are too long', function () {
            expect(
                (new Text)
                    ->max(1)
                    ->validate('Hello')
                    ->errors()
            )->toBe(['more than 1 character']);
        });

        it('accepts strings that are shorter', function () {
            expect(
                (new Text)
                    ->max(10)
                    ->validate('hello')
                    ->errors()
            )->toBe([]);
        });

        it('accepts strings that are the exact length', function () {
            expect(
                (new Text)
                    ->max(6)
                    ->validate('hello!')
                    ->errors()
            )->toBe([]);
        });
    });

    context('->min', function () {
        it('accepts strings that are longer', function () {
            expect(
                (new Text)
                    ->min(4)
                    ->validate('Hello')
                    ->errors()
            )->toBe([]);
        });

        it('rejects strings that are shorter', function () {
            expect(
                (new Text)
                    ->min(10)
                    ->validate('hello')
                    ->errors()
            )->toBe(['not at least 10 characters']);
        });

        it('accepts strings that are the exact length', function () {
            expect(
                (new Text)
                    ->min(6)
                    ->validate('hello!')
                    ->errors()
            )->toBe([]);
        });
    });

    context('->regex', function () {
        it('accepts strings that match the regex', function () {
            expect(
                (new Text)
                    ->regex('/.+/')
                    ->validate('hhh')
                    ->errors()
            )->toBe([]);
        });

        it('rejects strings that don\'t', function () {
            expect(
                (new Text)
                    ->regex('/[a-z]/')
                    ->validate('123')
                    ->errors()
            )->toBe(['does not match /[a-z]/']);
        });
    });

    context('->uppercase', function () {
        it('rejects strings that are not uppercase', function () {
            expect(
                (new Text)
                    ->uppercase()
                    ->validate('Hello')
                    ->errors()
            )->toBe(['not all uppercase']);
        });

        it('accepts strings that are uppercase', function () {
            expect(
                (new Text)
                    ->uppercase()
                    ->validate('ORVAH')
                    ->errors()
            )->toBe([]);
        });

        it('rejects strings that are not exclusively uppercase', function () {
            expect(
                (new Text)
                    ->uppercase()
                    ->validate('HEYNO!')
                    ->errors()
            )->toBe(['not all uppercase']);
        });
    });
});
