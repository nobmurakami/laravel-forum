<?php

use Illuminate\Support\HtmlString;

// 改行コード(\n)を改行タグ(<br>)に変換する
if (!function_exists('safeBr')) {
    function safeBr(string $value): HtmlString
    {
        return new HtmlString(nl2br(e($value)));
    }
}

// フォームに入力された改行コード[\r\n]を[\n]に変換する
if (!function_exists('crlf2lf')) {
    function crlf2lf(string $str): string
    {
        return str_replace("\r\n", "\n", $str);
    }
}
