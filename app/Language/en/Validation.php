<?php


/*
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

return [
    /*
     |--------------------------------------------------------------------------
     | Validation Language Lines
     |--------------------------------------------------------------------------
     |
     | The following language lines contain the default error messages used by
     | the validator class. Some of these rules have multiple versions such
     | as the size rules. Feel free to tweak each of these messages here.
     |
     */

    'accepted'  => 'The `:attribute` must be accepted.',
    'between'   => [
        'numeric'   => 'The `:attribute` must be between :min and :max.',
        'array'     => 'The `:attribute` must be between :min and :max items.',
        'bytes'     => 'The `:attribute` must be between :min and :max kilobytes.',
        'string'    => 'The `:attribute` must be between :min and :max characters.',
    ],
    'boolean'   => 'The `:attribute` field must be true or false.',
    'confirmed' => 'The `:attribute` confirmation does not match.',
    'email'     => 'The `:attribute` must be a valid email address.',
    'exists'    => 'The selected `:attribute` is not exists.',
    'file'      => 'The `:attribute` must be a file.',
    'integer'   => 'The `:attribute` must be an integer.',
    'ip'        => 'The `:attribute` must be a valid IP address.',
    'max'       => [
        'numeric'   => 'The `:attribute` may not be greater than :max.',
        'array'     => 'The `:attribute` may not be greater than :max items',
        'bytes'     => 'The `:attribute` may not be greater than :max kilobytes.',
        'string'    => 'The `:attribute` may not be greater than :max characters.',
    ],
    'min'       => [
        'numeric'   => 'The `:attribute` must be at least :min.',
        'array'     => 'The `:attribute` must be at least :min items',
        'bytes'     => 'The `:attribute` must be at least :min kilobytes.',
        'string'    => 'The `:attribute` must be at least :min characters.',
    ],
    'mime'      => 'The `:attribute` must be a file of type: :type.',
    'required'  => 'The `:attribute` field is required.',
    'string'    => 'The `:attribute` must be a string.',
    'unique'    => 'The selected `:attribute` has already been taken.',
    'uploaded'  => 'The `:attribute` failed to upload.',
    'url'       => 'The `:attribute` is not valid URL.',
];
