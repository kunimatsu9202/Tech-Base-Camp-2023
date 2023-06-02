
<?php

# セッションスタート
session_start();

$err_msg = "";

# ログイン情報を保持していたらproduct_list.phpへ
if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("Location: ../products/product_list.php");
}

# IDまたはパスワードが入力されていなかったらエラー
if(empty($_POST) === false) {
    if(empty($_POST["user id"]) === false || empty($_POST["password"]) === false) {
        $err_msg = auth_check();
    } else {
        $err_msg = "<script>alert('ID・パスワードを入力してください。');</script>";
    }
}

# エラーメッセージの出力
if ($err_msg != "") {
    print($err_msg);
}

# 入力されたID・パスワードを認証する関数
function auth_check()
{
    require_once("../database/db_connection.php");

    try {
        $stmt = $dbh -> prepare("SELECT id, password from users where name = ?");
        $stmt -> execute([$_POST["user_name"]]);
        $correct_user = $stmt ->fetch();

        if($stmt -> rowCount() > 0) {
            if(md5($_POST["password"]) === $correct_user["password"]) {
                $_SESSION["login_id"] = $correct_user["id"];
                $_SESSION["login_name"] = $correct_user["user_name"];
                $_SESSION["logged_in"] = true;

                $dbh = null;

                header("Location: ../products/product_list.php");

                exit();
            }
        }
    } catch(PDOException $e) {
        echo $e -> getMessage();
        exit();
    }
    $dbh = null;

    return("<script>alert('IDまたはパスワードが誤っています。');</script>");

}

?>

<!DOCTYPE html>
<html lang="jp">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>ログイン</title>
	<style>
	body {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);

		padding: 15px 30px;
		background: rgba(0, 0, 0, 0.4);
		color: black;
		text-align: center;
		width: 300px;
	}

	td {
		color: red
	}

	body {
		width: fit-content;
		margin: auto;
		padding: 200px 0;
	}

	body {
		background-color: #FFFFE0;
		border-radius: 8px;
		/*角の丸み*/
		box-shadow: 0px 0px 5px silver;
		/*5px=影の広がり具合*/
		padding: 0.5em 0.5em 0.5em 2em;
	}

	body {
		line-height: 1.5;
		padding: 0.5em 0;
	}
	</style>

</head>

<body>
	<h1 class="col-md-4 col-sm-6 col-6 my-1">在庫管理システム</h1>
	<form action="./login.php" method="post">
		<table>
			<tr>
				<td>ユーザーID</td>
				<td><input type="text" name="user_name" value="" /></td>
			</tr>
			<tr>
				<td>パスワード</td>
				<td><input type="password" name="password" value="" /></td>
			</tr>
		</table>
		<input type="submit" value="認証">
	</form>
</body>

</html>
