<?php

namespace Schemer\Validator;

use Schemer\Result;
use Schemer\NestableResult;

/**
 * The parent class for all validators.
 */
abstract class NestableAbstract extends ValidatorAbstract implements
    NestableInterface
{
    /**
     * Create a structure-mimicking error object.
     * @param mixed $value The value to validate.
     * @return stdClass [errors => Result, values => [...]]
     */
    protected function validateSimple($value) : Result
    {
        return parent::validate($value);
    }

    /**
     * Return a standard Result after validation.
     * @param mixed $value The value to validate.
     * @return Result The errors, if any.
     */
    public function validate($value) : Result
    {
        return self::unnest(static::nestedValidate($value));
    }

    /**
     * Collapse a nested error into a standard Result object.
     * @param array $errors The nested error set.
     * @return Result The unnested Result object.
     */
    protected static function unnest(NestableResult $struct) : Result
    {
        $result = $struct->outer;

        foreach ($struct->inner as $key => $error) {
            if ($error instanceof NestableResult) {
                $error = self::unnest($error);
            }

            $result = $result->concat(
                $error->map(function ($e) use ($key) {
                    return $key . ': ' . $e;
                })
            );
        }

        return $result;
    }
}
