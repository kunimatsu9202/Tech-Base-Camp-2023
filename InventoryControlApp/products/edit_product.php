<?php

session_start();
require_once("../database/db_connection.php");

if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]){
    header("Location:../loginout/login.php");
}

if ( isset($_POST["success"]) ) {
    editProduct($_POST["edit_id"]);
    $_POST['name'] = htmlentities($_POST['name'], ENT_QUOTES, "utf-8");
    $_POST['stock'] = htmlentities($_POST['stock'], ENT_QUOTES, "utf-8");
    $_POST['price'] = htmlentities($_POST['price'], ENT_QUOTES, "utf-8");
    print("更新完了しました");
    # ここにリンク＆更新内容
		/* 💬 更新処理完了時のHTMLの出力は、別ファイルに分けてもよかったかもしれません
		 * たとえば、 complete_edit_product.php というファイルを作って、header()関数で遷移させるなど
		 */
    print "<title>商品情報編集完了</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css' integrity='sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2' crossorigin='anonymous'>
	       <link rel ='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
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
		background-color: #CCFFFF;
		box-shadow: 0px 0px 0px 10px #CCFFFF;/*線の外側#fffde8*/
        border: dashed 2px #32CD32 ;/*破線*/
		border-radius: 9px;
		margin-left: 10px;/*はみ出ないように調整*/
		margin-right: 10px;/*はみ出ないように調整*/
		padding: 0.5em 0.5em 0.5em 2em;
	}
	</style>";
    print "<h1>商品一覧</h1>";
	print "<body><table style='margin: auto; width: 350px;' border='2'>";
	print "<tr>
			<th>商品名</th>
			<th>在庫</th>
			<th>単価</th>
            </tr>";
    print "<tr>
            <td>{$_POST['name']}</td>
            <td>{$_POST['stock']}</td>
            <td>{$_POST['price']}</td>
		</tr>";
    print "</table></body>";
    print "<a href='product_list.php'>▶商品一覧画面</a>";
    exit();
}else{
    $item = getProduct();
}

// ------------------------------------- 関数 --------------------------------------

// 商品idで編集対象を検索
function getProduct(){
    global $dbh;

    $stmt = $dbh->prepare("SELECT * FROM products where id = ?");
    try{
        $ret = $stmt->execute([$_POST["edit_id"]]);
        $array = $stmt -> fetch();
        if($ret === true){
            return ["result" => true, "stmt" => $array];
        }
        else{
            return ["result" => false, "stmt" => $array];
        }

    }catch(PDOException $e){
        $e->getMessage();
    }

}

// 編集された内容をデータベースに更新
function editProduct($edit_id){
    global $dbh;
    $stmt = $dbh->prepare("UPDATE products SET name = ?, stock = ?, price = ? WHERE id = ?");
    $res = $stmt->execute([$_POST['name'], $_POST['stock'], $_POST['price'], $_POST['edit_id']]);

    if($res == true){
        $_SESSION['flush_messages'] = ['type' => 'success', 'content' => '商品情報を更新しました'];
    }
    else{
        $_SESSION['flush_messages'] = ['type' => 'danger', 'content' => '商品情報を更新できませんでした'];
    }
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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
		integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<title>商品情報編集</title>
</head>
<?php if(!empty($msg)){
    echo $msg;
} ?>

<style>
h1 {
	padding: 0.25em 0.5em;/*上下 左右の余白*/
	color: #494949;/*文字色*/
	background: transparent;/*背景透明に*/
	border-left: solid 5px #FF773E;/*左線*/
}

body {
	margin: 250px;
	position: absolute;
	top: 0;
	right: 0;
	bottom: 30;
	left: 0;
	background: #FFDAB9;
	box-shadow: 0px 0px 0px 10px #FFDAB9;/*線の外側*/
	border: dashed 2px #FF773E;/*破線*/
	border-radius: 9px;
	margin-left: 10px;/*はみ出ないように調整*/
	margin-right: 10px;/*はみ出ないように調整*/
	padding: 0.5em 0.5em 0.5em 2em;
}
</style>


<body>
	<h1>
		商品情報編集
	</h1>
	<form style="margin: auto; width: 220px;" method="post" action="edit_product.php">
		<!--
			💬 valueの値を "" で囲んでおかないと、HTMLとして正しく解釈されない可能性があります
      たとえば、商品名に半角スペースが入っていた場合などです
    -->
		商品名:<input type="text" name="name" value="<?= $item['stmt']['name'] ?>">
		<br>
		在庫:<input type="number" name="stock" min=0 value="<?= $item['stmt']['stock'] ?>">
		<br>
		価格:<input type="number" name="price" min=0 value="<?= $item['stmt']['price'] ?>">
		<br>
		<input type="submit" value="更新" class="btn btn-primary">
		<input type="hidden" name="success">
		<input type="hidden" name="edit_id" value=<?= $_POST['edit_id'] ?>>
	</form>
	<br>
	<a href="product_list.php">▶商品一覧画面</a>
</body>

</html>
