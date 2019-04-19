<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @version : v1.0
 * @license : MIT
 */

namespace Octopy\Validation;

use Octopy\HTTP\Request\FileHandler;

trait ValidationRules
{
    /**
     * @param  string $attribute
     * @return string
     */
    public function accepted(string $attribute)
    {
        $value = $this->value($attribute);
        if ($value != true && $value != 'true' && $value != 1 && $value != 'on') {
            return $this->format('The `:attribute` must be accepted.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string  $attribute
     * @param  int     $min
     * @param  int     $max
     * @return string
     */
    public function between(string $attribute, int $min, int $max)
    {
        $value = $this->value($attribute);

        if ($value instanceof FileHandler) {
            $length = $value->size() / 1024;
            if (($length >= $min && $length <= $max) === false) {
                return $this->format('The `:attribute` must be between :min and :max kilobytes.', [
                    ':min'       => $min,
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (ctype_digit($value) || is_integer($value) || is_int($value) || is_float($value) || is_double($value)) {
            $length = $value;
            if (($length >= $min && $length <= $max) === false) {
                return $this->format('The `:attribute` must be between :min and :max.', [
                    ':min'       => $min,
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_array($value)) {
            $length = count($value);
            if (($length >= $min && $length <= $max) === false) {
                return $this->format('The `:attribute` must be between :min and :max items.', [
                    ':min'       => $min,
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_string($value) || is_null($value)) {
            $length = strlen($value);
            if (($length >= $min && $length <= $max) === false) {
                return $this->format('The `:attribute` must be between :min and :max characters.', [
                    ':min'       => $min,
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        }
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function boolean(string $attribute)
    {
        if (! is_bool($this->value($attribute))) {
            return $this->format('The `:attribute` field must be true or false.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @param  string $attribute2nd
     * @return string
     */
    public function confirmed(string $attribute, string $attribute2nd)
    {
        if ($this->value($attribute) !== $this->value($attribute2nd)) {
            return $this->format('The `:attribute` confirmation does not match.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function email(string $attribute)
    {
        if (! filter_var($this->value($attribute), FILTER_VALIDATE_EMAIL)) {
            return $this->format('The `:attribute` must be a valid email address.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @param  string $table
     * @param  string $column
     * @return string
     */
    public function exists(string $attribute, string $table, string $column)
    {
        $db = $this->app['database']->table($table);
        $db->where($column, $this->value($attribute));
        if ($db->count() == 0) {
            return $this->format('The selected `:attribute` is not exists.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function file(string $attribute)
    {
        $value = $this->value($attribute);
        if (! $value instanceof FileHandler) {
            return $this->format('The `:attribute` must be a file.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function integer(string $attribute)
    {
        $value = $this->value($attribute);
        if (! is_integer($value)) {
            return $this->format('The `:attribute` must be an integer.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function ip(string $attribute)
    {
        if (! filter_var($this->value($attribute), FILTER_VALIDATE_IP)) {
            return $this->format('The `:attribute` must be a valid IP address.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @param  int    $max
     * @return string
     */
    public function max(string $attribute, int $max)
    {
        $value = $this->value($attribute);
        if ($value instanceof FileHandler) {
            $size = $value->size() / 1024;
            if (($size < $max) === false) {
                return $this->format('The `:attribute` may not be greater than :max kilobytes.', [
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (ctype_digit($value) || is_integer($value) || is_float($value) || is_double($value)) {
            if (($value < $max) === false) {
                return $this->format('The `:attribute` may not be greater than :max.', [
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_array($value)) {
            if ((count($value) < $max) === false) {
                return $this->format('The `:attribute` may not be greater than :max items', [
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_string($value) || is_null($value)) {
            if ((strlen($value) < $max) === false) {
                return $this->format('The `:attribute` may not be greater than :max characters.', [
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        }
    }

    /**
     * @param  string $attribute
     * @param  int    $max
     * @return string
     */
    public function min(string $attribute, int $min)
    {
        $value = $this->value($attribute);
        if ($value instanceof FileHandler) {
            $size = $value->size() / 1024;
            if (($size > $min) === false) {
                return $this->format('The `:attribute` must be at least :min kilobytes.', [
                    ':min'       => $min,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (ctype_digit($value) || is_integer($value) || is_float($value) || is_double($value)) {
            if (($value > $min) === false) {
                return $this->format('The `:attribute` must be at least :min.', [
                    ':min'       => $min,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_array($value)) {
            if ((count($value) > $min) === false) {
                return $this->format('The `:attribute` must be at least :min items', [
                    ':min'       => $min,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_string($value) || is_null($value)) {
            if ((strlen($value) > $min) === false) {
                return $this->format('The `:attribute` must be at least :min characters.', [
                    ':min'       => $min,
                    ':attribute' => $attribute,
                ]);
            }
        }
    }

    /**
     * @param  string $attribute
     * @param  string $type
     * @return string
     */
    public function mime(string $attribute, string $type)
    {
        $value = $this->value($attribute);

        $mime = '';
        if ($value instanceof FileHandler) {
            $mime = $value->mime();
        } elseif (is_string($value) && file_exists($value)) {
            $mime = mime_content_type($value);
        }

        if ($mime !== $type) {
            return $this->format('The `:attribute` must be a file of type: :type.', [
                ':type'      => $type,
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function required(string $attribute)
    {
        $value = $this->value($attribute);
        if ($value === null || $value === '') {
            return $this->format('The `:attribute` field is required.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function string(string $attribute)
    {
        if (! is_string($this->value($attribute))) {
            return $this->format('The `:attribute` must be a string.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @param  string $table
     * @param  string $column
     * @return string
     */
    public function unique(string $attribute, string $table, string $column)
    {
        $db = $this->app['database']->table($table);
        $db->where($column, $this->value($attribute));
        if ($db->count() > 0) {
            return $this->format('The selected `:attribute` has already been taken.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function uploaded(string $attribute)
    {
        $value = $this->value($attribute);
        if ($value instanceof FileHandler && $value->uploaded() === false) {
            return $this->format('The `:attribute` failed to upload.', [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function url(string $attribute)
    {
        if (! filter_var($this->value($attribute), FILTER_VALIDATE_URL)) {
            return $this->format('The `:attribute` is not valid URL.', [
                ':attribute' => $attribute,
            ]);
        }
    }
}
