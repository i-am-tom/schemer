# Schemer [![Build Status](https://travis-ci.org/i-am-tom/schemer.svg?branch=master)](https://travis-ci.org/i-am-tom/schemer)

Schemer is a Joi-inspired library for validating complex data structures in PHP.
Validators can be composed together to create larger validators for tasks such
as schema-checking.

## Example

```php
<?php

include 'vendor/autoload.php';

use Schemer\Schemer as S;

// An associative array...
$myApiSchema = S::assoc([
    // A 3-to-10-character alphanumeric string...
    'username' => S::text()->min(3)->max(10)->alphanum(),

    // An email address...
    'email' => S::text()->email(),

    // A standard list (array) ...
    'friends' => S::collection(
        // ... of 3-to-20-character strings...
        S::text()->min(3)->max(20)
    )
])
    // And, optionally ...
    ->optional([
        // A boolean value...
        'isWizard' => S::boolean()
    ]);

$myApiSchema
    ->validate([
        'username' => 'agilebear',
        'email' => 'hey@no.com',
        'friends' => [
            'lols',
            'le gib',
            'saffy',
            'borb',
            'creams',
            'roy'
        ]
    ])
    ->isError(); // false

$mistakes = $myApiSchema
    ->validate([
        'username' => 'imposter',
        'friends' => 3
    ]);

$mistakes->isError(); // true
$mistakes->errors(); // ["missing 'email'", "friends: not an array"]
```

## API

**All methods are immutable**. Calling a method will return a **new** validator,
and not change the behaviour of the validator in any way:

```php
<?php

$integer = Schemer::Integer();
$integer->min(2); // This returns a NEW validator.

$integer->validate(1)->isError(); // false - the original is unchanged.
```

### `Schemer\Schemer::any() : Schemer\Validator\Any`

The `Any` validator will accept all values of any type.

#### `->but(callable $restriction) : Schemer\Validator\Any`

You can optionally add restrictions to `Any` using this method, which will pipe
a customised restriction onto the end of the chain. This can be used to make any
custom validator that you may need. (However, you could just extend the abstract
in `Schemer\Validator\ValidatorAbstract`, or implement the underlying interface
in `Schemer\Validator\ValidatorInterface` for more complicated validators).

### `Schemer\Schemer::assoc(array $schema = []) : Schemer\Validator\Assoc`

Create an associative array validator with an optional schema. If a schema is
not supplied, this will be a standard associative array validator. However, if
given an array where the keys all map to validators, they will be used to
produce a validator for a particular schema in which all these keys are
mandatory.

#### `->length(int $count) : Schemer\Validator\Assoc`

Require that the associative array must only have a certain number of entries.

#### `->max(int $count) : Schemer\Validator\Assoc`

Require that the associative array must have no more than a given number of
entries.

#### `->min(int $count) : Schemer\Validator\Assoc`

Require that the associative array must have no less than a given number of
entries.

#### `->never(array $keys) : Schemer\Validator\Assoc`

Require that the associative array never contains any of a given set of keys.

#### `->optional(array $schema) : Schemer\Validator\Assoc`

Similar to the constructor, this is a shape validator. However, the absence of
keys specified here is not an error. Instead, _if_ the keys are present, these
validators will be used.

#### `->only(array $keys) : Schemer\Validator\Assoc`

Require that the associative array only contains keys within this list.

### `Schemer\Schemer::boolean() : Schemer\Validator\Boolean`

Require that the value be a boolean.

#### `->true() : Schemer\Validator\Boolean`

Require that the boolean value be true.

#### `->false() : Schemer\Validator\Boolean`

Require that the boolean value be false.

### `Schemer\Schemer::collection(Schemer\Validator\ValidatorAbstract $validator) : Schemer\Validator\Collection`

The Collection validator will validate a list of items that adhere to a
certain validator. The given validator is the "type" of all containing elements.

#### `->length(int $count) : Schemer\Validator\Collection`

Require that the collection have a certain length.

#### `->max(int $count) : Schemer\Validator\Collection`

Require that the collection be no longer than a certain length.

#### `->min(int $count) : Schemer\Validator\Collection`

Require that the collection be no shorter than a certain length.

#### `->ordered(callable $comparator = null) : Schemer\Validator\Collection`

Require that the entries be ordered according to some comparator.

#### `->unique() : Schemer\Validator\Collection`

Require that the entries be unique.

### `Schemer\Schemer::Integer`

See the entries for `Schemer\Real`; this is the same, but restricted to integers.

### `Schemer\Schemer::Real`

Create a validator for floating point / real numbers.

#### `->exactly(float $value) : Schemer\Validator\Real`

Require that the value be exactly a given number.

#### `->max(float $value) : Schemer\Validator\Real`

Require that the value be no more than some amount.

#### `->min(float $value) : Schemer\Validator\Real`

Require that the value be no less than some amount.

#### `->negative() : Schemer\Validator\Real`

Require that the value be no more than zero.

#### `->positive() : Schemer\Validator\Real`

Require that the value be no less than zero.

### `Schemer\Schemer::text() : Schemer\Validator\Text`

Create a validator for a string of text.

#### `->alphanum() : Schemer\Validator\Text`

Require that the contents of the string all be alphanumeric. For standard
locales, this is `[a-zA-Z0-9]`.

#### `->email() : Schemer\Validator\Text`

Require that the string match an email regular expression.

#### `->length(int $length) : Schemer\Validator\Text`

Require that the string be of a certain length.

#### `->lowercase() : Schemer\Validator\Text`

Require that the string only contain lowercase letters.

#### `->min(int $length) : Schemer\Validator\Text`

Require that the string be at least a certain length.

#### `->max(int $length) : Schemer\Validator\Text`

Require that the string be at most a certain length.

#### `->regex(string $regex) : Schemer\Validator\Text`

Require that the string match a certain regular expression.

#### `->uppercase() : Schemer\Validator\Text`

Require that the string only contain uppercase letters.

## Contributing

Get involved! PRs are cool.
