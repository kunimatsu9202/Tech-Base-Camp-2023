<?php

#データベースに接続（スペースの位置は固定）
$dsn = 'mysql:host=localhost; dbname=php_todoapp; charset=utf8';
$user = 'testuser';
$pass = 'testpass';

try{
  $dbh = new PDO($dsn, $user, $pass);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e){
  echo "エラー：".$e->getMessage();
  die();
}


/*
 * db_connection.php
 * データベース接続情報を記載します
 */

// $db_info = [
// 	"user" => "root",
// 	"pass" => "",
// 	"host" => "localhost",
// 	"dbname" => "php_todoapp"
// ];
// $dsn = "mysql:host={$db_info['host']}; dbname={$db_info['dbname']}; charset=utf8";

// try{
// 	$dbh = new PDO($dsn, $db_info["user"], $db_info["pass"]);
// 	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// }catch(PDOException $e){
// 	die ("PDO Error:" . $e->getMessage());
// }
