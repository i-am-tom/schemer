<?php

namespace Schemer\Validator;

/**
 * Boolean validator.
 */
class Boolean extends ValidatorAbstract implements ValidatorInterface
{
    /**
     * The value must be a boolean.
     * @param string $errorMessage
     */
    public function __construct($errorMessage = 'not a boolean')
    {
        $this->restrictions = [
            self::strictPredicate('is_bool', $errorMessage)
        ];
    }

    /**
     * The value must be true.
     * @param string $errorMessage
     * @return Schemer\Validator\Boolean
     */
    public function true($errorMessage = 'not true') : Boolean
    {
        return $this->pipe(
            self::predicate(
                function (bool $bool) : bool {
                    return !!$bool;
                },
                $errorMessage
            )
        );
    }

    /**
     * The value must be false.
     * @param string $errorMessage
     * @return Schemer\Validator\Boolean
     */
    public function false($errorMessage = 'not false') : Boolean
    {
        return $this->pipe(
            self::predicate(
                function (bool $bool) : bool {
                    return !$bool;
                },
                $errorMessage
            )
        );
    }
}
