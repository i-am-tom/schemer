<?php

namespace Schemer\Validator;

use Schemer\Result;
use Schemer\NestableResult;

/**
 * Associative array validator.
 */
class Assoc extends NestableAbstract
{
    /**
     * The schema for this associative validator.
     * @var array The associative array schema.
     */
    private $schema = [];

    /**
     * The associative array must contain these keys.
     * @param array $schema The schema to validate.
     */
    public function __construct(array $schema = [])
    {
        $this->schema = $schema;

        $this->restrictions = [
            self::strictPredicate(
                'is_array',
                'not an array'
            )
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
     * Validate an associative structure, and return a NestedResult
     * with an assoc array of Results (and possible further nesting).
     * @param mixed $value The value to validate.
     * @return stdClass The nested structure.
     * @see NestableAbstract::nestedValidate()
     */
    public function nestedValidate($value) : NestableResult
    {
        $outer = parent::validateSimple($value);

        // Fatal error => no inner validation.
        $schema = $outer->isFatal() ? [] : $this->schema;
        $values = [];

        // Regrettably, as neat as I could get it...
        foreach ($schema as $key => $validator) {
            if (!isset($value[$key])) {
                $values[$key] = Result::failure('missing key');

                if ($validator instanceof NestableAbstract) {
                    $values[$key] = NestableResult::lift($values[$key]);
                }
            } else {
                $values[$key] = $validator instanceof NestableAbstract
                    ? $validator->nestedValidate($value[$key])
                    : $validator->validate($value[$key]);
            }
        }

        return new NestableResult($outer, $values);
    }
}
