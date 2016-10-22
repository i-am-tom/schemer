<?php

namespace Schemer\Validator;

/**
 * Real validator.
 */
class Real extends ValidatorAbstract implements ValidatorInterface
{
    /**
     * The value must be a float.
     * @param string $error
     * @return Schemer\Validator\Real
     */
    public function __construct(string $error = 'not a float')
    {
        $this->restrictions = [
            self::strictPredicate('is_float', $error)
        ];
    }

    /**
     * The number must be exactly a given value.
     * @param float check
     * @param string $error
     * @return Schemer\Validator\Real
     */
    public function exactly(float $check, string $error = 'not exactly %d') : Real
    {
        return $this->pipe(
            self::predicate(
                function (float $value) use ($check) : bool {
                    return $value === $check;
                },
                sprintf($error, $check)
            )
        );
    }

    /**
     * The number must be at most a given value.
     * @param float $max
     * @param string $error
     * @return Schemer\Validator\Real
     */
    public function max(float $max, string $error = 'not at most %d') : Real
    {
        return $this->pipe(
            self::predicate(
                function (float $value) use ($max) : bool {
                    return $value <= $max;
                },
                sprintf($error, $max)
            )
        );
    }

    /**
     * The number must be at least a given value.
     * @param float $min
     * @param string $error
     * @return Schemer\Validator\Real
     */
    public function min(float $min, string $error = 'not at least %d') : Real
    {
        return $this->pipe(
            self::predicate(
                function (float $value) use ($min) : bool {
                    return $value >= $min;
                },
                sprintf($error, $min)
            )
        );
    }

    /**
     * The number must be negative (including zero).
     * @param string $error
     * @return Schemer\Validator\Real
     */
    public function negative(string $error = 'not at most %d')
    {
        return $this->max(0, $error);
    }

    /**
     * The number must be positive (including zero).
     * @param string $error
     * @return Schemer\Validator\Real
     */
    public function positive(string $error = 'not at least %d')
    {
        return $this->min(0, $error);
    }
}
