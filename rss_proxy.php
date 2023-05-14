<?php
header('Content-Type: application/xml');
header('Access-Control-Allow-Origin: *');

$rssLink = $_GET['rssLink'];

if (!empty($rssLink)) {
    $content = file_get_contents($rssLink);
    echo $content;
} else {
    echo 'Error: RSS link not provided';
}