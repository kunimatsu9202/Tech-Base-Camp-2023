<?php


session_start();
require_once("../database/db_connection.php");

if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("Location:login.php");
}

# データ削除
if(isset($_POST["delete_id"])) {

    global $dbh;

    $stmt = $dbh -> prepare("UPDATE products SET flg_delete = TRUE WHERE id = ? AND name = ? ");
    $res = $stmt->execute([$_POST["delete_id"], $_POST["name"]]);

    # エラーメッセージ出力方法保留 resで判定

    if($res == true) {
        $_SESSION['flush_messages'] = ['type' => 'success', 'content' => '商品情報を削除しました'];
    } else {
        $_SESSION['flush_messages'] = ['type' => 'danger', 'content' => '商品情報の削除に失敗しました。'];
    }
}

if(isset($_SESSION['flush_messages'])) {
    echo $_SESSION['flush_messages']['content'];
    unset($_SESSION['flush_messages']);
}


if(!empty($_GET["product_name"])) {
    $product_name = htmlentities($_GET["product_name"], ENT_QUOTES, "utf-8");
    $res = selectProduct($product_name);
    if($res["result"] === true) {
        $list_display = createTable($res["stmt"]);
    } else {
        $list_display = "<tr><td>データの取得に失敗しました</td></tr>";
    }
} else {

    $res = getProduct();
    if($res["result"] === true) {
        $list_display = createTable($res["stmt"]);
    } else {
        $list_display = "<tr><td>データの取得に失敗しました</td></tr>";
    }
}

//----------------------------- 関数 -------------------------------------

// 商品一覧
function selectProduct($product_name)
{
    // require_once("db_connection.php");
    global $dbh;

    $stmt = $dbh->prepare("SELECT * FROM products WHERE name = ?");
    try {
        $ret = $stmt->execute([$product_name]);
        if($ret === true) {
            return ["result" => true, "stmt" => $stmt];
        } else {
            return ["result" => false, "stmt" => $stmt];
        }

    } catch(PDOException $e) {
        $e->getMessage();
    }
}

// 商品一覧(product.php)
function getProduct()
{
    global $dbh;

    $stmt = $dbh->prepare("SELECT * FROM products WHERE flg_delete = 0");
    try {
        $ret = $stmt->execute();
        if($ret === true) {
            return ["result" => true, "stmt" => $stmt];
        } else {
            return ["result" => false, "stmt" => $stmt];
        }

    } catch(PDOException $e) {
        $e->getMessage();
    }
}

// テーブルタグ作成
function createTable($stmt)
{
    if($stmt->rowCount() === 0) {
        return "登録商品がありません";
    }
    $elm = "";
    while($item = $stmt->fetch() ) {
        if($item["flg_delete"] == 0) {
            

            $item['name']=htmlentities($item['name'], ENT_QUOTES, "utf-8");
            $item['stock']=htmlentities(number_format($item['stock']), ENT_QUOTES, "utf-8");
            $item['price']=htmlentities(number_format($item['price']), ENT_QUOTES, "utf-8");
            // Number($item['price']).toLocaleString();

            $tr = "<tr>
                    <td style = 'text-align:center'>{$item['name']}</td>
                    <td style = 'text-align:right;'>{$item['stock']}</td>
                    <td style = 'text-align:right;'>{$item['price']}</td>
                    <td>
                    <form style='margin: auto;' action='./product_list.php' method='post' name='delete_form' onclick='return confirm_delete()'>
                        <button class='reset button-shadow' type='submit' name='delete_id' value={$item['id']} >削除</button>
                        <input type='hidden' name='name' value='{$item['name']}'>
                    </form>
                    </td>
                    <td>
                    <form style='margin: auto;' action = 'edit_product.php' method = 'post'>
                        <button class='reset button2-shadow' type='submit' name='edit_id' value={$item['id']}>編集</button>
                        <input type='hidden' name='name' value='{$item['name']}'>
                        <input type='hidden' name='stock' value='{$item['stock']}'>
                        <input type='hidden' name='price' value='{$item['price']}'>
                    </form>
                    </td>
                </tr>";
            $elm .= $tr;
        } elseif($stmt->rowCount() === 1) {
            return "登録商品がありません";
        }
    }
    return $elm;

}

?>

<script>
function confirm_delete() {
	var select = confirm("本当に削除しますか？");

	if (!select) {
		alert("削除をキャンセルしました");
		return select;
	}


}
</script>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
		integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<title>商品一覧</title>
	<style>
	h1 {
		padding: 0.25em 0.5em;
		/*上下 左右の余白*/
		color: #494949;
		/*文字色*/
		background: transparent;
		/*背景透明に*/
		border-left: solid 5px #7db4e6;
		/*左線*/
	}

	tr {
		background-color: #FFDAB9;
	}

	body {
		background-color: blanchedalmond
	}

	table {
		width: 95%;
		border-collapse: collapse;
		border-spacing: 0;
	}

	/* table th,table td{
        padding: 10px 0;
        text-align: right;
} */

	table tr:nth-child(odd) {
		background-color: #eee
	}

	.button-shadow {
		text-align: center;
		box-sizing: border-box;
		display: block;
		max-width: 60px;
		width: 60%;
		margin: auto;
		background: #777fff;
		/* 色変更可能 */
		color: #fff;
		font-weight: bold;
		padding: 13px 10px 10px;
		border-radius: 5px;
		border-bottom: 5px solid rgba(0, 0, 0, 0.3);
	}

	.button-shadow:hover {
		animation: 1s flash;
	}

	.button-shadow:active,
	.button-shadow:focus {
		border-bottom-width: 0;
		margin-top: 5px;
		background: #ff9300;
		/* 色変更可能 */
	}

	.button2-shadow {
		text-align: center;
		box-sizing: border-box;
		display: block;
		max-width: 60px;
		width: 60%;
		margin: auto;
		background: #466aaa;
		/* 色変更可能 */
		color: #fff;
		font-weight: bold;
		padding: 13px 10px 10px;
		border-radius: 5px;
		border-bottom: 5px solid rgba(0, 0, 0, 0.3);
	}

	.button2-shadow:hover {
		animation: 1s flash;
	}

	.button2-shadow:active,
	.button-shadow:focus {
		border-bottom-width: 0;
		margin-top: 5px;
		background: #ff9300;
		/* 色変更可能 */
	}

	@keyframes flash {
		from {
			opacity: 0.5;
		}

		to {
			opacity: 1;
		}
	}

	/* table th, table td{
        padding: 10px 0;
        text-align: center;
    } */
	</style>
	<?php require_once "header1.html" ?>
</head>

<body>
	<form action="" method="GET">
		商品検索：<input type="text" name="product_name">
		<input type="submit" value="検索" class="btn btn-primary">
	</form>
	<a href="product_list.php">一覧画面へ戻す</a>

	<h1 style="margin: auto; width: 1375px;">商品一覧</h1>
    <br>
    <a href="add_product.php">▶商品登録画面</a><br>
	<table style="margin: 10px auto; width: 1300px auto;" border="2">
		<tr>
			<th>商品名</th>
			<th>在庫</th>
			<th>単価</th>
			<th></th>
			<th></th>

		</tr>

		<?php print $list_display; ?>
	</table><br>
    
	<a href="add_product.php">▶商品登録画面</a><br>
</body>

</html>
