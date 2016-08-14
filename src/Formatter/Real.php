<?php

namespace Schemer\Formatter;

/**
 * Real formatter.
 */
class Real extends FormatterAbstract implements FormatterInterface
{
    /**
     * The value will be a floating-point number.
     */
    public function __construct()
    {
        $this->transformations = [
            function ($value) : float {
                return (float) $value;
            }
        ];
    }

    /**
     * The value will be an absolute.
     * @return Schemer\Formatter\Real
     */
    public function abs() : Real
    {
        return $this->pipe(
            function (float $value) : float {
                return abs($value);
            }
        );
    }

    /**
     * The value will be no bigger than a given boundary.
     * @param float $boundary
     * @return Schemer\Formatter\Real
     */
    public function max(float $boundary) : Real
    {
        return $this->pipe(
            function (float $value) use ($boundary) : float {
                return min($value, $boundary);
            }
        );
    }

    /**
     * The value will be no smaller than a given boundary.
     * @param float $boundary
     * @return Schemer\Formatter\Real
     */
    public function min(float $boundary) : Real
    {
        return $this->pipe(
            function (float $value) use ($boundary) : float {
                return max($value, $boundary);
            }
        );
    }
}
