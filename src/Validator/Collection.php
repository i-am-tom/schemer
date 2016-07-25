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
     */
    public function __construct(ValidatorAbstract $validator)
    {
        $this->restrictions = [
            self::predicate('is_array', 'not an array'),

            self::predicate(
                function (array $values) : bool {
                    return array_keys($values) === range(0, count($values) - 1);
                },
                'not a standard array'
            ),

            function (array $values) use ($validator) : Result {
                $result = $result->success();

                foreach ($values as $index => $value) {
                    $current = $validator->validate($value);

                    if (!$current->isError()) {
                        continue;
                    }

                    $result = $result->concat(
                        $current->map(function (string $error) use ($index) : string {
                            return "$error at index $index";
                        });
                    );
                }

                return $result;
            }
        ];
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
                "not exactly $count elements"
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
                "not at most $count elements"
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
                "not at least $count elements"
            )
        );
    }

    /**
     * This collection values must be ordered in some way.
     * @param callable $comparator A custom comparator function.
     * @return Schemer\Validator\Schemer An updated clone.
     */
    public function ordered(callable $comparator = null) : Collection
    {
        return $this->pipe(
            self::predicate(
                function (array $values) use ($comparator) : array {
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
