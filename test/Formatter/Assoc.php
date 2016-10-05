<?php

namespace Schemer\Test\Formatter;

use Schemer\Formatter\Assoc;
use Schemer\Formatter\Text;

describe(Assoc::class, function () {
    context('Unaltered', function () {
        context('Without schema', function () {
            it('waives arrays', function () {
                expect((new Assoc)->format([]))->toBe([]);
            });

            it('casts non-array values', function () {
                expect((new Assoc)->format(1))->toBe([1]);
            });
        });

        context('With schema', function () {
            it('waives matching arrays', function () {
                expect(
                    (new Assoc(['test' => new Text]))
                        ->format(['test' => 'hi'])
                )->toBe(['test' => 'hi']);
            });

            it('formats present keys', function () {
                expect(
                    (new Assoc(['test' => new Text]))
                        ->format(['test' => 3])
                )->toBe(['test' => '3']);
            });

            it('falls back for absent keys', function () {
                expect(
                    (new Assoc(['test' => new Text]))
                        ->format(['Leonard' => 'McCoy'])
                )->toBe(['Leonard' => 'McCoy', 'test' => '']);
            });
        });
    });

    context('->only', function () {
        it('waives acceptable arrays', function () {
            expect(
                (new Assoc)
                    ->only(['Jim', 'Leonard'])
                    ->format(['Jim' => 'Kirk'])
            )->toBe(['Jim' => 'Kirk']);
        });

        it('strips keys outside the whitelist', function () {
            expect(
                (new Assoc)
                    ->only(['Jim', 'Leonard'])
                    ->format(['Mr' => 'Spock'])
            )->toBe([]);
        });
    });

    context('->rename', function () {
        it('waives arrays without given keys', function () {
            expect(
                (new Assoc)
                    ->rename('starship', 'enterprise')
                    ->format(['Jean-Luc' => 'Picard'])
            )->toBe(['Jean-Luc' => 'Picard']);
        });

        it('renames the given array keys', function () {
            expect(
                (new Assoc)
                    ->rename('blue steel', 'phasers')
                    ->format(['blue steel' => 'stun'])
            )->toBe(['phasers' => 'stun']);
        });

        it('overwrites existing targets', function () {
            expect(
                (new Assoc)
                    ->rename('USS', 'starship')
                    ->format([
                        'USS' => 'enterprise',
                        'starship' => 'farragut'
                    ])
            )->toBe(['starship' => 'enterprise']);
        });
    });

    context('->renameMany', function () {
        it('waives arrays without given keys', function () {
            expect(
                (new Assoc)
                    ->renameMany([
                        'starship' => 'enterprise',
                        'starboard' => 'pork pies'
                    ])
                    ->format(['Jean-Luc' => 'Picard'])
            )->toBe(['Jean-Luc' => 'Picard']);
        });

        it('renames the given array keys', function () {
            expect(
                (new Assoc)
                    ->renameMany([
                        'blue steel' => 'phasers',
                        'geordi' => 'Mr La Forge'
                    ])
                    ->format([
                        'blue steel' => 'stun',
                        'geordi' => 'engage'
                    ])
            )->toBe([
                'phasers' => 'stun',
                'Mr La Forge' => 'engage'
            ]);
        });

        it('overwrites existing targets in order', function () {
            expect(
                (new Assoc)
                    ->renameMany([
                        'USS' => 'starship',
                        'wat' => 'starship'
                    ])
                    ->format([
                        'USS' => 'enterprise',
                        'starship' => 'farragut',
                        'wat' => 'WE BUILT THIS CITY'
                    ])
            )->toBe(['starship' => 'WE BUILT THIS CITY']);
        });
    });

    context('->strip', function () {
        it('waives arrays without given keys', function () {
            expect(
                (new Assoc)
                    ->strip(['space'])
                    ->format(['starship' => 'enterprise'])
            )->toBe(['starship' => 'enterprise']);
        });

        it('strips given keys', function () {
            expect(
                (new Assoc)
                    ->strip(['space'])
                    ->format(['space' => 'The final frontier'])
            )->toBe([]);
        });
    });
});
