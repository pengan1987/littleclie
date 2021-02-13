<?php
require_once 'lib/Feed.php';
require_once 'repos.php';

if (!ini_get('date.timezone')) {
	date_default_timezone_set('Asia/Shanghai');
}
ini_set('mbstring.substitute_character', "none");

header('Content-Type: text/html; charset=gbk');

if (isset($_GET['p'])) {
	$page = intval($_GET['p']);
} else {
	$page = -1;
}

if (isset($_GET['feed'])) {
	$feed = $_GET['feed'];
	$rssUrlInt = $repos[$feed];
	//	var_dump($rssUrlInt);
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
?>

<html>

<head>
	<meta charset="utf-8">
	<title><?php echo $rssTitle ?></title>
	<style>
		* {
			font-family: 'Simsun', 'Times New Roman', Times, serif;
			font-size: 12px;
		}
	</style>
</head>

<body>

	<?php if ($page < 0) : ?>
		<h4><?php echo $rssTitle ?></h4>
		<?php for ($i = 0; $i < sizeof($items); $i++) : ?>
			<p><a href="<?php echo htmlspecialchars('channel_ppc.php?feed=' . $feed . '&p=' . $i) ?>">
					<?php echo mb_convert_encoding($items[$i]->title, 'gbk', 'UTF-8') ?></a>
				<?php echo date('Y-m-d H:i', (int) $items[$i]->timestamp) ?>
			</p>

		<?php endfor ?>

	<?php else : ?>
		<?php
		$item = $items[$page];
		$itemTitle = mb_convert_encoding($item->title, 'gbk', 'UTF-8');
		$itemDescription = mb_convert_encoding($item->description, 'gbk', 'UTF-8');
		?>
		<h4><?php echo $itemTitle ?></h4>
		<?php echo $itemDescription; ?>
	<?php endif ?>
</body>

</html>