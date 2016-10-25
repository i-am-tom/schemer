<?php

namespace Schemer;

/**
 * A nestable result structure.
 * This structure defines both errors on the outer structure and any
 * errors that may have been found within the inner values.
 */
class NestableResult implements \ArrayAccess, \IteratorAggregate
{
    /**
     * The errors on the errors (container) value.
     * @var Schemer\Result
     */
    private $outer = null;

    /**
     * The errors on the inner values.
     * @var array An array of Results and NestableResults.
     */
    private $inner = [];

    /**
     * Populate the NestableResult object.
     * @param Result $errors The errors of the whole structure.
     * @param array $values The errors of the values within.
     */
    public function __construct(Result $errors, array $inner)
    {
        $this->outer = $errors;
        $this->inner = $inner;
    }

    /**
     * Join two results together, combining their errors.
     * @param Schemer\NestableResult $that
     * @return Schemer\NestableResult
     */
    public function concat(NestableResult $that) : NestableResult
    {
        if (!$this->isError()) {
            return $that;
        }

        if (!$that->isError()) {
            return $this;
        }

        $result = new NestableResult(
            $this->outer->concat($that->outer),
            $this->inner + $that->inner // Overwrite for sequential arrays.
        );

        $result->fatal = $this->isFatal() || $that->isFatal();
        return $result; // Fatals are not recoverable.
    }

    /**
     * Get the errors on the errors structure.
     * @return Result
     */
    public function errors() : array
    {
        return $this->outer->errors();
    }

    /**
     * Return a validation failure.
     * @param string $error
     * @return Schemer\NestableResult
     */
    public static function failure(string $error) : NestableResult
    {
        return new self(Result::failure($error), []);
    }

    /**
     * Return a failure, stop checking this branch.
     * @param string $error
     * @return Schemer\NestableResult
     */
    public static function fatal(string $error) : NestableResult
    {
        return new self(Result::fatal($error), []);
    }

    /**
     * Get the inner array for foreach syntax.
     * @return Traversable The inner array.
     */
    public function getIterator() : \Traversable
    {
        return new \ArrayIterator($this->inner);
    }

    /**
     * Get the inner Result array.
     * @return array
     */
    public function inner() : array
    {
        return $this->inner;
    }

    /**
     * Are there any errors within this result?
     * @return boolean
     */
    public function isError() : bool
    {
        if ($this->outer->isError()) {
            return true;
        }

        foreach ($this as $inner) {
            if ($inner->isError()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Are there any fatals within this result?
     * @return boolean
     */
    public function isFatal() : bool
    {
        if ($this->outer->isFatal()) {
            return true;
        }

        foreach ($this as $inner) {
            if ($inner->isFatal()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create a NestableResult from a Result.
     * @param Result $error
     * @return Schemer\NestableResult
     */
    public static function lift(Result $error) : NestableResult
    {
        return new NestableResult($error, []);
    }

    /**
     * Transform every error within this result.
     * @param callable $f
     * @return Schemer\Result
     */
    public function map(callable $f) : NestableResult
    {
        return new self(
            $this->outer->map($f),
            array_map($f, $this->inner)
        );
    }

   /**
     * Check for the existence of an index.
     * @param mixed $offset Int or string
     * @return Result
     */
    public function offsetExists($offset) : bool
    {
        return isset($this->inner[$offset]);
    }

    /**
     * Get the error at a particular index.
     * @param int|string $offset Int or string
     * @return Schemer\Result|Schemer\NestedResult
     */
    public function offsetGet($offset)
    {
        return $this->inner[$offset];
    }

    /**
     * @param int|string $offset
     * @param mixed $value
     * @throws BadMethodCallException THis is mutation!
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException(
            'Mutation is disallowed'
        );
    }

    /**
     * @param mixed $offset
     * @throws BadMethodCallException This is mutation!
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException(
            'Mutation is disallowed'
        );
    }

    /**
     * Get the outer result.
     * @return Schemer\Result
     */
    public function outer() : Result
    {
        return $this->outer;
    }

    /**
     * Return a validation success.
     * @return Schemer\NestableResult
     */
    public static function success() : NestableResult
    {
        return new self(Result::success(), []);
    }
}
