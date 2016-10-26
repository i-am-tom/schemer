<?php

namespace Schemer;

class ValidationError
{
    private /* const */ $messages = [
        'NOT_ALPHANUMERIC' => 'not alphanumeric',
        'NOT_EMAIL' => 'not an email',
        'LENGTH_MISMATCH' => 'not exactly %d %s',
        'NOT_LOWERCASE' => 'not all lowercase',
        'NOT_UPPERCASE' => 'not all uppercase',
        'TOO_LONG' => 'more than %d %s',
        'TOO_SHORT' => 'less than %d %s',
        'REGEX_MISMATCH' => 'does not match %s',
    ];

    public function __construct(string $token, array $values /* this name is bad */ = [])
    {
        $this->message = $this->messages[$token];
        $this->values = $values;
    }

    public function translate(string $message)
    {
        return sprintf($message, ...$this->values);
    }

    public function __toString()
    {
        return sprintf($this->message, ...$this->values);
    }
}
