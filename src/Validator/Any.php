<?php

namespace Schemer\Validator;

use Schemer\Result;

/**
 * A catch-all validator.
 */
class Any extends ValidatorAbstract implements ValidatorInterface
{
    /**
     * Create a new any-type validator.
     */
    public function __construct()
    {
        $this->restrictions = [
            self::predicate(
                function ($_) : bool {
                    return true;
                },
                ''
            )
        ];
    }

    /**
     * Create an any-type validator with a caveat :-)
     * @param callable $f
     * @return Schemer\Validator\Any
     */
    public function but(callable $f) : Any
    {
        return $this->pipe($f);
    }
}
