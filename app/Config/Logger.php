<?php

/**
 *   ___       _
 *  / _ \  ___| |_ ___  _ __  _   _
 * | | | |/ __| __/ _ \| '_ \| | | |
 * | |_| | (__| || (_) | |_) | |_| |
 *  \___/ \___|\__\___/| .__/ \__, |
 *                     |_|    |___/.
 * @author  : Supian M <supianidz@gmail.com>
 * @link    : framework.octopy.id
 * @license : MIT
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Handler
    |--------------------------------------------------------------------------
    |
    | This option defines the default log handler that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the handlers defined in the "handlers" configuration array.
    |
    */
    'handler' => 'file',

    /*
    |--------------------------------------------------------------------------
    | Error Logging Threshold
    |--------------------------------------------------------------------------
    |
    | You can enable error logging by setting a threshold over zero. The
    | threshold determines what gets logged. Any values below or equal to the
    | threshold will be logged. Threshold options are:
    |
    |   0 = Disables logging, Error logging TURNED OFF
    |   1 = Emergency Messages - System is unusable
    |   2 = Alert Messages     - Action Must Be Taken Immediately
    |   3 = Critical Messages  - Application component unavailable, unexpected exception.
    |   4 = Runtime Errors     - Don't need immediate action, but should be monitored.
    |   5 = Warnings           - Exceptional occurrences that are not errors.
    |   6 = Notices            - Normal but significant events.
    |   7 = Info               - Interesting events, like user logging in, etc.
    |   8 = Debug              - Detailed debug information.
    |   9 = All Messages
    |
    | You can also pass an array with threshold levels to show individual error types
    |
    |   array(1, 2, 3, 8) = Emergency, Alert, Critical, and Debug messages
    |
    | For a live site you'll usually enable Critical or higher (3) to be logged otherwise
    | your log files will fill up very fast.
    |
    */
    'threshold' => 3,

    /*
    |--------------------------------------------------------------------------
    | Log Handler Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log handlers for your application.
    | This gives you a variety of powerful log handlers / formatters to utilize.
    |
    | Supported : "file"
    |
    */
    'configuration' => [

        'file' => [
            'filepath'   => $this->path->writeable('octopy.log'),
            'permission' => 0644,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Date Format for Logs
    |--------------------------------------------------------------------------
    |
    | Each item that is logged has an associated date. You can use PHP date
    | codes to set your own date formatting
    |
    */
    'dateformat' => 'Y-m-d H:i:s',
];
