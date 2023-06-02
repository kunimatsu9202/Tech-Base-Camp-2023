<?php

session_start();

# 日付を現在時刻に初期化
$date = date('Y-m-d');

########## $optにプルダウン用の商品名格納 #############################

require_once "../database/db_connection.php";
$stmt = $dbh->prepare("SELECT * FROM products WHERE flg_delete = 0");
try{
    $stmt->execute();
    $stmt = $stmt->fetchALL();
    
}catch(PDOException $e){
    return $e->getMessage();
}

$opt = "";

foreach($stmt as $stmt_val){
    $opt .= "<option value='". $stmt_val['id']; 
    $opt .= "'>" . $stmt_val['name']. "</option>";
}

######################################################################


################# formから入力されたデータをデータベースに登録 #################################################

# postの値が入っているか
if ($_SERVER["REQUEST_METHOD"] === "POST"){
    # 未入力チェック
    if(!empty($_POST["quantity"]) && !empty($_POST["date"])){

        # $recordに選択された商品のレコードが格納
        $record = selectProduct($_POST["product_id"]);
        
        # purchaseテーブルに入力された仕入れ情報を登録
        $stmt = $dbh -> prepare("INSERT INTO purchases(product_id, quantity, date, note) values(?, ?, ?, ?)");
        $stmt -> execute([$record["id"], $_POST['quantity'], $_POST["date"], $_POST['note']]);
                
            if($stmt -> rowCount() > 0){
                $msg = '仕入れ情報を追加しました。';
            }
            else{
                $msg = '仕入れ情報の追加に失敗しました。';
            }
            # sumにproductsテーブルの在庫数を代入し、postで送られた仕入れ数を在庫数に足す
            $sum = $record["stock"];
            $sum += $_POST["quantity"];
            $msg = updateStock($_POST["quantity"],$sum,$record["id"],$record["flg_delete"]);
            
            # result()で完了画面の表示
            result($record["name"],$_POST["quantity"],$msg);

    }else{
        $msg = "未入力の項目があります。";
    }

}

###############################################################################################################


// -----------------関数--------------------//


// 仕入れた際、productsテーブルの在庫数に仕入れ数を足す
function updateStock($purchase_stock,$sum_stock,$product_id,$flg){
    global $dbh;

    if ($flg) {
        $stmt = $dbh->prepare("UPDATE products SET stock = ?, flg_delete = 0 WHERE id = ? ");
        try{
            $stmt->execute([$purchase_stock,$product_id]);
            
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }else{

        $stmt = $dbh->prepare("UPDATE products SET stock = ? WHERE id = ? ");
        try{
            $stmt->execute([$sum_stock,$product_id]);
            
        }catch(PDOException $e){
            return $e->getMessage();
        }

    }
    
}


// 商品idに一致するカラムを検索して取得
function selectProduct($product_id){
    global $dbh;

    $stmt = $dbh->prepare("SELECT * FROM products WHERE id = ? ");
    try{
        $stmt->execute([$product_id]);
        $ret = $stmt->fetch();
        return $ret;

    }catch(PDOException $e){
        return $e->getMessage();
    }
}

// 仕入れ結果をresult.tmplに出力
function result($name,$quantity,$msg){

    # テンプレート読み込み
    $conf = fopen("../tmpl/result.tmpl","r+") or die;
    $size = filesize("../tmpl/result.tmpl");
    $data = fread($conf , $size);
    fclose($conf);

    # 文字置き換え
    $data = str_replace("!product_name!", $name, $data);
    $data = str_replace("!product_quantity!", $quantity, $data);
    $data = str_replace("!announce!", $msg, $data);
    
    
    # 表示
    echo $data;
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<title>仕入れ情報登録</title>
    <style>
    
    h1 {
      padding: 0.25em 0.5em;/*上下 左右の余白*/
      color: #494949;/*文字色*/
      background: transparent;/*背景透明に*/
      border-left: solid 5px #FF4F02;/*左線*/
    }
    body {
        margin: 250px;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 250;
        left: 0;
        background: #fffde8;
        box-shadow: 0px 0px 0px 10px #fffde8;/*線の外側*/
        border: dashed 2px #ffb03f ;/*破線*/
        border-radius: 9px;
        margin-left: 10px;/*はみ出ないように調整*/
        margin-right: 10px;/*はみ出ないように調整*/
        padding: 0.5em 0.5em 0.5em 2em;
}
	</style>

</head>
<?php if(!empty($msg)){
    echo $msg;
} ?>

<body>
    <h1>仕入れ情報登録</h1>
	<form style="margin: auto; width: 350px;" method="post" action="register_purchase.php">
		商品名：<select name='product_id'><?php echo $opt; ?></select><br>
		仕入数：<input type="number" step="1" min="1" name="quantity" value=""><br>
		仕入日：<input type="date" name="date" value="<?= $date; ?>"><br>
		備考：<textarea rows="3" cols="50" name="note"></textarea><br>
		<input type="submit" value="仕入れ確定" class="btn btn-primary">
		
	</form>
    <a href="./purchase_list.php">▶仕入れ情報一覧</a>
</body>

</html>
