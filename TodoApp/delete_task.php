<?php

/*
 * delete_task.php
 * POSTで送信されたIDのtasks レコードを削除する処理を行います
 */

session_start();

if (isset($_POST['delete_id'])) { /* isset($_POST['delete_id']) で、削除するTodoのidが指定されているか確認し、指定されている場合は処理を行う */
    require_once "./db_connection.php";

   $stmt = $dbh->prepare(" DELETE FROM tasks WHERE id = ? ");

	/* POSTで受け取ったdelete_idと、ログイン中のユーザーのIDを使って、レコードを絞り込んで処理を実行 */
   $stmt->execute([$_POST["delete_id"]]);

    /* $stmt->rowCount() はexecuteで実行したSQLが影響したデータベースレコードの件数を取得する
     * これを使って、データの削除ができたかを確認し、フラッシュメッセージをセットする
     */

    if ($stmt->rowCount() > 0) { /* データを追加できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
            'type' => 'success',
            'content' => "Todoを削除しました<br>",
        ];
    } else { /* > 0 ではない場合(データを追加できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
            'type' => 'danger',
            'content' => 'Todoの削除に失敗しました<br>',
        ];
    }

    /* $_POST['delete_id'] が送信されていた場合の処理、ここまで */

} else { /* $_POST['delete_id']が送信されていなかった場合、エラー扱いとする */
    $_SESSION['flush_message'] = [
        'type' => 'danger',
        'content' => 'データが送信されていません<br>',
    ];
}


/* 一覧画面に遷移する */
header("Location: index.php");
