<?php
  session_start();
  
  if(isset($_SESSION['logged_in']) == false){
	header("Location: ../loginout/login.php");
	exit;
  }
  else{
	
	if(empty($_POST) === false){
		if($_POST["name"] != "" && $_POST["stock"] != "" && $_POST["price"] != ""){
			require_once "../database/db_connection.php";

			# セキュリティ
			$_POST["name"] = htmlentities($_POST["name"], ENT_QUOTES, "UTF-8");
			$_POST["stock"] = htmlentities($_POST["stock"], ENT_QUOTES, "UTF-8");
			$_POST["price"] = htmlentities($_POST["price"], ENT_QUOTES, "UTF-8");

			try{
				# $recorded_namesにテーブルに既にある商品の名前を配列として格納
				$stmt = $dbh -> prepare("SELECT name FROM products");
				$stmt -> execute();
				$recorded_names = $stmt -> fetchAll();
				
				foreach ( $recorded_names as $recorded_name ) {
					# テーブルに既に名前があったらstockのみ更新
					if ( $_POST["name"] == $recorded_name["name"] ) {		
						$stmt = $dbh -> prepare("UPDATE products SET stock = ?, flg_delete = 0 WHERE name = ?");
						$stmt -> execute([$_POST["stock"],$_POST["name"]]);
						$_SESSION['flush_message'] = [
							'type' => 'success', 
							'content' => "{$_POST['name']}を更新しました。"
						];
						header("Location: ./add_product.php");
						exit();
					}
				}

				$stmt = $dbh -> prepare("INSERT into products(name, stock, price) value(?, ?, ?)");
				$stmt -> execute([$_POST["name"], $_POST["stock"], $_POST["price"]]);
				
				if ($stmt -> rowCount() > 0){
					$_SESSION['flush_message'] = [
						'type' => 'success', 
						'content' => "{$_POST['name']}を追加しました。"
					];
					header("Location: ./add_product.php");
					exit();
				} else {
					$_SESSION['flush_message'] = [
						'type' => 'danger', 
						'content' => "{$_POST['name']}の追加に失敗しました。"
					];
					exit();
				}
			}catch(PODException $e){
				echo $e->getMessage();
				exit();
			}
			$dbh = null;
    	}else{
			$_SESSION['flush_message'] = [
				'type' => 'danger', 
				'content' => "未入力の項目があります。"
			];
		}
    }
  }

  if(isset($_SESSION['flush_message'])){
      echo $_SESSION['flush_message']['content'];
	  unset($_SESSION['flush_message']);
  }

?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<title>商品登録</title>
    <style>
	
	h1 {
       padding: 0.25em 0.5em;/*上下 左右の余白*/
       color: #494949;/*文字色*/
       background: transparent;/*背景透明に*/
       border-left: solid 5px #32CD32;/*左線*/
    }

    body {
        margin: 250px;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 80;
        left: 0;
        background-color: #E6E6FA;
        box-shadow: 0px 0px 0px 10px #E6E6FA;/*線の外側#fffde8*/
        border: dashed 2px #32CD32 ;/*破線*/
        border-radius: 9px;
        margin-left: 10px;/*はみ出ないように調整*/
        margin-right: 10px;/*はみ出ないように調整*/
        padding: 0.5em 0.5em 0.5em 2em;
        }
	</style>
	
</head>

<body>
    <h1>商品登録</h1>
	<form style="margin: auto; width: 350px;" action="./add_product.php" method="post">
		商品名：<input type="text" name="name" value=""><br>
		現在の在庫数：<input type="number" name="stock" min="0" value=""><br>
		商品の価格：<input type="number" name="price" min="0" value=""><br>
		<input type="submit" name="submit" value="登録" class="btn btn-primary">
	</form>
	<a href="./product_list.php">▶商品情報一覧</a><br>
	<a href="./import_product.php">▶csvで追加</a><br>
</body>

</html>
