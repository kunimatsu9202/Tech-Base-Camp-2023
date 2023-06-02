<?php
// フォームが送信された場合に処理を行う

    $dir = "../upload/";
    $page = "./import_product.php";

    if (!empty($_FILES['csv_file']['tmp_name'])) {
        if(is_uploaded_file($_FILES["csv_file"]["tmp_name"])){
            $file_tmp_name = $_FILES["csv_file"]["tmp_name"];
            $file = $dir . basename($_FILES["csv_file"]["name"]);
            move_uploaded_file($_FILES["csv_file"]["tmp_name"],$file);

            // CSVファイルを読み込む
            $handle = fopen($file, 'r');
            // flock($handle,LOCK_EX);
            $msg = "商品情報をインポートしました";
            // データを1行ずつ読み込み、商品情報を追加する
            while ($data = fgetcsv($handle)) {
                $product_name = $data[0];
                $stock = $data[1];
                $price = $data[2];
                if($stock >= 0 && $price >= 0){
                    require_once "../database/db_connection.php";
                    try{
                        $stmt = $dbh->prepare("SELECT name FROM products WHERE name = ?");
                        $stmt->execute([$data[0]]);
                        if($stmt->rowCount() === 0){
                            $stmt = $dbh->prepare("INSERT INTO products(name,stock,price) VALUES(?,?,?)");
                            $res = $stmt->execute([$product_name,$stock,$price]);
                            if($res === false){
                                $msg =  "商品情報の追加に失敗しました";
                                result($msg);
                            }
                        }
                        else{
                            $stmt = $dbh->prepare("UPDATE products SET stock = ?, flg_delete = false WHERE name = ?");
                            $res = $stmt->execute([$data[1],$data[0]]);
                        }
                    
                    }catch(PDOException $e){
                        echo $e->getMessage();
                    }
                }else{
                    $msg = "<p>在庫数か値段の値が不正です<p>";
                   
                }
            }
        // flock(LOCK_UN);
        fclose($handle);
        result($msg,$page);
        }
    }
    

// ------------------------------------- 関数 --------------------------------------------//

// インポート結果をcsv_insert.tmplで表示
function result($msg,$page){

    # テンプレート読み込み
    $conf = fopen("../tmpl/csv_insert.tmpl","r") or die;
    $size = filesize("../tmpl/csv_insert.tmpl");
    $data = fread($conf , $size);
    fclose($conf);

    # 文字置き換え
    $data = str_replace("!announce!", $msg, $data);
    $data = str_replace("!page!", $page, $data);
    
    # 表示
    echo $data;
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>商品情報追加</title>
	<style>
	h1 {
		padding: 0.25em 0.5em;/*上下 左右の余白*/
		color: #494949;/*文字色*/
		background: transparent;/*背景透明に*/
        border-left: solid 5px #4B0082;/*左線*/
        font-size: 40px;
        font-weight: lighter;
	}
    body {
	    margin: 250px;
	    position: absolute;
	    top: 0;
	    right: 0;
	    bottom: 250;
	    left: 0;
	    background-color: #FFC;
	    box-shadow: 0px 0px 0px 10px #FFC;/*線の外側*/
	    border: dashed 2px #32CD32;/*破線*/
	    border-radius: 9px;
	    margin-left: 10px;/*はみ出ないように調整*/
	    margin-right: 10px;/*はみ出ないように調整*/
	    padding: 0.5em 0.5em 0.5em 2em;
    }
	
	</style>
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
		integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> -->
</head>

<body>
	<h1>商品情報追加</h1>
	<?php if(!empty($err_msg)){
        echo $err_msg;
    }
    ?>
	<form style="margin: auto; width: 350px;" action="./import_product.php" method="post" enctype="multipart/form-data">
		csvファイル：<input type="file" name="csv_file"><br>
		<input type="submit" value="追加" class="btn btn-primary">
	</form>
    <br>
	<a href="./add_product.php">戻る</a>
</body>

</html>
