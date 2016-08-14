<?php

namespace Schemer\Formatter;

/**
 * Boolean formatter.
 */
class Boolean extends FormatterAbstract implements FormatterInterface
{
    /**
     * The value will be a boolean.
     */
    public function __construct()
    {
        $this->transformations = [
            function ($value) : bool {
                return (bool) $value;
            }
        ];
    }
}
