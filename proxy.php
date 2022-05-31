<?php

/**
 * Простой прокси-сервер на PHP, 
 * 
 * Запуск: 
 *
 * php -S 127.0.0.1:9001 proxy.php
 *
 * После этого в браузере можно открывать http://127.0.0.1:9001/
 * и все запросы пойдут через прокси на указанный в $base адрес.
 */
require_once('replacer.php');
$replacer = new Replacer();
$url = $_SERVER['REQUEST_URI'];
$path = parse_url($url, PHP_URL_PATH);
$query = parse_url($url, PHP_URL_QUERY);

$newPath = ltrim($path, '/');
if ($query) {
    $newPath .= '?' . $query;
}
$base = 'https://news.ycombinator.com';
$proxyUrl = $base ."/". $newPath;
header('Content-Type: text/html; charset=utf-8');
$contents = @file_get_contents($proxyUrl);
if ($contents === false) {
    header("HTTP/1.1 404 Not Found");
    die("Unknown.");
}

// Do not replace text if its file
if(strpos($path,".")){
    echo $contents;
}else{
    $contents = $replacer->replaceLinks($base, $contents);
    echo $replacer->replaceContent($contents);
}

?>