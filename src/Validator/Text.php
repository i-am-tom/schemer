<?php

namespace Schemer\Validator;

use Schemer\Result;
use Schemer\ValidationError;

/**
 * Text validator.
 */
class Text extends ValidatorAbstract
{
    /**
     * The value must be a string.
     */
    public function __construct()
    {
        $this->restrictions = [
            self::strictPredicate('is_string', 'not a string')
        ];
    }

    /**
     * Format a character count for printing.
     * @param int $count
     * @return string
     */
    private static function characterf(int $count) : string
    {
        return sprintf('%d character%s', $count, $count === 1 ? '' : 's');
    }

    /**
     * This text must be alphanumeric.
     * @return Schemer\Validator\Text
     */
    public function alphanum() : Text
    {
        return $this->pipe(self::predicate('ctype_alnum', 'not alphanumeric'));
    }

    /**
     * This string must be an email.
     * @return Schemer\Validator\Text
     */
    public function email(string $error = '') : Text
    {
        return $this->pipe(
            self::predicate(
                function (string $value) : bool {
                    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
                },
                new ValidationError(ValidationError::NOT_EMAIL, $error)
            )
        );
    }

    /**
     * This string must be a given length.
     * @param int $length The required string length.
     * @return Schemer\Validator\Text
     */
    public function length($length) : Text
    {
        return $this->pipe(
            self::predicate(
                function (string $value) use ($length) : bool {
                    return strlen($value) === $length;
                },
                'not exactly ' . self::characterf($length)
            )
        );
    }

    /**
     * This string must be lowercase.
     * @return Schemer\Validator\Text
     */
    public function lowercase() : Text
    {
        return $this->pipe(self::predicate('ctype_lower', 'not all lowercase'));
    }

    /**
     * This string must have at most a given number of characters.
     * @param int $length The maximum string length.
     * @return Schemer\Validator\Text
     */
    public function max(int $length) : Text
    {
        return $this->pipe(
            self::predicate(
                function (string $value) use ($length) : bool {
                    return strlen($value) <= $length;
                },
                'more than ' . self::characterf($length)
            )
        );
    }

    /**
     * This string must have at least a given number of characters.
     * @param int $length The minimum string length.
     * @return Schemer\Validator\Text
     */
    public function min(int $length) : Text
    {
        return $this->pipe(
            self::predicate(
                function (string $value) use ($length) : bool {
                    return strlen($value) >= $length;
                },
                'not at least ' . self::characterf($length)
            )
        );
    }

    /**
     * This string must match a given regular expression.
     * @param string $regex The regular expression to match.
     * @return Schemer\Validator\Text
     */
    public function regex(string $regex) : Text
    {
        return $this->pipe(
            self::predicate(
                function ($value) use ($regex) {
                    return preg_match($regex, $value) === 1;
                },
                "does not match $regex"
            )
        );
    }

    /**
     * This string must be upper case.
     * @return Schemer\Validator\Text
     */
    public function uppercase() : Text
    {
        return $this->pipe(self::predicate('ctype_upper', 'not all uppercase'));
    }
}
