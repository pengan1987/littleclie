<?php

$repos = array(
    'eastday' => 'https://rsshub.ioiox.com/eastday/sh',
    'zaobao' => 'https://rsshub.ioiox.com/zaobao/realtime/china',
    'engadget' => 'https://rsshub.ioiox.com/engadget-cn',
    'initium' => 'https://rsshub.ioiox.com/initium/latest/zh-hans'
);

foreach ($repos as $name => $link) {
    try {
        $filename = 'saestor://rss/' . $name . '.xml';
        $rss_content = file_get_contents($link);
        file_put_contents($filename, $rss_content);
        echo $filename, ' ok!', PHP_EOL;
    } catch (Exception $e) {
        echo $filename, $e->getMessage(), PHP_EOL;
    }
}
