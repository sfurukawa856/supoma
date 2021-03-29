<?php

require_once('env.php');
/**
 * PDOを使ってデータベースに接続する
 * @return PDO
 */
function getDatabaseConnection()
{

    $host   = DB_HOST;
    $dbname = DB_NAME;
    $user   = DB_USER;
    $pass   = DB_PASS;

    $dsn    = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

    try {
        $database_handler = new PDO($dsn, $user, $pass);
        // プリペアドステートメントのエミュレーションを無効にする
        $database_handler->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        // 例外がスローされる設定にする
        $database_handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "DB接続に成功しました。<br />";
    } catch (PDOException $e) {
        echo "DB接続に失敗しました。<br />";
        echo $e->getMessage();
        exit;
    }
    return $database_handler;
}
