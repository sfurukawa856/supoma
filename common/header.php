<?php
// 共通する処理のPHPプログラムファイルを格納する


/**
 * タイトルを指定してヘッダーを作成する
 * @param $title
 * @return string
 */
function getHeader($title)
{
    return <<< "EOF"

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="スポマ。マッチングサイト " />
    <meta name="robots" content="noindex">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../public/css/rest.css" />

    <title>{$title}</title>

EOF;
}
