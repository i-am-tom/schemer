<?php

namespace Schemer;

/**
 * A validation result.
 */
class Result
{
    /**
     * The errors within this result.
     * @var array
     */
    private $errors = [];

    /**
     * Is this a fatal error?
     * @var bool
     */
    private $fatal = false;

    /**
     * Create a new result with the given errors.
     * @param array $errors
     */
    private function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Join two results together, combining their errors.
     * @param Result $that
     * @return Schemer\Result
     */
    public function concat(Result $that) : Result
    {
        if (!$this->isError()) {
            return $that;
        }

        if (!$that->isError()) {
            return $this;
        }

        $result = new Result(
            array_merge(
                $this->errors(),
                $that->errors()
            )
        );

        $result->fatal = $this->fatal || $that->fatal;
        return $result; // Fatals are not recoverable.
    }

    /**
     * Get the errors for this result.
     * @return array
     */
    public function errors() : array
    {
        return $this->errors;
    }

    /**
     * Return a validation failure.
     * @param string $error
     * @return Schemer\Result
     */
    public static function failure(string $error) : Result
    {
        return new self([$error]);
    }

    /**
     * Return a failure, stop checking this branch.
     * @param string $error
     * @return Schemer\Result
     */
    public static function fatal(string $error) : Result
    {
        $result = new self([$error]);
        $result->fatal = true;

        return $result;
    }

    /**
     * Are there any errors in this result?
     * @return boolean
     */
    public function isError() : bool
    {
        return !empty($this->errors);
    }

    /**
     * Is this a fatal error?
     * @return boolean
     */
    public function isFatal() : bool
    {
        return $this->fatal;
    }

    /**
     * Transform every error within this result.
     * @param callable $f [description]
     * @return Schemer\Result
     */
    public function map(callable $f) : Result
    {
        $result = new self(array_map($f, $this->errors));
        $result->fatal = $this->fatal;

        return $result;
    }

    /**
     * Return a validation success.
     * @return Schemer\Result
     */
    public static function success() : Result
    {
        return new self([]);
    }
}
