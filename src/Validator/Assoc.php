<?php

namespace Schemer\Validator;

use Schemer\Result;

/**
 * Associative array validator.
 */
class Assoc extends ValidatorAbstract implements ValidatorInterface
{
    /**
     * The associative array must contain these keys.
     * @param array $schema The schema to validate.
     */
    public function __construct(array $schema = [])
    {
        $this->restrictions = [
            self::strictPredicate('is_array', 'not an array'),

            function (array $assoc) use ($schema) : Result {
                $result = Result::success();

                foreach ($schema as $key => $validator) {
                    $result = $result->concat(
                        isset($assoc[$key])
                            ? $validator
                                ->validate($assoc[$key])
                                ->map(function (string $error) use ($key) : string {
                                    return "$key: $error";
                                })
                            : Result::failure("missing '$key'")
                    );
                }

                return $result;
            }
        ];
    }

    /**
     * Return "1 entry" or "X entries", depending on the count.
     * @param int $count The number of entries.
     * @return The string to describe this in English.
     */
    private static function entryf($count)
    {
        return sprintf('%d entr%s', $count, $count === 1 ? 'y' : 'ies');
    }

    /**
     * The associative array must have exactly a given number of entries.
     * @param int $entries The number of keys allowed.
     * @return Schemer\Validator\Assoc
     */
    public function length(int $entries) : Assoc
    {
        return $this->pipe(
            self::predicate(
                function (array $assoc) use ($entries) : bool {
                    return count($assoc) === $entries;
                },
                'doesn\'t have exactly ' . self::entryf($entries)
            )
        );
    }

    /**
     * The associative array must have at most a given number of entries.
     * @param int $entries The maximum number of keys allowed.
     * @return Schemer\Validator\Assoc
     */
    public function max(int $entries) : Assoc
    {
        return $this->pipe(
            self::predicate(
                function (array $values) use ($entries) : bool {
                    return count($values) <= $entries;
                },
                'has over ' . self::entryf($entries)
            )
        );
    }

    /**
     * The associative array must have at least a given number of entries.
     * @param int $entries The minimum number of keys allowed.
     * @return Schemer\Validator\Assoc
     */
    public function min(int $entries) : Assoc
    {
        return $this->pipe(
            self::predicate(
                function (array $values) use ($entries) : bool {
                    return count($values) >= $entries;
                },
                'has under ' . self::entryf($entries)
            )
        );
    }

    /**
     * The associative array may never contain the following keys.
     * @param array $keys The optional schema.
     * @return Schemer\Validator\Assoc
     */
    public function never(array $keys) : Assoc
    {
        return $this->pipe(function (array $values) use ($keys) : Result {
            $result = Result::success();

            foreach ($keys as $key) {
                if (!isset($values[$key])) {
                    continue;
                }

                $result = $result->concat(
                    Result::failure("contains '$key'")
                );
            }

            return $result;
        });
    }

    /**
     * The associative array may contain the given values.
     * @param array $schema The optional schema.
     * @return Schemer\Validator\Assoc
     */
    public function optional(array $schema) : Assoc
    {
        return $this->pipe(function (array $values) use ($schema) : Result {
            $result = Result::success();

            foreach ($schema as $key => $validator) {
                if (!isset($values[$key])) {
                    continue;
                }

                $result = $result->concat(
                    $validator
                        ->validate($values[$key])
                        ->map(function ($error) use ($key) {
                            return "$key: $error";
                        })
                );
            }

            return $result;
        });
    }

    /**
     * The associative array may only contain the following keys.
     * @param array $keys The optional schema.
     * @return Schemer\Validator\Assoc
     */
    public function only(array $keys)
    {
        return $this->pipe(function ($values) use ($keys) {
            $result = Result::success();

            foreach ($values as $key => $_) {
                if (in_array($key, $keys)) {
                    continue;
                }

                return $result->concat(
                    Result::failure("contains '$key'")
                );
            }

            return $result;
        });
    }
}
