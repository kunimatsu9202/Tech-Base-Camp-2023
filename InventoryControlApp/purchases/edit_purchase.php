<?php
  session_start();

  if(isset($_POST["edit_id"])){
      require_once "../database/db_connection.php";
  }

?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<title>仕入れ情報編集</title>
	<style>
	h1 {
      padding: 0.25em 0.5em;/*上下 左右の余白*/
      color: #494949;/*文字色*/
      background: transparent;/*背景透明に*/
      border-left: solid 5px #0000AA;/*左線*/
    }

  body {
		  margin: 250px;
      position: absolute;
      top: 0;
      right: 0;
      bottom: 250;
      left: 0;
      background: #B0E0E6;
      box-shadow: 0px 0px 0px 10px #B0E0E6;/*線の外側*/
      border: dashed 2px #0000AA ;/*破線*/
      border-radius: 9px;
      margin-left: 10px;/*はみ出ないように調整*/
      margin-right: 10px;/*はみ出ないように調整*/
      padding: 0.5em 0.5em 0.5em 2em;
    }


	</style>

</head>

<body>
	<h1>仕入れ情報編集</h1>
	<form style="margin: auto; width: 220px;" action="./edit_purchase2.php" method="POST">
		名前：<?php echo $_POST['name'];?><br>
		仕入れ数：<input type="number" name="quantity" min="0" value="<?php echo $_POST['quantity'];?>"><br>
		仕入れ日：<br><input type="date" name="date" value="<?php echo $_POST['date'];?>"><br>
		備考：<textarea rows="3" cols="50" name="note"><?php echo $_POST['note'];?></textarea><br>
		<input type="hidden" name="id" value=<?php echo $_POST['edit_id'];?>><br>
		<input type="submit" name="submit" value="更新" class="btn btn-primary">
    <input type="hidden" name="name_product" value=<?php echo $_POST['name']; ?>>
		<input type="hidden" name="product_id" value=<?php echo $_POST['product_id']; ?>>
	</form>
	<br>
	<a href="./purchase_list.php">▶仕入れ情報一覧</a>

</body>

</html>
