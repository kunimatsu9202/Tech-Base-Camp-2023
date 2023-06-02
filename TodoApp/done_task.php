<?php

session_start();

if (isset($_POST['done_id'])) { /* isset($_POST['done_id']) で、完了するTodoのidが指定されているか確認し、指定されている場合は処理を行う */
    require_once "./db_connection.php";

   $stmt = $dbh->prepare(" UPDATE tasks SET done = done + 1 WHERE id = ? ");

    /* POSTで受け取ったdone_idと、ログイン中のユーザーのIDを使って、レコードを絞り込んで処理を実行 */
   $stmt->execute( [$_POST["done_id"]] );

    /* $stmt->rowCount() はexecuteで実行したSQLが影響したデータベースレコードの件数を取得する
     * これを使って、データの更新ができたかを確認し、フラッシュメッセージをセットする
     */
    if ($stmt->rowCount() > 0) { /* 完了に設定できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
            'type' => 'success',
            'content' => "id: {$_POST['done_id']} のTodoを完了しました<br>",
        ];
    } else { /* > 0 ではない場合(データを追加できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
            'type' => 'danger',
            'content' => '存在しないタスクのIDが指定されました<br>',
        ];
    }
    /* $_POST['done_id'] が送信されていた場合の処理、ここまで */

} else { /* $_POST['done_id']が送信されていなかった場合、エラー扱いとする */
    $_SESSION['flush_message'] = [
        'type' => 'danger',
        'content' => '存在しないタスクのIDが指定されました<br>',
    ];
}

/* 一覧画面に遷移する */
header("Location: index.php");
