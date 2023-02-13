<?php
require_once 'repos.php';
require_once 'lib/Feed.php';
require_once 'lib/utils.php';

if (!ini_get('date.timezone')) {
    date_default_timezone_set('Asia/Shanghai');
}
ini_set('mbstring.substitute_character', "none");

header('Content-Type: text/html; charset=gbk');

$rss_url = 'https://rsshub.app/eastday/sh';
$feed = 'eastday';
if (isset($_GET['feed'])) {
    $feed = $_GET['feed'];
    $rss_url = $repos[$feed];
}

$baseTmpDir = "tmp/";
$feedTmpDir = $baseTmpDir . $feed;
$cache_file = $feedTmpDir . ".html";

$rss = Feed::loadRss($rss_url);

// Create the HTML output
$html = "<html><head><title>" . $rss->channel->title . "</title></head><body>";
$html .= "<h1>" . $rss->title . "</h1>";

// Loop through each feed item
$items = $rss->item;
$all_images = [];
for ($i = 0; $i < sizeof($items); $i++) {
    $item = $items[$i];
    $dateString = date('Y-m-d H:i', (int) $item->timestamp);
    $articleLink = $feed . "/" . $i . ".html";
    $html .= "<p><a href='" . $articleLink . "'>" . $item->title . "</a> " . $dateString . "</p>";

    $imgLinkPrefix = '/thumb/';
    $articleHtml = convert_img_to_a($item->description);

    cache_article($item->title, $articleHtml, $i, $feedTmpDir);
}

$html .= "</body></html>";

// Write the HTML output to the cache file
$html = mb_convert_encoding($html, 'gbk', 'UTF-8');
file_put_contents($cache_file, $html);

echo ("<br>Total articles: " . sizeof($rss->item));
echo "<br>OK";

#finally run this command in linux to upload all contents into cloud storage
#./coscli  cp tmp/ cos://web/ -r
#system("./coscli cp tmp/ cos://dnbwgweb-1255835060/ -r -c .cos.yaml");
