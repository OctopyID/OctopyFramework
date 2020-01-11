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

trait ValidationRule
{
    /**
     * @param  string $attribute
     * @return string
     */
    public function accepted(string $attribute)
    {
        $value = $this->value($attribute);
        if ($value !== true && $value !== 'true' && $value !== 1 && $value !== 'on') {
            return $this->format($this->app->lang['validation.accepted'], [
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
                return $this->format($this->app->lang['validation.between.bytes'], [
                    ':min'       => $min,
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (ctype_digit($value) || is_int($value) || is_int($value) || is_float($value) || is_double($value)) {
            $length = $value;
            if (($length >= $min && $length <= $max) === false) {
                return $this->format($this->app->lang['validation.between.numeric'], [
                    ':min'       => $min,
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_array($value)) {
            $length = count($value);
            if (($length >= $min && $length <= $max) === false) {
                return $this->format($this->app->lang['validation.between.array'], [
                    ':min'       => $min,
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_string($value) || is_null($value)) {
            $length = mb_strlen($value);
            if (($length >= $min && $length <= $max) === false) {
                return $this->format($this->app->lang['validation.between.string'], [
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
            return $this->format($this->app->lang['validation.boolean'], [
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
            return $this->format($this->app->lang['validation.confirmed'], [
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
            return $this->format($this->app->lang['validation.email'], [
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
        $database = $this->app['database']->table($table);
        $database->where($column, $this->value($attribute));

        if ($database->count() === 0) {
            return $this->format($this->app->lang['validation.exists'], [
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
            return $this->format($this->app->lang['validation.file'], [
                ':attribute' => $attribute,
            ]);
        }
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function int(string $attribute)
    {
        $value = $this->value($attribute);
        if (! is_int($value)) {
            return $this->format($this->app->lang['validation.integer'], [
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
        return $this->int($attribute);
    }

    /**
     * @param  string $attribute
     * @return string
     */
    public function ip(string $attribute)
    {
        if (! filter_var($this->value($attribute), FILTER_VALIDATE_IP)) {
            return $this->format($this->app->lang['validation.ip'], [
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
                return $this->format($this->app->lang['validation.max.bytes'], [
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (ctype_digit($value) || is_int($value) || is_float($value) || is_double($value)) {
            if (($value < $max) === false) {
                return $this->format($this->app->lang['validation.max.numeric'], [
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_array($value)) {
            if ((count($value) < $max) === false) {
                return $this->format($this->app->lang['validation.max.array'], [
                    ':max'       => $max,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_string($value) || is_null($value)) {
            if ((mb_strlen($value) < $max) === false) {
                return $this->format($this->app->lang['validation.max.string'], [
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
                return $this->format($this->app->lang['validation.min.bytes'], [
                    ':min'       => $min,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (ctype_digit($value) || is_int($value) || is_float($value) || is_double($value)) {
            if (($value > $min) === false) {
                return $this->format($this->app->lang['validation.min.numeric'], [
                    ':min'       => $min,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_array($value)) {
            if ((count($value) > $min) === false) {
                return $this->format($this->app->lang['validation.min.array'], [
                    ':min'       => $min,
                    ':attribute' => $attribute,
                ]);
            }
        } elseif (is_string($value) || is_null($value)) {
            if ((mb_strlen($value) > $min) === false) {
                return $this->format($this->app->lang['validation.min.string'], [
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
            return $this->format($this->app->lang['validation.mime'], [
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
            return $this->format($this->app->lang['validation.required'], [
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
            return $this->format($this->app->lang['validation.string'], [
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
        $database = $this->app['database']->table($table);
        $database->where($column, $this->value($attribute));
        if ($database->count() > 0) {
            return $this->format($this->app->lang['validation.unique'], [
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
            return $this->format($this->app->lang['validation.uploaded'], [
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
            return $this->format($this->app->lang['validation.url'], [
                ':attribute' => $attribute,
            ]);
        }
    }
}
