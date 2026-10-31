<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Vercel serverless entry point
|--------------------------------------------------------------------------
|
| The vercel-php runtime invokes this file for every request. Laravel still
| uses public/index.php as its front controller; this entry point only
| prepares the writable directories and URL defaults that Vercel's read-only
| filesystem does not provide.
|
*/

$isVercel = getenv('VERCEL') === '1';

if ($isVercel) {
    $storagePath = '/tmp/storage';

    $directories = [
        $storagePath.'/app',
        $storagePath.'/framework/cache/data',
        $storagePath.'/framework/sessions',
        $storagePath.'/framework/testing',
        $storagePath.'/framework/views',
        $storagePath.'/logs',
    ];

    foreach ($directories as $directory) {
        if (! is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }
    }

    if (! getenv('APP_URL')) {
        $host = getenv('VERCEL_PROJECT_PRODUCTION_URL') ?: getenv('VERCEL_URL');

        if ($host) {
            $appUrl = 'https://'.$host;

            putenv('APP_URL='.$appUrl);
            $_ENV['APP_URL'] = $appUrl;
            $_SERVER['APP_URL'] = $appUrl;
        }
    }
}

require __DIR__.'/../public/index.php';
