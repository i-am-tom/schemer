<?php

namespace Schemer\Formatter;

/**
 * Boolean formatter.
 */
class Boolean extends FormatterAbstract
{
    /**
     * The value will be a boolean.
     */
    public function __construct()
    {
        $this->transformations = [
            function ($value) : bool {
                return 'false' === $value ? false : (bool) $value;
            }
        ];
    }
}
