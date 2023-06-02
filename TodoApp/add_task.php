<?php

/*
 * add_task.php
 * POSTで送信されたtasks のデータを追加する処理を行います
 */

session_start();

if (isset($_POST)) { /* isset() で、$_POSTが送信されているか確認し、送信されている場合はレコードの追加を行う */
    require_once "./db_connection.php";

   $stmt = $dbh->prepare("INSERT INTO tasks VALUES(?, ?, ?, ?, ?)");

	/* POSTで受け取った値と、ログイン中のユーザーのIDを使って、データをテーブルに追加 */
   $stmt->execute( [ 0, $_POST["title"], $_POST["detail"], 0, $_SESSION["login_id"]] );

    /* $stmt->rowCount() はexecuteで実行したSQLが影響したデータベースレコードの件数を取得する
     * これを使って、データの更新ができたかを確認し、フラッシュメッセージをセットする
     */
    if ($stmt->rowCount() > 0) { /* データを追加できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
            'type' => 'success',
            'content' => "Todoを追加しました<br>",
        ];
    } else { /* > 0 ではない場合(データを追加できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
            'type' => 'danger',
            'content' => 'Todoの追加に失敗しました<br>',
        ];
    }
    /* $_POST データが送信されていた場合の処理、ここまで */

} else { /* $_POSTが送信されていなかった場合、エラー扱いとする */
    $_SESSION['flush_message'] = [
        'type' => 'danger',
        'content' => 'データが送信されていません<br>',
    ];
}

/* 一覧画面に遷移する */
header("Location: index.php");
