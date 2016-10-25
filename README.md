# Schemer [![Build Status](https://travis-ci.org/i-am-tom/schemer.svg?branch=master)](https://travis-ci.org/i-am-tom/schemer)

```
composer require i-am-tom/schemer
```

Schemer is a [Joi](https://github.com/hapijs/joi)-inspired library for validating and formatting data structures. Complex structures' validators can be constructed by composition.

## Quickstart

The full API can be accessed via the `Schemer\Validator` and `Schemer\Formatter` sets of static functions. We'll bring them in with an alias:

```php
<?php

include 'vendor/autoload.php';

use Schemer\Validator as V;
use Schemer\Formatter as F;
```

Now, we'll declare a validator and a formatter:

```php
$validator = V::assoc([
    'username' => V::text()
        ->min(3)
        ->max(10)
        ->alphanum(),

    'age' => V::integer()
        ->positive(),

    'email' => V::text()
        ->email(),

    'friends' => V::collection(
        V::text()
            ->min(3)
            ->max(20)
    )
]);

$formatter = F::assoc([
    'age' => F::integer(),

    'friends' => F::collection(
        F::text()
    )
])->only([
    'username',
    'age',
    'email',
    'friends'
]);
```

We've used the `Assoc` validator and formatter to create more complicated validators that nest data. The `Collection` interfaces can do similar things for sequential arrays. So, let's look at the formatter:

```php
// $_GET = [
//     'username' => 'agilebear',
//     'email' => 'hey@no.com',
//     'age' => '40'
// ];

$formatted = $formatter->format($_GET);
// $formatted = [
//     'username' => 'agilebear',
//     'email' => 'hey@no.com',
//     'age' => 40,
//     'friends' => []
// ];
```

We see that `age` is now an integer, and the `friends` key has been created with an empty array, as per the formatting instructions. When handling data from other sources, you'll probably want to attempt to format the data before validating it.

Validation comes in two flavours. First, we'll start with simple validation:

```php
$validator
    ->validate($formatted)
    ->isError(); // false

$result = $validator->validate([
    'username' => 'criminal',
    'friends' => 3
]);

$result->isError(); // true
$result->errors();
// [
//     'age: missing key',
//     'email: missing key',
//     'friends: not an array'
// ]
```

This flattens all the errors down to one `Result` whose `errors` method will return them in an array. While this may be suitable for validation of API requests, for example, it's often helpful for the structure of the errors to mimic the structure of the data. This can be achieved using `nestedValidate`:

```php
$validator->nestedValidate([
    'username' => 'criminal',
    'friends' => 3
]);
// [
//     'age' => Result::failure('missing key'),
//     'email' => Result::failure('missing key'),
//     'friends' => Result::failure('not an array')
// ]
```

Although the above represents the result as an array, it is actually an instance of `Schemer\NestedResult`, which implements both `ArrayAccess` and `Traversable`, which allows you to use `foreach` syntax as well as array access to get at the results (e.g. `$output['age']->errors() === ['missing key']`). If there's a nestable (`Assoc` or `Collection`) within your structure, `nestedValidate` will recurse down through these in the same pattern, too!

Well, that's all there is to it! The API will fill in the rest of the blanks.

## API

**All methods are immutable**. Calling a method will return a **new** object, and not change the previous one in any way:

```php
<?php

$integer = Schemer\Validator::integer();
$integer->min(2); // This returns a NEW validator.

$integer->validate(1)->isError(); // false
```

### `Schemer\Formatter`

```php
<?php

// Format the value as an associative array with a [key => Formatter] schema.
Schemer\Formatter::assoc(array $schema)
    ->only(array $keys) // Strip unmentioned keys.
    ->rename(string $from, string $to) // Rename a key.
    ->renameMany(array $map) // Rename keys according to a [key => value] map.
    ->strip(array $keys) // Strip mentioned keys.

Schemer\Formatter::boolean() // Format as boolean.

// Format the value to an array of elements formatted accordingly.
Schemer\Formatter::collection(Schemer\Formatter\FormatterInterface $formatter)
    // Sort the values, either with sort() or a given comparator.
    ->sort(callable $comparator = null)
    ->truncate(int $maximum) // Strip values after a given length.
    ->unique() // Strip duplicates.

Schemer\Formatter::integer()
    ->abs() // Make the value positive if negative.
    ->max(int $boundary) // Cap the value at a maximum.
    ->min(int $boundary) // Make the value at least a minimum.

Schemer\Formatter::real()
    ->abs() // Make the value positive if negative.
    ->max(int $boundary) // Cap the value at a maximum.
    ->min(int $boundary) // Make the value at least a minimum.

Schemer\Formatter::text()
    ->lowercase() // Transform to lowercase.

    // Replace according to a regular expression.
    ->replace(string $regex, string $replacement)
    ->translate(string $from, string $to) // Translate characters.
    ->trim(string $mask = " \t\n\r\0\x0B") // Trim the string ends.
    ->truncate(int $maximum) // Cut the string to a maximum length.
    ->uppercase() // Transform to uppercase.
```

### `Schemer\Validator`

```php
<?php

// Included on every validator.
Schemer\ValidatorAbstract
  // Create a non-fatal validator from a bool-returning predicate function.
  ->should(callable $predicate, string $error = 'unsatisfied predicate')

  // Create a fatal validator from a bool-returning predicate function.
  ->must(callable $predicate, string $error = 'unsatisfied predicate')

  // Validate a given value against this validator.
  ->validate($value)

// Included on Assoc and Collection. Extends ValidatorAbstract.
Schemer\NestableAbstract

  // Validate a given value to produce a NestableResult.
  ->nestedValidate($value)

Schemer\Validator::any() // Accept all values of any type.

 // Allowed values. This is a specialised Any.
Schemer\Validator::allow(array $whitelist)

// Validate according to a schema. This is a [key => Validator] set,
// where the Validator can be any ValidatorInterface implementation.
Schemer\Validator::assoc(array $schema = [])
    ->length(int $count) // Set the required number of entries.
    ->max(int $count) // Set the maximum number of entries.
    ->min(int $count) // Set the minimum number of entries.
    ->never(array $keys) // Add the key blacklist.
    ->optional(array $schema) // A schema of optional keys.
    ->only(array $keys) // Add the key whitelist.

Schemer\Validator::boolean() // Accept only boolean values.
    ->true() // Accept only 'true'.
    ->false() // Accept only 'false'.

// Accept an array of items all matching a given validator.
Schemer\Validator::collection(Schemer\Validator\ValidatorInterface $validator)
    ->length(int $count) // Set the required array length.
    ->max(int $count) // Set the maximum array length.
    ->min(int $count) // Set the minimum array length.

    // The elements must be sorted, either with sort() or a given
    // comparison function.
    ->ordered(callable $comparator = null)
    ->unique() // All values must be unique.

// Accept objects of a given class. This is a specialised Any.
Schemer\Validator::instanceOf(string $comparison)

Schemer\Validator::integer() // Accept integer values. Specialised Real.

// Accept a value that matches one of an array of validators.
// This is a specialised Any.
Schemer\Validator::oneOf(array $validators)

Schemer\Validator::real() // Accept floating-point values.
    ->exactly(float $value) // Set the required value.
    ->max(float $value) // Set the maximum value.
    ->min(float $value) // Set the minimum value.
    ->negative() // Allow only values less than or equal to zero.
    ->positive() // Allow only values greater than or equal to zero.

 // Allow all values accept those from a given set. Specialised Any.
Schemer\Validator::reject(array $blacklist)

Schemer\Validator::text() // Accept string values.
    ->alphanum() // Allow only alphanumeric strings.
    ->email() // Allow only email addresses.
    ->length(int $length) // Set the allowed string length.
    ->lowercase() // Allow only lowercase strings.
    ->max(int $length) // Set the maximum string length.
    ->min(int $length) // Set the minimum string length.
    ->regex(string $regex) // Set the regex to be matched.
    ->uppercase() // Allow only uppercase strings.
```

## Contributing

Get involved! PRs are cool.
