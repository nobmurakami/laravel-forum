<?php

use Illuminate\Support\HtmlString;

// 改行コード(\n)を改行タグ(<br>)に変換する
if (!function_exists('safeBr')) {
    function safeBr(string $value): HtmlString
    {
        return new HtmlString(nl2br(e($value)));
    }
}
