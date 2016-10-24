<?php

namespace Schemer\Validator;

/**
 * Boolean validator.
 */
class Boolean extends ValidatorAbstract
{
    /**
     * The value must be a boolean.
     */
    public function __construct()
    {
        $this->restrictions = [
            self::strictPredicate('is_bool', 'not a boolean')
        ];
    }

    /**
     * The value must be true.
     * @return Schemer\Validator\Boolean
     */
    public function true() : Boolean
    {
        return $this->pipe(
            self::predicate(
                function (bool $bool) : bool {
                    return !!$bool;
                },
                'not true'
            )
        );
    }

    /**
     * The value must be false.
     * @return Schemer\Validator\Boolean
     */
    public function false() : Boolean
    {
        return $this->pipe(
            self::predicate(
                function (bool $bool) : bool {
                    return !$bool;
                },
                'not false'
            )
        );
    }
}
