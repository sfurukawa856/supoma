<?php

/**
 * ファイルデータを保存
 * @param string $filename ファイル名
 * @param string $save_path 保存先のパス
 * @param string $caption キャプション
 * @return  bool $result
 */
function fileSave($filename, $save_path)
{
    $result = false;


    try {
        $sql = "INSERT INTO userInfor ( file_name, file_path) VALUE (?,?)";

        $database_handler = getDatabaseConnection();
        $stmt =  $database_handler->prepare($sql);
        $stmt->bindValue(1, $filename);
        $stmt->bindValue(2, $save_path);

        $result = $stmt->execute();
        return $result;
    } catch (Exception $e) {
        echo  $e->getMessage();
        return $result;
    }
}


/**
 * ファイルデータを取得
 * @return  array $fileDate
 */

function getAllfile()
{
    $database_handler = getDatabaseConnection();
    $sql = "SELECT * FROM userInfor";
    $fileDate = $database_handler->query($sql);

    return $fileDate;
}
