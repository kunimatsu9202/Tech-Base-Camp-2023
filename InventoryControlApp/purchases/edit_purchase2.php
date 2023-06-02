<?php
session_start();

if(isset($_POST["submit"])){
    $name = htmlentities($_POST["name_product"], ENT_QUOTES, "utf-8");
    $id = htmlentities((int)$_POST["id"], ENT_QUOTES, "utf-8");
    $product_id = htmlentities((int)$_POST["product_id"], ENT_QUOTES, "utf-8");
    $quantity = htmlentities((int)$_POST["quantity"], ENT_QUOTES, "utf-8");
    $date = htmlentities($_POST["date"], ENT_QUOTES, "utf-8");
    $note = htmlentities($_POST["note"], ENT_QUOTES, "utf-8");

    require_once("../database/db_connection.php");

    $stmt=$dbh->prepare("SELECT quantity FROM purchases WHERE purchase_id = ?");
    $stmt->execute([$id]);
    $purchase = $stmt->fetch(); 
    $quo = $purchase["quantity"]; //変更前の仕入れ数

    $stmt=$dbh->prepare("UPDATE purchases SET quantity = ?, date = ?, note = ? WHERE purchase_id = ?");
    $stmt->execute([$quantity, $date, $note, $id]);

//productテーブルのstock変更
    $stmt=$dbh->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    $stock = $product["stock"] + ($quantity-$quo);//$quantity-$quo:変更前と変更後での仕入れ数の差

    $stmt=$dbh->prepare("UPDATE products SET stock = ? WHERE id = ?");
    $stmt->execute([$stock, $product_id]);

    $_SESSION['flush_messages'] = ['type' => 'success', 'content' => '仕入れ情報を更新しました'];

    //header("Location: ./purchase_list.php");
}else{
    echo "編集できませんでした";
}
if(isset($_SESSION['flush_messages'])){
    echo $_SESSION['flush_messages']['content'];
    unset($_SESSION['flush_messages']);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<title>仕入れ情報編集完了</title>
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
      bottom: 150;
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

    <h1>仕入れ情報一覧</h1>
	<body>
        <table style='margin: auto; width: 350px;' border='2'>
	<tr>
            <th>名前</th>
			<th>仕入れ数</th>
			<th>日付</th>
			<th>備考</th>
            </tr>
    <tr>
        <td><?php echo $name; ?></td>
        <td><?php echo $quantity; ?></td>
        <td><?php echo $date; ?></td>
        <td><?php echo $note; ?></td>
		</tr>
    </table>
    </body>
    <a href='./purchase_list.php'>▶仕入れ商品一覧画面</a>

</html>

