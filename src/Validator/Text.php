<?php

namespace Schemer\Validator;

use Schemer\Result;

/**
 * Text validator.
 */
class Text extends ValidatorAbstract implements ValidatorInterface
{
    /**
     * The value must be a string.
     */
    public function __construct()
    {
        $this->restrictions = [ self::predicate('is_string', 'not a string') ];
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
    public function email() : Text
    {
        return $this->pipe(
            self::predicate(
                function (string $value) : bool {
                    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
                },
                "not an email"
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
                "not exactly $length characters"
            )
        );
    }

    /**
     * This string must be lowercase.
     * @return Schemer\Validator\Text
     */
    public function lowercase() : Text
    {
        return $this->pipe(self::predicate('ctype_lower', 'not lowercase'));
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
                "more than $length characters"
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
                "not at least $length characters"
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
                    return preg_match($regex, $value) !== false;
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
        return $this->pipe(self::predicate('ctype_upper', 'not uppercase'));
    }
}
