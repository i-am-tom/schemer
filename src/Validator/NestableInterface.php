<?php

namespace Schemer\Validator;

use Schemer\NestableResult;

/**
 * Interface for all nestable validators.
 */
interface NestableInterface extends ValidatorInterface
{
    /**
     * Validate a value, and maintain structure.
     * @param mixed $value The variable to validate.
     * @return stdClass Has keys for "errors" (for outer errors) and
     *                  "values" (for inner values).
     */
    public function nestedValidate($value) : NestableResult;
}
