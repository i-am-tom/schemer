<?php

namespace Schemer\Formatter;

/**
 * Interface for all Schemer formatters.
 */
interface FormatterInterface
{
    /**
     * Format a value according to this Schemer instance.
     * @param mixed $value The variable to format.
     * @return mixed The result of the formatting.
     */
    public function format($value);
}
