<?php
$dir = "../upload/";
$page = "./purchase_list.php";

$msg = " ";

if(!empty($_FILES["export_file"]["tmp_name"])){
    if(is_uploaded_file($_FILES["export_file"]["tmp_name"])){
        $file_tmp_name = $_FILES["export_file"]["tmp_name"];
        $file = $dir . basename($_FILES["export_file"]["name"]);
        move_uploaded_file($_FILES["export_file"]["tmp_name"],$file);

        $result = getPurchase();
        if(is_array($result)){
            $handle = fopen($file, 'r+');
            // flock($handle,LOCK_EX);
            $msg = "仕入れ情報をファイルに出力しました";
            foreach($result as $value){
                fputcsv($handle,$value);
                fwrite($handle,"\r\n");
            }
            // flock(LOCK_UN);
            fclose($handle);
            result($msg,$page);
        }
    }
}


function getPurchase(){
    require_once "../database/db_connection.php";

    $stmt = $dbh->prepare("SELECT purchase_id,product_id,quantity,date,note FROM purchases WHERE flg_delete_purchase = 0");
    // $stmt = $dbh->prepare("SELECT * FROM purchases inner join products on purchases.product_id = products.id");
    
    try{
        $stmt->execute();
        $ret = $stmt->fetchall(PDO::FETCH_ASSOC);
        if($stmt->rowCount() > 0){
            return $ret;
        }
        else{
            return "仕入れ情報がありません";
        }
        
    }catch(PDOException $e){
        $e->getMessage();
    }
}

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
<html lang="ja">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<head>
    <meta charset="UTF-8">
    <title>仕入れ情報をCSVファイルに出力</title>
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
        background-color: #F3FFD8;
        box-shadow: 0px 0px 0px 10px #F3FFD8;/*線の外側*/
        border: dashed 2px #0000AA ;/*破線*/
        border-radius: 9px;
        margin-left: 10px;/*はみ出ないように調整*/
        margin-right: 10px;/*はみ出ないように調整*/
        padding: 0.5em 0.5em 0.5em 2em;
    }
    
   </style>
</head>
<body>
    <h1>在庫情報をCSVファイルに出力</h1>
    <?php if(!$msg = " "){
        echo $msg;
    } ?>

    <form style="margin: auto; width: 620px;" action="" method="POST" enctype="multipart/form-data">
        出力するファイル：<input type="file" name="export_file"><br>
        <input type="submit" value="出力">
    </form>
    <a href="./purchase_list.php">戻る</a>
</body>
</html>
