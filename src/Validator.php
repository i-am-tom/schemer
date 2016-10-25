<?php

namespace Schemer;

use Schemer\Validator;

/**
 * The validation entry point.
 */
class Validator
{
    /**
     * The value can be anything.
     * @return Schema\Validator\Any
     */
    public static function any() : Validator\Any
    {
        return new Validator\Any;
    }

    /**
     * The value must be on a given whitelist.
     * @param array $whitelist The allowed values.
     * @return Schemer\Validator\Any
     */
    public static function allow(array $whitelist) : Validator\Any
    {
        return self::any()->should(
            function ($value) use ($whitelist) : bool {
                foreach ($whitelist as $candidate) {
                    if ($candidate === $value) {
                        return true;
                    }
                }

                return false;
            },
            'not in the whitelist'
        );
    }

    /**
     * The value must be an associative array.
     * @param array $schema The schema against which to validate.
     * @return Schemer\Validator\Assoc
     */
    public static function assoc(array $schema) : Validator\Assoc
    {
        return new Validator\Assoc($schema);
    }

    /**
     * The value must be a boolean.
     * @return Schemer\Validator\Boolean
     */
    public static function boolean() : Validator\Boolean
    {
        return new Validator\Boolean;
    }

    /**
     * The value must be a collection of a given type.
     * @param Schemer\Validator\ValidatorInterface $validator
     * @return Schemer\Validator\Collection
     */
    public static function collection(
        Validator\ValidatorInterface $validator
    ) : Validator\Collection {
        return new Validator\Collection($validator);
    }

    /**
     * The value must be an instance of the given class.
     * @param string $comparison
     * @return Schemer\Validator\Any
     */
    public static function instanceOf(string $comparison) : Validator\Any
    {
        return self::any()
            ->must('is_object', 'not an object')
            ->must(
                function ($value) use ($comparison) : bool {
                    return $value instanceof $comparison;
                },
                "not an instance of $comparison"
            );
    }

    /**
     * The value must be an integer.
     * @return Schemer\Validator\Integer
     */
    public static function integer() : Validator\Integer
    {
        return new Validator\Integer;
    }

    /**
     * The value must match one of a given set of validators.
     * @param array $validators The possible validators to use.
     * @return Schemer\Validator\Any
     */
    public static function oneOf(array $validators) : Validator\Any
    {
        return self::any()->should(
            function ($value) use ($validators) : bool {
                foreach ($validators as $validator) {
                    if (!$validator->validate($value)->isError()) {
                        return true;
                    }
                }

                return false;
            },
            'matches none of the options'
        );
    }

    /**
     * The value must be a float.
     * @return Schemer\Validator\Real
     */
    public static function real() : Validator\Real
    {
        return new Validator\Real;
    }

    /**
     * The value must not be on a given blacklist.
     * @param array $blacklist The forbidden values.
     * @return Schemer\Validator\Any
     */
    public static function reject(array $blacklist) : Validator\Any
    {
        return self::any()->should(
            function ($value) use ($blacklist) : bool {
                foreach ($blacklist as $candidate) {
                    if ($candidate === $value) {
                        return false;
                    }
                }

                return true;
            },
            'in the blacklist'
        );
    }

    /**
     * Create a string validator.
     * @return Schemer\Validator\Text
     */
    public static function text() : Validator\Text
    {
        return new Validator\Text;
    }
}
