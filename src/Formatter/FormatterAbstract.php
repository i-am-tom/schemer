<?php

namespace Schemer\Formatter;

abstract class FormatterAbstract implements FormatterInterface
{
    /**
     * The transformations for this formatter.
     * @var array A list of functions.
     */
    protected $transformations = [];

    /**
     * A default for missing values.
     * @var mixed
     */
    protected $default = null;

    /**
     * The value will be passed as a constructor to a given class.
     * @param string $class The class to instantiate.
     * @return Schemer\Formatter\FormatterAbstract
     */
    public function construct(string $class) : FormatterAbstract
    {
        return $this->pipe(function ($data) {
            return new $class($data);
        });
    }

    /**
     * Set a default for missing values.
     * @param mixed $default
     * @return Schemer\Formatter\FormatterAbstract
     */
    public function fallback($default) : FormatterAbstract
    {
        $that = clone $this; // Immutability!
        $that->default = $default;

        return $that;
    }

    /**
     * Add another step to the formatter.
     * @param callable $transformation
     * @return Schemer\Formatter\FormatterAbstract
     */
    protected function pipe(callable $transformation) : FormatterAbstract
    {
        $that = clone $this; // Immutability!
        array_push($that->transformations, $transformation);

        return $that;
    }

    /**
     * Execute the formatter on a given value.
     * @param mixed $value
     * @return mixed
     */
    public function format($value)
    {
        return array_reduce(
            $this->transformations,
            function ($x, callable $f) {
                return $f($x);
            },
            $value ?: $this->default
        );
    }
}
