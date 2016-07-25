<?php

namespace Schemer\Validator;

use Schemer\Result;

/**
 * The parent class for all validators.
 */
class ValidatorAbstract implements ValidatorInterface
{
    /**
     * The currently-applied restrictions.
     * @var array callable
     */
    protected $restrictions = [];

    /**
     * Add another validator to the restrictions.
     * @param callable $validator The validator to add.
     * @return Schemer\Validator\ValidatorAbstract A new validator.
     */
    protected function pipe(callable $validator) : ValidatorAbstract
    {
        $that = clone $this; // Immutability!
        array_push($that->restrictions, $validator);

        return $that;
    }

    /**
     * Create a restriction from a boolean predicate.
     * @param callable $predicate True/false-yielding function.
     * @param string $error The failure error to use.
     * @return callable A restriction to pipe.
     */
    public static function predicate(
        callable $predicate,
        string $error
    ) : callable {
        return function ($value) use ($predicate, $error) : Result {
            return $predicate ($value)
                ? Result::success()
                : Result::failure($error);
        };
    }

    /**
     * Execute the validation functions.
     * @param $value The value to validate.
     * @return Schemer\Result The validation result.
     */
    public function validate($value) : Result
    {
        return array_reduce(
            $this->restrictions,
            function ($result, $restriction) use ($value) : Result {
                return $result->concat($restriction($value));
            },
            Result::success()
        );
    }
}
