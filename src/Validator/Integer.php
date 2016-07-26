<?php

namespace Schemer\Validator;

/**
 * Integer validator.
 * This can just extend Real because PHP's type system is still a bit
 * of a mess, so integers will be cast for the real checks.
 */
class Integer extends Real
{
    /**
     * The integer must be positive.
     * @return Schemer\Validator\Internal The created validator.
     */
    public function __construct()
    {
        $this->restrictions = [
            self::strictPredicate('is_int', 'not an integer')
        ];
    }
}
