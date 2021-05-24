<?php
// XSS対策のためのHTMLエスケープ
function es($data, $charset = 'UTF-8')
{
    // $dataが配列のとき
    if (is_array($data)) {
        // 再帰呼び出し
        return array_map(__METHOD__, $data);
    } else {
        // HTMLエスケープを行う
        return htmlspecialchars($data, ENT_QUOTES, $charset);
    }
}

// 配列の文字エンコードのチェックを行う
function cken($data)
{
    $result = true;
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            // 含まれている値が配列のとき文字列に連結する
            $value = implode("", $value);
            // var_dump($value);
        }
        if (!mb_check_encoding($value)) {
            // 文字エンコードが一致しないとき
            $result = false;
            // foreachでの走査をブレイクする
            break;
        }
    }
    return $result;
}

// 配列の値チェック関数
function selected($value, $question)
{
    if (is_array($question)) {
        $isChecked = in_array($value, $question);
    } else {
        $isChecked = ($value === $question);
    }
    if ($isChecked) {
        echo "selected";
    } else {
        echo "";
    }
}

// セッションを破壊する
function killSession()
{
    // セッション変数の値を空にする
    $_SESSION = [];
    // セッションクッキーを破棄する
    if (isset($_COOKIE[session_name()])) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $params['path']);
    }
    // セッションを破棄する
    session_destroy();
}
