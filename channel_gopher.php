<?php
require_once 'lib/Feed.php';
require_once 'repos.php';
require_once 'lib/utils.php';
require_once 'lib/wordwrap.php';

if (!ini_get('date.timezone')) {
    date_default_timezone_set('Asia/Shanghai');
}
ini_set('mbstring.substitute_character', "none");

header('Content-Type: text/plain; charset=gbk');

if (isset($_GET['p'])) {
    $page = intval($_GET['p']);
} else {
    $page = -1;
}

if (isset($_GET['feed'])) {
    $feed = $_GET['feed'];
    $rssUrlInt = $repos[$feed];
//    var_dump($rssUrlInt);
} else {
    $feed = "nothing";
    $rssUrlInt = 'https://rsshub.app/eastday/sh';
}

Feed::$cacheDir = __DIR__ . '/tmp';
Feed::$cacheExpire = '2 hours';

//var_dump(Feed::$cacheDir);

$rss = Feed::loadRss($rssUrlInt);
$rssTitle = mb_convert_encoding($rss->title, 'gbk', 'UTF-8');
$items = $rss->item;
if ($page < 0) {
    echo 'i' . $rssTitle . "\tnull.host\t1\r\n";
    for ($i = 0; $i < sizeof($items); $i++) {
        $articleTitle = mb_convert_encoding($items[$i]->title, 'gbk', 'UTF-8');
        $dateStr = date('Y-m-d H:i', (int) $items[$i]->timestamp);
        $paragraphLink = "/users/pengan/cgi-bin/news.cgi?feed=" . $feed . "&p=" . $i;
        echo "0" . $articleTitle . " " . $dateStr . "\t" . $paragraphLink . "\tsdf.org\t70\r\n";

    }

} else {
    $item = $items[$page];
    $itemTitle = $item->title . "\r\n\r\n";
    $itemDescription = $itemTitle . $item->description;
    $itemDescription = convert_img_to_text($itemDescription);
    $itemDescription = preg_replace("/<p[^>]*?>/", "", $itemDescription);
    $itemDescription = str_replace("</p>", "\r\n", $itemDescription);
    $itemDescription = str_replace("&nbsp;", " ", $itemDescription);
    $itemDescription = strip_tags($itemDescription);
    $itemDescription = word_wrap_chinese($itemDescription, 60);
    $itemDescription = mb_convert_encoding($itemDescription, 'gbk', 'UTF-8');

    echo $itemDescription;
}
