<?php

/*
 * login.php
 * フォームに入力された内容を用いて、ログイン判定を行います
 * ログインに成功したら、index.phpに遷移します
 */

session_start();
$err_msg = "";

/*
 * 1. SESSION["logged_in"]をチェック
 * trueならindex.phpに遷移します
 */

if ( isset($_SESSION["loged_in"]) && $_SESSION["loged_in"]){
  header("Location:./index.php");
}

/*
 * 2. POSTデータがあるかをチェック
 * 両方入力されていれば判定を行い、なければエラーを表示します
 */
if (empty($_POST) === false) {
  /* IDまたはパスワードがない場合はエラーメッセージ */
  if (empty($_POST["user_id"]) === false || empty($_POST["password"]) === false) {
      /* ログイン判定用の関数を実行 */
      $err_msg = auth_check();
  } else {
      $err_msg = "<p>ID・パスワードを入力してください</p>";
  }
}

/*
* 3. 入力された値を、ID、パスワードと照合、ログインを判定します
* ログインに成功していれば、SESSION["logged_in"]をtrueに設定し、
* index.phpに遷移します
*/
function auth_check()
{

  require_once "db_connection.php";

  try {
      $stmt = $dbh->prepare("SELECT id, password FROM users WHERE name = ?");
      $stmt->execute([$_POST["user_name"]]);
      $correct_user = $stmt->fetch();

      if ($stmt->rowCount() > 0) { /* SQLの検索結果が1件以上のとき */
          if (md5($_POST["password"]) === $correct_user["password"]) {
              /* ハッシュ化した入力パスワードと、データベースの保存内容が同じ -> 認証成功) */
              $_SESSION["login_id"] = $correct_user["id"];
              $_SESSION["login_name"] = $_POST["user_name"];
              $_SESSION["logged_in"] = true;

              $dbh = null;
              header("Location: index.php");
              exit();
          }
      }
  } catch (PDOException $e) {
      echo $e->getMessage();
      exit();
  }

  $dbh = null;
  $_SESSION["logged_in"] = false;
  return ("IDまたはパスワードが正しくありません");
}

if( $err_msg != "" ){
  print($err_msg);
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MYTOP</title>
  </head>
  <body>
    <form action="login.php" method="post">
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
      <input type="submit" value="認証" />
    </form>
  </body>
</html>
