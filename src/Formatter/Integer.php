<?php

namespace Schemer\Formatter;

/**
 * Integer formatter.
 */
class Integer extends FormatterAbstract
{
    /**
     * The value will be a integer.
     */
    public function __construct()
    {
        $this->transformations = [
            function ($value) : int {
                return (int) $value;
            }
        ];
    }

    /**
     * The value will be an absolute.
     * @return Schemer\Formatter\Integer
     */
    public function abs() : Integer
    {
        return $this->pipe('abs');
    }

    /**
     * The value will be no bigger than a given boundary.
     * @param int $boundary
     * @return Schemer\Formatter\Integer
     */
    public function max(int $boundary) : Integer
    {
        return $this->pipe(
            function (int $value) use ($boundary) : int {
                return min($value, $boundary);
            }
        );
    }

    /**
     * The value will be no smaller than a given boundary.
     * @param int $boundary
     * @return Schemer\Formatter\Integer
     */
    public function min(int $boundary) : Integer
    {
        return $this->pipe(
            function (int $value) use ($boundary) : int {
                return max($value, $boundary);
            }
        );
    }
}
