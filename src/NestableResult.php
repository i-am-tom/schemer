<?php

namespace Schemer;

/**
 * A nestable result structure.
 * This structure defines both errors on the outer structure and any
 * errors that may have been found within the inner values.
 */
class NestableResult
{
    /**
     * The errors on the outer (container) value.
     * @var Schemer\Result
     */
    public $outer = null;

    /**
     * The errors on the inner values.
     * @var array An array of Results and NestableResults.
     */
    public $inner = [];

    /**
     * Populate the NestableResult object.
     * @param Result $errors The errors of the whole structure.
     * @param array $values The errors of the values within.
     */
    public function __construct(Result $outer, array $inner)
    {
        $this->outer = $outer;
        $this->inner = $inner;
    }

    /**
     * Create a NestableResult from a Result.
     * @param Result $error
     * @return NestableResult
     */
    public static function lift(Result $error) : NestableResult
    {
        return new NestableResult($error, []);
    }
}
