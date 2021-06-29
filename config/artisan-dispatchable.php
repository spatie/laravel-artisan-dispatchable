<?php

return [
    /*
     * These directories will be scanned for dispatchable jobs. They
     * will be registered automatically to Artisan.
     */
    'auto_discover_dispatchable_jobs' => [
        app()->path(),
    ],

    /*
     * This directory will be used as the base path when scanning
     * for dispatchable jobs.
     */
    'auto_discover_base_path' => base_path(),

    /*
     * In production, you likely don't want the package to auto-discover dispatchable
     * jobs every time Artisan is invoked. The package can cache discovered job.
     *
     * Here you can specify where the cache should be stored.
     */
    'cache_file' => storage_path('app/artisan-dispatchable/artisan-dispatchable-jobs.php'),

    /**
     * Here you can specify the prefix to be used for all dispatchable jobs.
     */
    'command_name_prefix' => '',
];
