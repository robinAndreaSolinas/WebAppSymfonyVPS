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
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'jquery' => [
        'version' => '3.7.1',
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
    'datatables.net-dt' => [
        'version' => '2.0.2',
    ],
    'datatables.net' => [
        'version' => '2.0.2',
    ],
    'datatables.net-dt/css/dataTables.dataTables.min.css' => [
        'version' => '2.0.2',
        'type' => 'css',
    ],
    'datatables.net-responsive' => [
        'version' => '3.0.0',
    ],
    'datatables.net-datetime' => [
        'version' => '1.5.2',
    ],
    'datatables.net-datetime/dist/dataTables.dateTime.min.css' => [
        'version' => '1.5.2',
        'type' => 'css',
    ],
    'datatables.net-responsive-dt' => [
        'version' => '3.0.0',
    ],
    'datatables.net-responsive-dt/css/responsive.dataTables.min.css' => [
        'version' => '3.0.0',
        'type' => 'css',
    ],
];
