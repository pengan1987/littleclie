<?php
$rss_url = 'https://rsshub.ioiox.com/eastday/sh';
$rss_content = file_get_contents($rss_url);
file_put_contents('saestor://rss/eastday.xml', $rss_content);

echo "eastday ok!";
$zaobao_url = 'https://rsshub.ioiox.com/zaobao/realtime/china';
$zaobao_content = file_get_contents($zaobao_url);
file_put_contents('saestor://rss/zaobao.xml', $zaobao_content);

echo "zaobao ok!";