<?php

namespace Schemer\Formatter;

/**
 * Array formatter.
 */
class Collection extends FormatterAbstract
{
    /**
     * The value will be an array.
     * @param Schemer\Formatter\FormatterInterface $formatter
     */
    public function __construct(FormatterInterface $formatter)
    {
        $this->transformations = [
            function ($value) : array {
                return (array) $value;
            },

            function (array $value) use ($formatter) : array {
                return array_map([$formatter, 'format'], $value);
            }
        ];
    }

    /**
     * THe array will be sorted, possibly by a given function.
     * @param callable $comparator
     * @return Schemer\Formatter\Collection
     */
    public function sort(callable $comparator = null) : Collection
    {
        return $this->pipe(
            function (array $value) use ($comparator) : array {
                $comparator !== null
                    ? usort($value, $comparator)
                    : sort($value);

                return $value;
            }
        );
    }

    /**
     * The array will have no more than a given number of elements.
     * @param int $maximum
     * @return Schemer\Formatter\Collection
     */
    public function truncate(int $maximum) : Collection
    {
        return $this->pipe(
            function (array $value) use ($maximum) : array {
                return count($value) > $maximum
                    ? array_slice($value, 0, $maximum)
                    : $value;
            }
        );
    }

    /**
     * The array will contain only unique items.
     * @return Schemer\Formatter\Collection
     */
    public function unique() : Collection
    {
        return $this
            ->pipe('array_unique')
            ->pipe('array_values'); // Resets indices.
    }
}
