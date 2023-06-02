<?php

/*
 * logout.php
 * ログアウト処理を行い、login.phpに遷移します
 */

/* セッションを開始 */
session_start();

/* セッション情報を空にする */
$_SESSION = array();

/* Cookieに保存したセッションIDを削除 */
setcookie(session_name(), "", time() - 1);

/* セッションデータを破棄 */
session_destroy();

/* login.phpに遷移する */
header("Location: login.php");