<?php
//セッション削除
session_start();
$_SESSION = [];
session_destroy();

header('Location: ../../login/');
exit;
