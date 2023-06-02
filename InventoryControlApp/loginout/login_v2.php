<?php

# セッションスタート
session_start();

$err_msg = "";

# ログイン情報を保持していたらproduct_list.phpへ
if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]){
  header("Location:./product_list.php");
}

if(empty($_POST) === false){
    if(empty($_POST["user id"]) === false || empty($_POST["password"]) === false){
        $err_msg = auth_check();
    }else{
        $err_msg = "echo '<script>alert('ID・パスワードを入力してください。');</script>';";
    }
}

# エラーメッセージの出力
if ( $err_msg != "" ) {
    print($err_msg);
}

function auth_check()
{
    require_once("db_connection.php");

    try{
        $stmt = $dbh -> prepare("SELECT id, password from users where name = ?");
        $stmt -> execute([$_POST["user_name"]]);
        $correct_user = $stmt ->fetch();

        if($stmt -> rowCount() > 0){
            if(md5($_POST["password"]) === $correct_user["password"]){
                $_SESSION["login_id"] = $correct_user["id"];
                $_SESSION["login_name"] = $correct_user["user_name"];
                $_SESSION["logged_in"] = true;
                
                $dbh = null;

                header("Location: product_list.php");

                exit();
            }
        }
    }

    catch(PDOException $e){
        echo $e -> getMessage();
        exit();
    }
    $dbh = null;

    return("echo '<script>alert('IDまたはパスワードが誤っています。');</script>';");
}



?>

<!DOCTYPE html>
<html lang="jp">

<head>
	<meta charset="UTF-8" \>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<title>ログイン</title>
	<style>
	body {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		/* background-color: #aaa; */

	}

	.inputBox {
		position: relative;
		width: 200px;
		margin: 0 0 50px 0;
	}

	.inputBox input {
		outline: none;
		width: 100%;
		font-size: 14px;
		-webkit-transition: 0.3s;
		-moz-transition: 0.3s;
		-ms-transition: 0.3s;
		-o-transition: 0.3s;
		transition: 0.3s;
	}

	.inputBox label {
		-webkit-transition: 0.3s;
		-moz-transition: 0.3s;
		-ms-transition: 0.3s;
		-o-transition: 0.3s;
		transition: 0.3s;
		position: absolute;
		color: #aaa;
	}

	.inputBox input.style1 {
		padding: 5px 0;
		border: none;
		border-bottom: 2px solid #ccc;
	}

	.inputBox input.style1~label {
		top: 2px;
		left: 0;
	}

	.inputBox input.style1:focus~label,
	.inputBox input.style1.isVal~label {
		top: -12px;
		font-size: 11px;
		color: #EBC70A;
	}

	.inputBox .border {
		position: absolute;
		left: 0;
		bottom: 0;
		display: block;
		height: 1px;
		width: 0;
		background-color: #EBC70A;
		-webkit-transition: 0.4s;
		-moz-transition: 0.4s;
		-ms-transition: 0.4s;
		-o-transition: 0.4s;
		transition: 0.4s;
	}

	.inputBox input.style1:focus~.border,
	.inputBox input.style1.isVal~.border {
		width: 100%;
	}

	.inputBox input.style2 {
		padding: 5px 0;
		border: none;
		border-bottom: 2px solid #ccc;
	}

	.inputBox input.style2~label {
		top: 2px;
		left: 0;
	}

	.inputBox input.style2:focus~label,
	.inputBox input.style2.isVal~label {
		top: -12px;
		font-size: 11px;
		color: #EBC70A;
	}

	.inputBox input.style2:focus~.border,
	.inputBox input.style2.isVal~.border {
		width: 100%;
	}
	</style>

</head>

<body>
	<h1 class="col-md-4 col-sm-6 col-6 my-1">在庫管理システム</h1>
	<form action="login.php" method="post">

		<div class="inputBox">
			<input type="text" class="style1" /><label>ユーザーID</label><span class="border"></span>
		</div>
		<div class="inputBox">
			<input type="password" class="style2" /><label>パスワード</label><span class="border"></span>
		</div>
		<div>
			<input type="submit" value="認証">
		</div>
	</form>
	<script>
	$(function() {
		// フォーカスが当たっとき、入力がなければラベルを上にあげる
		$(".inputBox input").focus(function() {
			if ($(this).val().length === 0) {
				$(this).addClass("isVal");
			} else {
				$(this).removeClass("isVal");
			}
		});

		// フォーカスが外れたとき、入力がなければラベルを下に下げる
		$(".inputBox input").blur(function() {
			if ($(this).val().length === 0) {
				$(this).removeClass("isVal");
			} else {
				$(this).addClass("isVal");
			}
		});
	});
	</script>
</body>

</html>
