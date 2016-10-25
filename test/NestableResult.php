<?php

namespace Schemer\Test;

use Schemer\NestableResult;
use Schemer\Result;

describe(NestableResult::class, function () {
    context('__construct', function () {
        context('Result', function () {
            it('holds a successful Result', function () {
                expect(
                    (new NestableResult(Result::success(), []))
                        ->errors()
                )->toBe([]);
            });

            it('holds a failing Result', function () {
                expect(
                    (new NestableResult(Result::failure('test'), []))
                        ->errors()
                )->toBe(['test']);
            });
        });

        context('Values', function () {
            it('holds inner Results', function () {
                $values = [
                    Result::success(),
                    Result::failure('test')
                ];

                expect(
                    (new NestableResult(Result::success(), $values))
                        ->getIterator()->getArrayCopy()
                )->toBe($values);
            });
        });
    });

    context('->concat', function () {
        context('Inner', function () {
            it('concatenates successes', function () {
                expect(
                    (new NestableResult(Result::success(), []))
                        ->concat(new NestableResult(Result::success(), []))
                        ->errors()
                )->toBe([]);
            });

            it('concatenates failures', function () {
                expect(
                    (new NestableResult(Result::failure('lol'), []))
                        ->concat(new NestableResult(Result::failure('wut'), []))
                        ->errors()
                )->toBe(['lol', 'wut']);
            });

            it('concatenates success with failure', function () {
                expect(
                    (new NestableResult(Result::success(), []))
                        ->concat(new NestableResult(
                            Result::failure('Tracy Chapman'), []
                        ))
                        ->errors()
                )->toBe(['Tracy Chapman']);
            });

            it('concatenates failure with success', function () {
                expect(
                    (new NestableResult(Result::success(), []))
                        ->concat(new NestableResult(
                            Result::failure('Chacy Trapman'), []
                        ))
                        ->errors()
                )->toBe(['Chacy Trapman']);
            });
        });

        context('Outer', function () {
            it('merges associatives', function () {
                $crusher = Result::failure('Crusher');
                $troi = Result::failure('Troi');

                expect(
                    (new NestableResult(
                        Result::success(),
                        ['Deanna' => $troi]
                    ))->concat(
                        new NestableResult(
                            Result::success(),
                            ['Wesley' => $crusher]
                        )
                    )->inner()
                )->toBe([
                    'Deanna' => $troi,
                    'Wesley' => $crusher
                ]);
            });

            it('merges sequentials', function () {
                $wesley = Result::failure('Wesley');
                $crusher = Result::failure('Crusher');

                expect(
                    (new NestableResult(
                        Result::success(),
                        [$wesley, $crusher]
                    ))->concat(
                        new NestableResult(
                            Result::success(),
                            [Result::failure('Beverly')]
                        )
                    )->inner()
                )->toBe([$wesley, $crusher]);
            });
        });
    });

    context('::failure', function () {
        it('generates a failure', function () {
            expect(NestableResult::failure('Riff')->errors())
                ->toBe(['Riff']);
        });
    });

    context('::fatal', function () {
        it('generates a fatal', function () {
            expect(
                NestableResult::fatal('Clichard')
                    ->outer()
                    ->isFatal()
            )->toBe(true);
        });
    });

    context('->getIterator', function () {
        it('returns the inner array iterator', function () {
            expect(
                iterator_to_array(
                    (new NestableResult(
                        Result::success(),
                        [ 1, 2, 3, 4, 5 ]
                    ))->getIterator()
                )
            )->toBe([1, 2, 3, 4, 5]);
        });
    });

    context('->inner', function () {
        it('returns the inner array', function () {
            expect(
                (new NestableResult(
                    Result::success(),
                    [ 1, 2, 3, 4, 5 ]
                ))->inner()
            )->toBe([1, 2, 3, 4, 5]);
        });
    });

    context('->isError', function () {
        it('rejects success', function () {
            expect(
                (new NestableResult(
                    Result::success(), []
                ))->isError()
            )->toBe(false);
        });

        it('rejects failures', function () {
            expect(
                (new NestableResult(
                    Result::failure('Eek'), []
                ))->isError()
            )->toBe(true);
        });

        it('rejects nested failure', function () {
            expect(
                (new NestableResult(
                    Result::success(),
                    [Result::failure('Ook')]
                ))->isError()
            )->toBe(true);
        });
    });

    context('->isFatal', function () {
        it('rejects success', function () {
            expect(
                (new NestableResult(
                    Result::success(), []
                ))->isFatal()
            )->toBe(false);
        });

        it('rejects failures', function () {
            expect(
                (new NestableResult(
                    Result::fatal('Aak'), []
                ))->isFatal()
            )->toBe(true);
        });

        it('rejects nested failure', function () {
            expect(
                (new NestableResult(
                    Result::success(),
                    [Result::fatal('Ukulele')]
                ))->isFatal()
            )->toBe(true);
        });
    });

    context('::lift', function () {
        it('lifts a successful Result', function () {
            expect(
                NestableResult::lift(Result::success())
                    ->errors()
            )->toBe([]);
        });

        it('lifts an unsuccessful Result', function () {
            expect(
                NestableResult::lift(Result::failure('test'))
                    ->errors()
            )->toBe(['test']);
        });
    });

    context('->map', function () {
        it('maps over the outer', function () {
            expect(
                (new NestableResult(
                    Result::failure('Guinan'), []
                ))->map('strtoupper')->errors()
            )->toBe(['GUINAN']);
        });
    });

    context('->offsetExists', function () {
        context('Sequential', function () {
            it('returns true for valid offsets', function () {
                expect(
                    (new NestableResult(
                        Result::success(),
                        [ 0, 1, 2 ]
                    ))->offsetExists(2)
                )->toBe(true);
            });

            it('returns false for invalid offsets', function () {
                expect(
                    (new NestableResult(
                        Result::success(),
                        [ 0, 1, 2 ]
                    ))->offsetExists(3)
                )->toBe(false);
            });
        });

        context('Associative', function () {
            it('returns true for valid offsets', function () {
                expect(
                    (new NestableResult(
                        Result::success(),
                        [ 'Janet' => 'Jackson' ]
                    ))->offsetExists('Janet')
                )->toBe(true);
            });

            it('returns false for invalid offsets', function () {
                expect(
                    (new NestableResult(
                        Result::success(),
                        [ 'Janis' => 'Joplin' ]
                    ))->offsetExists('Janet')
                )->toBe(false);
            });
        });
    });

    context('->offsetGet', function () {
        context('Sequential', function () {
            it('returns the value at the offset', function () {
                expect(
                    (new NestableResult(
                        Result::success(),
                        [ 0, 1, 4 ]
                    ))->offsetGet(2)
                )->toBe(4);
            });
        });

        context('Associative', function () {
            it('returns the value at the offset', function () {
                expect(
                    (new NestableResult(
                        Result::success(),
                        [ 'Janet' => 'Jackson' ]
                    ))->offsetGet('Janet')
                )->toBe('Jackson');
            });
        });
    });

    context('->offsetSet', function () {
        context('Sequential', function () {
            it('disallows mutation', function () {
                expect(
                    function () {
                        (new NestableResult(
                            Result::success(),
                            []
                        ))->offsetSet(0, 'Janet');
                    }
                )->toThrow(new \BadMethodCallException);
            });
        });

        context('Associative', function () {
            it('disallows mutation', function () {
                expect(
                    function () {
                        (new NestableResult(
                            Result::success(),
                            []
                        ))->offsetSet('Janis', 'Jacklin');
                    }
                )->toThrow(new \BadMethodCallException);
            });
        });
    });

    context('->offsetUnset', function () {
        context('Sequential', function () {
            it('disallows mutation', function () {
                expect(
                    function () {
                        (new NestableResult(
                            Result::success(),
                            []
                        ))->offsetUnset(0);
                    }
                )->toThrow(new \BadMethodCallException);
            });
        });

        context('Associative', function () {
            it('disallows mutation', function () {
                expect(
                    function () {
                        (new NestableResult(
                            Result::success(),
                            []
                        ))->offsetUnset('Janis');
                    }
                )->toThrow(new \BadMethodCallException);
            });
        });
    });

    context('::success', function () {
        it('constructs a successful NestableResult', function () {
            expect(NestableResult::success()->isError())->toBe(false);
        });
    });
});
