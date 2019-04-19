<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : www.octopy.xyz
 * @license : MIT
 */

namespace Octopy\Config;

use Octopy\Config\Exception\DotEnvException;

class DotEnv
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $path
     * @param string $file
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function load()
    {
        // We don't want to enforce the presence of a .env file,
        // they should be optional.
        if (! is_file($this->path)) {
            return false;
        }

        if (! is_readable($this->path)) {
            throw new DotEnvException("The .env file is not readable: {$this->path}");
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (mb_strpos(trim($line), '#') === 0) {
                continue;
            }

            // If there is an equal sign, then we know we
            // are assigning a variable.
            if (mb_strpos($line, '=') !== false) {
                $this->set($line);
            }
        }

        return true;
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function set(string $key, string $value = ''): void
    {
        extract($this->normalise($key, $value));

        if (! getenv($key, true)) {
            putenv("$key=$value");
        }

        if (empty($_ENV[$key])) {
            $_ENV[$key] = $value;
        }

        if (empty($_SERVER[$key])) {
            $_SERVER[$key] = $value;
        }
    }

    /**
     * @param  string $key
     * @return mixed
     */
    protected function get(string $key)
    {
        switch (true) {
            case array_key_exists($key, $_ENV):
                return $_ENV[$key];
                break;
            case array_key_exists($key, $_SERVER):
                return $_SERVER[$key];
                break;
            default:
                $value = getenv($key);

                return $value === false ? null : $value;
        }
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $_ENV;
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return array
     */
    protected function normalise(string $key, string $value = ''): array
    {
        if (mb_strpos($key, '=') !== false) {
            list($key, $value) = explode('=', $key, 2);
        }

        $key = trim($key);
        $value = trim($value);

        // Sanitize the key
        $key = str_replace(['export', '\'', '"'], '', $key);

        // Sanitize the value
        $value = $this->nested(
            $this->sanitize($value)
        );

        return compact('key', 'value');
    }

    /**
     * @param  string $value
     * @return string
     */
    protected function sanitize(string $value): string
    {
        if (! $value) {
            return $value;
        }

        if (strpbrk($value[0], '"\'') !== false) {
            $regexp = sprintf(
                '/^
                    %1$s          # match a quote at the start of the value
                    (             # capturing sub-pattern used
                     (?:          # we do not need to capture this
                      [^%1$s\\\\] # any character other than a quote or backslash
                      |\\\\\\\\   # or two backslashes together
                      |\\\\%1$s   # or an escaped quote e.g \"
                     )*           # as many characters that match the previous rules
                    )             # end of the capturing sub-pattern
                    %1$s          # and the closing quote
                    .*$           # and discard any string after the closing quote
                    /mx',
                $quote = $value[0]
            );

            $value = preg_replace($regexp, '$1', $value);
            $value = str_replace("\\$quote", $quote, $value);
            $value = str_replace('\\\\', '\\', $value);
        } else {
            $parts = explode(' #', $value, 2);

            $value = trim($parts[0]);

            // Unquoted values cannot contain whitespace
            if (preg_match('/\s+/', $value) > 0) {
                throw new DotEnvException('.env values containing spaces must be surrounded by quotes.');
            }
        }

        return $value;
    }

    /**
     * @param  string $value
     * @return string
     */
    protected function nested(string $value): string
    {
        if (mb_strpos($value, '$') !== false) {
            $loader = $this;

            $value = preg_replace_callback('/\${([a-zA-Z0-9_]+)}/', function ($matched) use ($loader) {
                $nested = $loader->get($matched[1]);

                if (is_null($nested)) {
                    return $matched[0];
                }

                return $nested;
            }, $value);
        }

        return $value;
    }
}
