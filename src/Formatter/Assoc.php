<?php

namespace Schemer\Formatter;

/**
 * Associative array formatter.
 */
class Assoc extends FormatterAbstract implements FormatterInterface
{
    /**
     * The value will be an associative array.
     */
    public function __construct(array $schema = [])
    {
        $this->transformations = [
            function ($value) : array {
                return (array) $value;
            },

            function (array $values) use ($schema) : array {
                foreach ($schema as $key => $formatter) {
                    $values[$key] = $formatter->format(
                        $values[$key] ?? null
                    );
                }

                return $values;
            }
        ];
    }

    /**
     * The array will only contain keys from a given list.
     * @param array $keys
     * @return Schemer\Formatter\Assoc
     */
    public function only(array $keys) : Assoc
    {
        return $this->pipe(
            function (array $values) use ($keys) : array {
                foreach ($values as $key => $_) {
                    if (!in_array($key, $keys)) {
                        unset($values[$key]);
                    }
                }

                return $values;
            }
        );
    }

    /**
     * The array will have the given key renamed.
     * @param string $from
     * @param string $to
     * @return Schemer\Formatter\Assoc
     */
    public function rename(string $from, string $to) : Assoc
    {
        return $this->pipe(
            function (array $values) use ($from, $to) : array {
                if (isset($values[$from])) {
                    $values[$to] = $values[$from];
                    unset($values[$from]);
                }

                return $values;
            }
        );
    }

    /**
     * The array will not contain keys from a given list.
     * @param array $keys
     * @return Schemer\Formatter\Assoc
     */
    public function strip(array $keys) : Assoc
    {
        return $this->pipe(
            function (array $values) use ($keys) : array {
                foreach ($keys as $key) {
                    if (isset($values[$key])) {
                        unset($values[$key]);
                    }
                }

                return $values;
            }
        );
    }
}
