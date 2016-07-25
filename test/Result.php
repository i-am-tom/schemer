<?php

namespace Schemer\Test;

use Schemer\Result;

describe(Result::class, function () {
    it('constructs a success', function () {
        $success = Result::success();

        expect($success->isError())->toBe(false);
        expect($success->errors())->toBe([]);
    });

    it('constructs a failure', function () {
        $failure = Result::failure('test');

        expect($failure->isError())->toBe(true);
        expect($failure->errors())->toBe(['test']);
    });

    it('concatenates success', function () {
        $test = Result::success()
            ->concat(Result::success());

        expect($test->errors())->toBe([]);
        expect($test->isError())->toBe(false);
    });

    it('concatenates failures', function () {
        $test = Result::failure('a')
            ->concat(Result::failure('b'));

        expect($test->errors())->toBe(['a', 'b']);
        expect($test->isError())->toBe(true);
    });

    it('concatenates mixtures', function () {
        $test1 = Result::success()
            ->concat(Result::failure('b'));

        expect($test1->errors())->toBe(['b']);
        expect($test1->isError())->toBe(true);

        $test2 = Result::failure('b')
            ->concat(Result::success());

        expect($test2->errors())->toBe(['b']);
        expect($test2->isError())->toBe(true);
    });

    it('maps over success', function () {
        $test = Result::success()->map('strtoupper');

        expect($test->errors())->toBe([]);
        expect($test->isError())->toBe(false);
    });

    it('maps over failure', function () {
        $test = Result::failure('a')->map('strtoupper');

        expect($test->errors())->toBe(['A']);
        expect($test->isError())->toBe(true);
    });

    it('maps over failures', function () {
        $test = Result::failure('a')
            ->concat(Result::failure('b'))
            ->map('strtoupper');

        expect($test->errors())->toBe(['A', 'B']);
        expect($test->isError())->toBe(true);
    });
});
