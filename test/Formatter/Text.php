<?php

namespace Schemer\Test\Formatter;

use Schemer\Formatter\Text;

describe(Text::class, function () {
    context('Unaltered', function () {
        it('waives string values', function () {
            expect((new Text)->format('hello'))->toBe('hello');
        });

        it('casts non-string values', function () {
            expect((new Text)->format(135))->toBe('135');
        });
    });

    context('->lowercase', function () {
        it('waives lowercase string', function () {
            expect(
                (new Text)
                    ->lowercase()
                    ->format('hello')
            )->toBe('hello');
        });

        it('converts strings to lowercase', function () {
            expect(
                (new Text)
                    ->lowercase()
                    ->format('YMCA')
            )->toBe('ymca');
        });
    });

    context('->replace', function () {
        it('waives strings without matches', function () {
            expect(
                (new Text)
                    ->replace('/buffalo/', 'giraffe')
                    ->format('dog')
            )->toBe('dog');
        });

        it('performs replacements', function () {
            expect(
                (new Text)
                    ->replace('/AT/', 'POM')
                    ->format('AT-AT')
            )->toBe('POM-POM');
        });
    });

    context('->translate', function () {
        it('waives unmatched strings', function () {
            expect(
                (new Text)
                    ->translate('a', 'b')
                    ->format('bilge')
            )->toBe('bilge');
        });

        it('translates matched strings', function () {
            expect(
                (new Text)
                    ->translate('abc', 'def')
                    ->format('abc leppard')
            )->toBe('def leppdrd');
        });
    });

    context('->trim', function () {
        context('Default', function () {
            it('waives unpadded strings', function () {
                expect(
                    (new Text)
                        ->trim()
                        ->format('to be again')
                )->toBe('to be again');
            });

            it('trims padded strings', function () {
                expect(
                    (new Text)
                        ->trim()
                        ->format(" doctor?\t")
                )->toBe('doctor?');
            });
        });

        context('Custom mask', function () {
            it('waives un"padded" strings', function () {
                expect(
                    (new Text)
                        ->trim('i')
                        ->format('Kirk')
                )->toBe('Kirk');
            });

            it('trims "padded" strings', function () {
                expect(
                    (new Text)
                        ->trim('h')
                        ->format('henoch')
                )->toBe('enoc');
            });
        });
    });

    context('->truncate', function () {
        it('waives strings within limit', function () {
            expect(
                (new Text)
                    ->truncate(500)
                    ->format('humanoid robots')
            )->toBe('humanoid robots');
        });

        it('truncates strings too long', function () {
            expect(
                (new Text)
                    ->truncate(10)
                    ->format('I know him now')
            )->toBe('I know him');
        });
    });

    context('->uppercase', function () {
        it('waives uppercase string', function () {
            expect(
                (new Text)
                    ->uppercase()
                    ->format('HOLA')
            )->toBe('HOLA');
        });

        it('converts strings to uppercase', function () {
            expect(
                (new Text)
                    ->uppercase()
                    ->format('cat')
            )->toBe('CAT');
        });
    });
});
