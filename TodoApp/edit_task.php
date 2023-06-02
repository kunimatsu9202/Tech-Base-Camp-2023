<?php

session_start();

if (isset($_POST["title"]) && isset($_POST["detail"])){
    require_once "./db_connection.php";

    var_dump($_POST["edit_id"]);

    $stmt = $dbh->prepare(" UPDATE tasks SET title = ?, detail = ?  WHERE id = ? "); 
    
    $stmt->execute( [$_POST["title"], $_POST["detail"], $_POST["edit_id"]] );

    if ($stmt->rowCount() > 0) { /* データを追加できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
            'type' => 'success',
            'content' => "Todoを編集しました<br>",
        ];
    } else { /* > 0 ではない場合(データを追加できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
            'type' => 'danger',
            'content' => 'Todoの編集に失敗しました<br>',
        ];
    }

    /* 一覧画面に遷移する */
    header("Location: index.php");

}

if (isset($_POST['edit_id'])) {
    require_once "./db_connection.php";

    // var_dump($_POST["edit_id"]);


   $stmt = $dbh->prepare(" SELECT title, detail FROM tasks WHERE id = ? ");

   $stmt->execute( [$_POST["edit_id"]] );
   $stmt =  $stmt->fetch();
//    var_dump($stmt);
} else { 
    $_SESSION['flush_message'] = [
        'type' => 'danger',
        'content' => 'データが送信されていません<br>',
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編集だよ</title>
</head>
<body>
    <h>編集中</h>
    <br>
    <form action="edit_task.php" method="post">
        <table>
            <tr>
                <td>件名</td>
                <td><input type="text" name="title" value="<?=$stmt['title'] ?>"></td>
            </tr>
            <tr>
                <td>詳細</td>
                <td><textarea name="detail" rows="4" value=""><?=$stmt['detail'] ?></textarea></td>
            </tr>
        </table>
        <input type="submit" value="編集を完了する">
        <input type="hidden" name="edit_id" value="<?=$_POST['edit_id'] ?>">
    </form>
    <br>    
</body>
</html>