<?php

namespace Schemer\Validator;

use Schemer\Result;

/**
 * Collection validator.
 */
class Collection extends ValidatorAbstract implements ValidatorInterface
{
    /**
     * The value must be a non-associative array with ordered keys.
     * @param Schemer\Validator\ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->restrictions = [
            self::strictPredicate('is_array', 'not an array'),

            self::predicate(
                function (array $values) : bool {
                    return empty($values) ? true // Range edge case
                        : array_keys($values) === range(0, count($values) - 1);
                },
                'not a standard array'
            ),

            function (array $values) use ($validator) : Result {
                $result = Result::success();

                foreach ($values as $index => $value) {
                    $current = $validator->validate($value);

                    if (!$current->isError()) {
                        continue;
                    }

                    $result = $result->concat(
                        $current->map(function (string $error) use ($index) : string {
                            return "$error at index $index";
                        })
                    );
                }

                return $result;
            }
        ];
    }

    /**
     * Format "elements" for printing.
     * @param int $count
     * @return string
     */
    private static function elementf(int $count) : string
    {
        return sprintf('%d element%s', $count, $count === 1 ? '' : 's');
    }

    /**
     * The collection must have exactly a given number of elements.
     * @param int $count The required number of elements.
     * @return Schemer\Validator\Collection
     */
    public function length(int $count) : Collection
    {
        return $this->pipe(
            self::predicate(
                function (array $values) use ($count) : bool {
                    return count($values) === $count;
                },
                'not exactly ' . self::elementf($count)
            )
        );
    }

    /**
     * The collection must have no more than a given number of elements.
     * @param int $count The maximum number of elements.
     * @return Schemer\Validator\Collection
     */
    public function max(int $count) : Collection
    {
        return $this->pipe(
            self::predicate(
                function (array $values) use ($count) : bool {
                    return count($values) <= $count;
                },
                'not at most ' . self::elementf($count)
            )
        );
    }

    /**
     * The collection must have no less than a given number of elements.
     * @param int $count The minimum number of elements.
     * @return Schemer\Validator\Collection
     */
    public function min(int $count) : Collection
    {
        return $this->pipe(
            self::predicate(
                function (array $values) use ($count) : bool {
                    return count($values) >= $count;
                },
                'not at least ' . self::elementf($count)
            )
        );
    }

    /**
     * This collection values must be ordered in some way.
     * @param callable $comparator A custom comparator function.
     * @return Schemer\Validator\Schemer
     */
    public function ordered(callable $comparator = null) : Collection
    {
        return $this->pipe(
            self::predicate(
                function (array $values) use ($comparator) : bool {
                    $sorted = $values;

                    $comparator !== null
                        ? usort($values, $comparator)
                        : sort($values);

                    return $sorted === $values;
                },
                'not ordered'
            )
        );
    }

    /**
     * This collection must not contain duplicates.
     * @return Schemer\Validator\Collection
     */
    public function unique() : Collection
    {
        return $this->pipe(
            self::predicate(
                function (array $values) : bool {
                    return array_unique($values) === $values;
                },
                'not all unique elements'
            )
        );
    }
}
