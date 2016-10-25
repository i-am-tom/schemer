<?php

namespace Schemer\Validator;

use Schemer\Result;

/**
 * A catch-all validator.
 */
class Any extends ValidatorAbstract
{
    /**
     * Create a new any-type validator.
     */
    public function __construct()
    {
        $this->restrictions = [];
    }
}
