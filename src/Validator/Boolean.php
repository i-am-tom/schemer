<?php

namespace Schemer\Validator;

/**
 * Boolean validator.
 */
class Boolean extends ValidatorAbstract implements ValidatorInterface
{
    /**
     * The value must be a boolean.
     * @param string $error
     */
    public function __construct(string $error = 'not a boolean')
    {
        $this->restrictions = [
            self::strictPredicate('is_bool', $error)
        ];
    }

    /**
     * The value must be true.
     * @param string $error
     * @return Schemer\Validator\Boolean
     */
    public function true(string $error = 'not true') : Boolean
    {
        return $this->pipe(
            self::predicate(
                function (bool $bool) : bool {
                    return !!$bool;
                },
                $error
            )
        );
    }

    /**
     * The value must be false.
     * @param string $error
     * @return Schemer\Validator\Boolean
     */
    public function false(string $error = 'not false') : Boolean
    {
        return $this->pipe(
            self::predicate(
                function (bool $bool) : bool {
                    return !$bool;
                },
                $error
            )
        );
    }
}
