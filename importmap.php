<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/script/app.js',
        'entrypoint' => true,
    ],
    'jquery_provider' => [
        'path' => './assets/script/jquery.js',
        'entrypoint' => true,
    ],
    'loan_form' => [
        'path' => './assets/script/loan/form.js',
        'entrypoint' => true,
    ],
    'loan_mask' => [
        'path' => './assets/script/loan/mask.js',
        'entrypoint' => true,
    ],
    'loan_range' => [
        'path' => './assets/script/loan/range.js',
        'entrypoint' => true,
    ],
    'loan_validate' => [
        'path' => './assets/script/loan/validate.js',
        'entrypoint' => true,
    ],
    'loan_autocomplete' => [
        'path' => './assets/script/loan/autocomplete.js',
        'entrypoint' => true,
    ],
    'loan_push' => [
        'path' => './assets/script/loan/push.js',
        'entrypoint' => true,
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'jquery-mask-plugin' => [
        'version' => '1.14.16',
    ],
    'jquery-datepicker' => [
        'version' => '1.12.3',
    ],
    'jquery-autocomplete' => [
        'version' => '1.2.8',
    ],
    'jquery-ui' => [
        'version' => '1.13.2',
    ],
];
