<?php

namespace Schemer\Validator;

/**
 * Real validator.
 */
class Real extends ValidatorAbstract implements ValidatorInterface
{
    /**
     * The value must be a float.
     * @param string $errorMessage
     * @return Schemer\Validator\Real
     */
    public function __construct(string $errorMessage = 'not a float')
    {
        $this->restrictions = [
            self::strictPredicate('is_float', $errorMessage)
        ];
    }

    /**
     * The number must be exactly a given value.
     * @param float check
     * @param string $errorMessage
     * @return Schemer\Validator\Real
     */
    public function exactly(float $check, string $errorMessage = 'not exactly %d') : Real
    {
        return $this->pipe(
            self::predicate(
                function (float $value) use ($check) : bool {
                    return $value === $check;
                },
                sprintf($errorMessage, $check)
            )
        );
    }

    /**
     * The number must be at most a given value.
     * @param float $max
     * @param string $errorMessage
     * @return Schemer\Validator\Real
     */
    public function max(float $max, string $errorMessage = 'not at most %d') : Real
    {
        return $this->pipe(
            self::predicate(
                function (float $value) use ($max) : bool {
                    return $value <= $max;
                },
                sprintf($errorMessage, $max)
            )
        );
    }

    /**
     * The number must be at least a given value.
     * @param float $min
     * @param string $errorMessage
     * @return Schemer\Validator\Real
     */
    public function min(float $min, string $errorMessage = 'not at least %d') : Real
    {
        return $this->pipe(
            self::predicate(
                function (float $value) use ($min) : bool {
                    return $value >= $min;
                },
                sprintf($errorMessage, $min)
            )
        );
    }

    /**
     * The number must be negative (including zero).
     * @param string $errorMessage
     * @return Schemer\Validator\Real
     */
    public function negative(string $errorMessage = 'not at most %d')
    {
        return $this->max(0, $errorMessage);
    }

    /**
     * The number must be positive (including zero).
     * @param string $errorMessage
     * @return Schemer\Validator\Real
     */
    public function positive(string $errorMessage = 'not at least %d')
    {
        return $this->min(0, $errorMessage);
    }
}
