<?php

/*
 * db_access.php
 * データベースへアクセスをしてデータを取得する関数をまとめたファイルです
 * このファイルをインクルードして、関数を呼び出すことで、index.phpなどのプログラム記述の見通しが良くなります
 */

/* get_todo_list()
 * データベースから、ログイン中のユーザーのTodoのコードを取り出します
 */
function get_todo_list($user_id){
	/* データベースとのコネクションを開く
	 * $dbb でPDOオブジェクトが使えるようになる
	 */
    require_once "db_connection.php";

	/* プレースホルダつきSQLを作成する */
	$stmt = $dbh->prepare("SELECT * FROM tasks WHERE user_id = ?");

	/* execute() に、配列形式でプレースホルダの値(ログイン中ユーザーのID)を渡す */
	try {
		$ret = $stmt->execute([$_SESSION["login_id"]]);
		if ($ret === true) {
			/* データ取得が成功していたら、trueを返す */
			return (["result" => true, "stmt" => $stmt]);
		} else {
			/* データ取得が失敗していたら、falseを返す */
			return (["result" => false, "stmt" => $stmt]);
		}
	} catch(PDOException $e){
		return (["result" => false, "exeption" => $e]);
	}
}

/* generate_todo_table()
 * データベースから取り出したTodoレコードのステートメントオブジェクトを使って、
 * 画面に表示するtable要素を生成します
 */
function generate_todo_table($stmt){
	if ($stmt->rowCount() === 0){
		return ("<tr><td colspan='3'>データがありません</td></tr>");
	}
	$elms = ""; /* tableの要素をまとめて入れておく変数 */
	while($item = $stmt->fetch() ) {
        // print "<pre>";
        // var_dump($item);
        // print "</pre>";
        if ( $item["done"] === 0 ) {

            $tr = 
                "
                <table border='1'>
                    <tr>
                        <td>件名</td>
                        <td>{$item['title']}</td>
                    </tr>
                    <tr>
                        <td>詳細</td>
                        <td>{$item['detail']}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>
                            <form action='./done_task.php' method='POST'>
                                <button type= 'submit' name='done_id' value='{$item['id']}'>完了</button>
                            </form>
                        </td>
                        <td>
                            <form action='./delete_task.php' method='POST'>
                                <button type='submit' name='delete_id' value='{$item['id']}'>削除</button>
                            </form>
                        </td>
                        <td>
                            <form action='./edit_task.php' method='POST'>
                                <button type='submit' name='edit_id' value='{$item['id']}'>編集</button>
                            </form>
                        </td>
                    </tr>
                </table>
                <br>
                ";
		/* $elmsに、今回の処理で作成した$trの内容を追記する */
		    $elms .= $tr;
        }
	}
	return ($elms); /* $elmsは、最初から最後まですべての$tr の内容を結合した内容になっている */
}