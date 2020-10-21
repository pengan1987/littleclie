<?php
header('Content-Type: text/html; charset=gbk');

if (!ini_get('date.timezone')) {
	date_default_timezone_set('Asia/Shanghai');
}
ini_set('mbstring.substitute_character', "none");

require_once 'lib/Feed.php';

$rssUrlExt = 'http://dnbwg-rss.stor.sinaapp.com/zaobao.xml';
$rssUrlInt = 'saestor://rss/zaobao.xml';

$rss = Feed::loadRss($rssUrlInt);
if (isset($_GET['p'])) {
	$page = intval($_GET['p']);
} else {
	$page = -1;
}

$rssTitle = mb_convert_encoding($rss->title,'gbk','UTF-8');

?>

<?php if ($page < 0) : ?>
	<h1><?php echo $rssTitle ?></h1>


	<?php
	$i = 0;
	foreach ($rss->item as $item) :
		$itemTitle = mb_convert_encoding($item->title,'gbk','UTF-8');
		
		//var_dump($itemTitle);
	?>
		<p><a href="<?php echo htmlspecialchars("zaobao.php?p=" . $i) ?>">
			<?php echo $itemTitle ?></a>
		<?php echo date('Y-m-d H:i', (int) $item->timestamp) ?>
		</p>

	<?php
		$i++;
	endforeach
	?>

<?php
else :
	$item = $rss->item[$page];
	$itemTitle = mb_convert_encoding($item->title,'gbk','UTF-8');
	$itemDescription = mb_convert_encoding($item->description,'gbk','UTF-8');
?>
	<h1><?php echo $itemTitle ?></h1>
	<?php echo $itemDescription; ?>
<?php endif ?>