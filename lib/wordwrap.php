<?php

function word_wrap_chinese($text, $width)
{
    $words = preg_split('/(?<!^)(?!$)/u', $text);
    $lines = array();
    $line = '';

    foreach ($words as $word) {
        if ($word == "\n") {
          array_push($lines, $line);
          #array_push($lines, "");
          $line = '';
        } else {

            if (mb_strwidth($line . $word) <= $width) {
                $line .= $word;
            } else {
                array_push($lines, $line);
                $line = $word;
            }

        }
    }

    array_push($lines, $line);

    return implode("\n", $lines);
}

