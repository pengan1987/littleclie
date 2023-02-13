<?php

function downloadImage($url, $directory)
{
    if (!file_exists($directory)) {
        mkdir($directory, 0775, true);
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->buffer($data);

    $extension = "";
    switch ($mime_type) {
        case "image/jpeg":
            $extension = ".jpg";
            break;
        case "image/png":
            $extension = ".png";
            break;
        case "image/gif":
            $extension = ".gif";
            break;
        case "image/webp":
            $extension = ".webp";
            break;
    }

    if ($extension) {
        $new_file_name = md5($url) . $extension;
        $save_path = $directory . "/" . $new_file_name;
        file_put_contents($save_path, $data);
        return $save_path;
    } else {
        return false;
    }
}

function replace_article_images($content, $link_prefix)
{
    $imgSrc = [];

    $new_html = $content;

    // Use regular expressions to extract all the <img> tags from the HTML
    preg_match_all("/<img[^>]+\>/i", $content, $matches);
    $img_tags = $matches[0];

    // Loop through each <img> tag
    foreach ($img_tags as $img_tag) {
        // Extract the src attribute from the <img> tag
        preg_match("/src=[\"'](.*?)[\"']/", $img_tag, $src_matches);
        $src = $src_matches[1];

        // Download the image

        $srcMD5 = md5($src);
        array_push($imgSrc, $src);

        // Replace the src attribute in the <img> tag with the local path
        $new_img_tag = str_replace($src, $link_prefix . md5($src) . '.gif', $img_tag);
        $new_html = str_replace($img_tag, $new_img_tag, $new_html);
    }
    $result = ["html" => $new_html, "images" => $imgSrc];
    return $result;
}

function cache_article($title, $content, $articleId, $directory)
{
    if (!file_exists($directory)) {
        mkdir($directory, 0775, true);
    }

    $html = "<html><head><title>" . $title . "</title>";
    $html .= '<meta http-equiv="Content-Type" content="text/html; charset=gb2312"></head><body>';

    $html .= "<h1>" . $title . "</h1>" . $content;

    $html .= "</body></html>";

    if (!file_exists($directory)) {
        mkdir($directory, 0775, true);
    }
    $article_cache = $directory . "/" . $articleId . ".html";
    $gbContent = mb_convert_encoding($html, 'gbk', 'UTF-8');

    file_put_contents($article_cache, $gbContent);
}

function convert_img_to_a($content)
{

    preg_match_all("/<img[^>]+\>/i", $content, $matches);
    $img_tags = $matches[0];

    // Loop through each <img> tag
    foreach ($img_tags as $img_tag) {
        // Extract the src attribute from the <img> tag
        preg_match("/src=[\"'](.*?)[\"']/", $img_tag, $src_matches);
        $src = $src_matches[1];

        // Download the image
        $link = '<a href="' . $src . '">图片</a>';

        // Replace the src attribute in the <img> tag with the local path
        $content = str_replace($img_tag, $link, $content);
    }
    return $content;
}
