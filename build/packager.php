<?php
require __DIR__ . '/../vendor/mtdowling/burgomaster/src/Burgomaster.php';

$stageDirectory = __DIR__ . '/staging';
$projectRoot = __DIR__ . '/../';
$packager = new \Burgomaster($stageDirectory, $projectRoot);

foreach (['README.md'] as $file) {
    $packager->deepCopy($file, $file);
}

$packager->recursiveCopy('src', 'SixCRM', ['php', 'pem']);
$packager->recursiveCopy('vendor/guzzlehttp/guzzle/src', 'GuzzleHttp');
$packager->recursiveCopy('vendor/guzzlehttp/promises/src', 'GuzzleHttp/Promise');
$packager->recursiveCopy('vendor/guzzlehttp/psr7/src', 'GuzzleHttp/Psr7');
$packager->recursiveCopy('vendor/psr/http-message/src', 'Psr/Http/Message');

$packager->createAutoloader([ 
    'GuzzleHttp/functions.php',
    'GuzzleHttp/Psr7/functions.php',
    'GuzzleHttp/Promise/functions.php',
]);

$packager->createZip(__DIR__ . '/artifacts/sixcrm-transactional-api-sdk.zip');

