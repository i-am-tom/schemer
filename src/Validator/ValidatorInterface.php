<?php

namespace Schemer\Validator;

use Schemer\Result;

/**
 * Interface for all Schemer validators.
 */
interface ValidatorInterface
{
    /**
     * Validate a value against this Schemer instance.
     * @param mixed $value The variable to validate.
     * @return Schemer\Result The result of the validation.
     */
    public function validate($value) : Result;
}
