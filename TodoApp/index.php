
<?php

session_start();

/*
 * ログイン状態の確認
 */

 if (isset($_SESSION["logged_in"]) == false || $_SESSION["logged_in"] !== true) {
    /* ログイン状態ではないとき */
    header("Location: login.php");
}

/* 共通のデータベース連携処理をまとめたphpファイルをインクルード */
include "db_access.php";

/* flash_messageの出力   xxxx共通のhtml読み込みに使うphpファイルをインクルード */
if (isset($_SESSION['flush_message'])) { /* isset($_SESSION['flush_message']) で、フラッシュメッセージがセットされているか確認する */
    print($_SESSION['flush_message']['content']);
}
/* 一度出力したら、次は表示しないようにセッションからフラッシュメッセージを除去する */
unset($_SESSION['flush_message']);


/* todo リストの一覧を取得する
 * 処理に成功した場合は$res にtrueが、
 * 失敗した場合は$res にfalseが設定される
 */
$res = get_todo_list($_SESSION['login_id']);
if ($res["result"] === true){
    /* データの取得に成功していた場合、htmlに埋め込むtable要素の内容を作る */
    $todo_items = generate_todo_table($res["stmt"]);
} else {
    /* データベースからレコードが取得できなかったら、$elmにはエラーメッセージを入れておく */
    $todo_items = "<tr><td class='alert alert-danger' colspan='3'>データの取得に失敗しました</td></tr>";
}

// var_dump($todo_items);
// print "<pre>";
// var_dump($todo_items);
// print "</pre>";

print($todo_items);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODOアプリ部分だよ</title>
</head>
<body>
    <h>MY TODO LIST</h>
    <br>
    <form action="add_task.php" method="post">
        <table>
            <tr>
                <td>件名</td>
                <td><input type="text" name="title" value=""></td>
            </tr>
            <tr>
                <td>詳細</td>
                <td><textarea name="detail" rows="4"></textarea></td>
            </tr>
        </table>
        <input type="submit" value="追加する">
    </form>
    <br>
    <a href="logout.php">ログアウト</a>    
</body>
</html>


