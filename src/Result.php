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
    public function concat(Result $that)
    {
        if (!$this->isError()) {
            return $that;
        }

        if (!$that->isError()) {
            return $this;
        }


        return new Result(
            array_merge(
                $this->errors(),
                $that->errors()
            )
        );
    }

    /**
     * Return a validation failure.
     * @param string $error
     * @return Result
     */
    public static function failure(string $error) : Result
    {
        return new self([$error]);
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
     * Get the errors for this result.
     * @return array
     */
    public function errors() : array
    {
        return $this->errors;
    }

    /**
     * Transform every error within this result.
     * @param callable $f [description]
     * @return Schemer\Result
     */
    public function map(callable $f) : Result
    {
        return new self(array_map($f, $this->errors));
    }

    /**
     * Return a validation success.
     * @return Result
     */
    public static function success() : Result
    {
        return new self([]);
    }
}
