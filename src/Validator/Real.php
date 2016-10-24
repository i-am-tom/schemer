<?php

namespace Schemer\Validator;

/**
 * Real validator.
 */
class Real extends ValidatorAbstract
{
    /**
     * The value must be a float.
     * @return Schemer\Validator\Real
     */
    public function __construct()
    {
        $this->restrictions = [
            self::strictPredicate('is_float', 'not a float')
        ];
    }

    /**
     * The number must be exactly a given value.
     * @return Schemer\Validator\Real
     */
    public function exactly(float $check) : Real
    {
        return $this->pipe(
            self::predicate(
                function (float $value) use ($check) : bool {
                    return $value === $check;
                },
                "not exactly $check"
            )
        );
    }

    /**
     * The number must be at most a given value.
     * @return Schemer\Validator\Real
     */
    public function max(float $max) : Real
    {
        return $this->pipe(
            self::predicate(
                function (float $value) use ($max) : bool {
                    return $value <= $max;
                },
                "not at most $max"
            )
        );
    }

    /**
     * The number must be at least a given value.
     * @return Schemer\Validator\Real
     */
    public function min(float $min) : Real
    {
        return $this->pipe(
            self::predicate(
                function (float $value) use ($min) : bool {
                    return $value >= $min;
                },
                "not at least $min"
            )
        );
    }

    /**
     * The number must be negative (including zero).
     * @return Schemer\Validator\Real
     */
    public function negative()
    {
        return $this->max(0);
    }

    /**
     * The number must be positive (including zero).
     * @return Schemer\Validator\Real
     */
    public function positive()
    {
        return $this->min(0);
    }
}
