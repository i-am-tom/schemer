<?php

namespace Schemer\Formatter;

/**
 * Blank formatter.
 */
class Any extends FormatterAbstract
{
    /**
     * The value will be unchanged.
     */
    public function __construct()
    {
        $this->transformations = [];
    }

    /**
     * Add a custom transformation.
     * @param callable $f The formatting transformation.
     * @return Schemer\Formatter\Any
     */
    public function but(callable $f) : Any
    {
        return $this->pipe($f);
    }
}
