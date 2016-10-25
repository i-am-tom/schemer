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
     * @return Schemer\NestableResult
     */
    public function nestedValidate($value) : NestableResult;
}
