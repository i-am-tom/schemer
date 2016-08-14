<?php

namespace Schemer\Formatter;

/**
 * String formatter.
 */
class Text extends FormatterAbstract implements FormatterInterface
{
    /**
     * The value will be a string.
     */
    public function __construct()
    {
        $this->transformations = [
            function ($value) : string {
                return (string) $value;
            }
        ];
    }

    /**
     * The string will be transformed to lowercase.
     * @return Schemer\Formatter\Text
     */
    public function lowercase() : Text
    {
        return $this->pipe('strtolower');
    }

    /**
     * Substrings will be replaced according to a regex.
     * @param string $regex
     * @param string $replacement
     * @return Schemer\Formatter\Text
     */
    public function replace(string $regex, string $replacement) : Text
    {
        return $this->pipe(
            function (string $value) use ($regex, $replacement) {
                return preg_replace($regex, $replacement, $value);
            }
        );
    }

    /**
     * Given characters will be translated.
     * @param string $from
     * @param string $to
     * @return Schemer\Formatter\Text
     */
    public function translate(string $from, string $to) : Text
    {
        return $this->pipe(
            function (string $value) use ($from, $to) : string {
                return strtr($value, $from, $to);
            }
        );
    }

    /**
     * Given characters will be stripped from both string ends.
     * @param string $mask
     * @return Schemer\Formatter\Text
     */
    public function trim(string $mask = " \t\n\r\0\x0B") : Text
    {
        return $this->pipe(
            function (string $value) use ($mask) : string {
                return trim($value, $mask);
            }
        );
    }

    /**
     * The string will be truncated at a given length.
     * @param int $maximum
     * @return Schemer\Formatter\Text
     */
    public function truncate(int $maximum) : Text
    {
        return $this->pipe(
            function (string $value) use ($maximum) : string {
                return strlen($value) > $maximum
                    ? substr($value, 0, $maximum)
                    : $value;
            }
        );
    }

    /**
     * The string will be uppercase.
     * @return Schemer\Formatter\Text
     */
    public function uppercase() : Text
    {
        return $this->pipe('strtoupper');
    }
}
