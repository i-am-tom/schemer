<?php

namespace Schemer;

final class ValidationError
{
    const NOT_ALPHANUMERIC = 'NOT_ALPHANUMERIC';
    const NOT_EMAIL = 'NOT_EMAIL';
    const LENGTH_MISMATCH = 'LENGTH_MISMATCH';
    const NOT_LOWERCASE = 'NOT_LOWERCASE';
    const NOT_UPPERCASE = 'NOT_UPPERCASE';
    const TOO_LONG = 'TOO_LONG';
    const TOO_SHORT = 'TOO_SHORT';
    const REGEX_MISMATCH = 'REGEX_MISMATCH';

    private /* const */ $messages = [
        self::NOT_ALPHANUMERIC => 'not alphanumeric',
        self::NOT_EMAIL => 'not an email',
        self::LENGTH_MISMATCH => 'not exactly %d %s',
        self::NOT_LOWERCASE => 'not all lowercase',
        self::NOT_UPPERCASE => 'not all uppercase',
        self::TOO_LONG => 'more than %d %s',
        self::TOO_SHORT => 'less than %d %s',
        self::REGEX_MISMATCH => 'does not match %s',
    ];

    public function __construct(string $token, string $message = '', array $values /* this name is bad */ = [])
    {
        $this->token = $token;
        $this->message = $message ?: $this->messages[$token];
        $this->values = $values;
    }

    public function token()
    {
        return $this->token;
    }

    public function translate(string $message)
    {
        // prefer a custom message over this translation
        if ($this->message !== $this->messages[$this->token]) {
            return (string) $this;
        }

        return sprintf($message, ...$this->values);
    }

    public function __toString()
    {
        return sprintf($this->message, ...$this->values);
    }
}
