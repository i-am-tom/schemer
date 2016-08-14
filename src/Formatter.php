<?php

namespace Schemer;

use Schemer\Formatter;

/**
 * The formatting entry point.
 */
class Formatter
{
    /**
     * The value will not be affected.
     * @return Schemer\Formatter\Any
     */
    public static function any() : Formatter\Any
    {
        return new Formatter\Any;
    }

    /**
     * The value will be typed as an associative array.
     * @param array $schema The schema against which to format.
     * @return Schemer\Formatter\Assoc
     */
    public static function assoc(array $schema) : Formatter\Assoc
    {
        return new Formatter\Assoc($schema);
    }

    /**
     * The value will be typed as a boolean.
     * @return Schemer\Formatter\Boolean
     */
    public static function boolean() : Formatter\Boolean
    {
        return new Formatter\Boolean;
    }

    /**
     * The value will be typed as an array of a type.
     * @param Schemer\Formatter\FormatterInterface $formatter
     * @return Schemer\Formatter\Collection
     */
    public static function collection(
        Formatter\FormatterInterface $formatter
    ) : Formatter\Collection {
        return new Formatter\Collection($formatter);
    }

    /**
     * The value will be typed as an integer.
     * @return Schemer\Formatter\Integer
     */
    public static function integer() : Formatter\Integer
    {
        return new Formatter\Integer;
    }

    /**
     * The value will be typed as a floating point number.
     * @return Schemer\Formatter\Real
     */
    public static function real() : Formatter\Real
    {
        return new Formatter\Real;
    }

    /**
     * The value will be typed as a string.
     * @return Schemer\Formatter\Text
     */
    public static function text() : Formatter\Text
    {
        return new Formatter\Text;
    }
}
