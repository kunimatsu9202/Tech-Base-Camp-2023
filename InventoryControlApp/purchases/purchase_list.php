<?php

session_start();
require_once("../database/db_connection.php");

if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("Location:login.php");
}

// ########## $optにプルダウン用の商品名格納 #############################

// // require_once "../database/db_connection.php";
// $stmt = $dbh->prepare("SELECT * FROM purchases WHERE flg_delete = 0");
// print("");
// try{
//     $stmt->execute();
//     $stmt = $stmt->fetchALL();
    
// }catch(PDOException $e){
//     return $e->getMessage();
// }

// $opt = "";

// foreach($stmt as $stmt_val){
//     $opt .= "<option value='". $stmt_val['name']; 
//     $opt .= "'>" . $stmt_val['name']. "</option>";
// }

// ######################################################################

if(isset($_POST["delete_id"])) {
    deletePurchase($_POST["delete_id"]);
}

if(!empty($_GET["purchase_name"])) {
    $res = selectPurchase($_GET["purchase_name"]);
    if($res["result"] === true) {
        $list_display = createTable($res["stmt"]);
    } else {
        $list_display = "<tr><td>仕入れ情報の取得に失敗しました</td></tr>";
    }
} else {

    $res = getPurchase();
    if($res["result"] === true) {
        $list_display = createTable($res["stmt"]);
    } else {
        $list_display = "<tr><td>仕入れ情報の取得に失敗しました</td></tr>";
    }
}


// ------------------------------- 関数 --------------------------------------//

// 仕入れ情報と商品情報を取得
function getPurchase()
{
    global $dbh;
    // $stmt = $dbh->prepare("SELECT * FROM purchases");
    $stmt = $dbh->prepare("SELECT * FROM purchases inner join products on purchases.product_id = products.id");

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
        return "仕入れ商品がありません";
    }

    $elm = "";

    while($item = $stmt->fetch()) {

        if ($item['flg_delete_purchase'] == 0) {

            $item['name']=htmlentities($item['name'], ENT_QUOTES, "utf-8");
            $item['quantity']=htmlentities(number_format($item['quantity']), ENT_QUOTES, "utf-8");
            $item['DATE']=htmlentities($item['DATE'], ENT_QUOTES, "utf-8");
            $item['note']=htmlentities($item['note'], ENT_QUOTES, "utf-8");

            $tr = "<tr>
                    <td style = 'text-align:center'>{$item['name']}</td>
                    <td style = 'text-align:right;'>{$item['quantity']}</td>
                    <td style = 'text-align:right;'>{$item['DATE']}</td>
                    <td>{$item['note']}</td>
                    <td>
                    <form style='margin: auto;' action='purchase_list.php' method='post' name='delete_form' onclick='return confirm_delete()'>
                        <button class='reset button-shadow' type='submit' name='delete_id' value={$item['purchase_id']}>削除</button>
                    </form>
                    </td>
                    <td>
                    <form style='margin: auto;' action='edit_purchase.php' method='post'>
                        <button class='reset button2-shadow' type='submit' name='edit_id' value={$item['purchase_id']}>編集</button>
                        <input type='hidden' name='product_id' value={$item['product_id']}>
                        <input type='hidden' name='name' value={$item['name']}>
                        <input type='hidden' name='quantity' value={$item['quantity']}>
                        <input type='hidden' name='date' value={$item['DATE']}>
                        <input type='hidden' name='note' value={$item['note']}>                    
                    </form>
                    </td>
                </tr>";
            $elm .= $tr;

        } elseif($stmt->rowCount() === 1) {
            return "仕入れ商品がありません";
        }
    }

    return $elm;

}

// プロダクトのidとパーチェスのidを関連付けて検索する
function selectPurchase($purchase_name)
{
    global $dbh;
    $stmt = $dbh->prepare("SELECT * FROM purchases inner join products on purchases.product_id = products.id WHERE name = ?");

    try {
        $ret = $stmt->execute([$purchase_name]);
        if($ret === true) {
            return ["result" => true, "stmt" => $stmt];
        } else {
            return ["result" => false, "stmt" => $stmt];
        }

    } catch(PDOException $e) {
        $e->getMessage();
    }

}

// 仕入れ情報を論理削除
function deletePurchase($delete_id)
{
    global $dbh;

    $stmt = $dbh->prepare("UPDATE purchases SET flg_delete_purchase = TRUE WHERE purchase_id = ?");
    $res = $stmt->execute([$delete_id]);

    if($res == true) {
        $_SESSION['flush_messages'] = ['type' => 'success', 'content' => '仕入れ情報を削除しました'];

        $stmt = $dbh->prepare("SELECT * FROM purchases WHERE purchase_id = ?");
        $res = $stmt->execute([$delete_id]);
        $purchase = $stmt->fetch();

        $stmt = $dbh->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->execute([$purchase['product_id']]);
        $product = $stmt->fetch();

        $stock = 0;
        $stock = ((int)$product['stock'] - ((int)$purchase['quantity']));

        $stmt = $dbh->prepare("UPDATE products SET stock = ? WHERE id = ?");
        $stmt->execute([$stock, $purchase['product_id']]);

    } else {
        $_SESSION['flush_messages'] = ['type' => 'danger', 'content' => '仕入れ情報の削除に失敗しました。'];
    }
}


if(isset($_SESSION['flush_messages'])) {
    echo $_SESSION['flush_messages']['content'];
    unset($_SESSION['flush_messages']);
}

?>

<script>
function confirm_delete() {
	var select = confirm("本当に削除しますか？");

	if (!select) {
		/* 💬 delete_form の名前が複数つけられていて、フォームが複数取得されています
		 * これだと、submit を実行できません。
		 * JavaScript からsubmit() を実行する必要はなく、
		 * 削除用のbutton 要素に type='submit' を指定すればよいです。
		 */
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
	<title>仕入れ商品一覧</title>
	<style>
	h1 {
		padding: 0.25em 0.5em;
		/*上下 左右の余白*/
		color: #494949;
		/*文字色*/
		background: transparent;
		/*背景透明に*/
		border-left: solid 5px #8B0000;
		/*左線*/
	}

	tr {
		background-color: #87CEFA;
	}

	body {
		background-color: #B0E0E6
	}

	table {
		width: 95%;
		border-collapse: collapse;
		border-spacing: 0;
	}

	table th,
	table td {
		padding: 10px 0;
		text-align: center;
	}

	table tr:nth-child(odd) {
		background-color: #eee
	}

	.content a {
		font-weight: bold;
	}

	.content a:hover {
		color: #f89174;
		/*文字色*/
		text-decoration: underline;
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
	</style>
	<?php require_once "./header2.html" ?>
</head>

<body>
	<form action="" method="GET">
		仕入れ情報検索：<input type="text" name="purchase_name" value="">
		<input type="submit" value="検索" class="btn btn-primary">
	</form>
	<a href="purchase_list.php">一覧画面へ戻す</a>
	<h1 style="margin: 10px auto; width: 1365px;">仕入れ商品一覧</h1>
	<br>
	<a href="./register_purchase.php">▶仕入れ情報登録画面</a>
	<br>
	<a href="./export_purchase.php">▶仕入れ情報をCSVに出力</a>
	<table style="margin: 10px auto; width: 1300px auto;" border="2">
		<tr>
			<th>商品名</th>
			<th>仕入れ数</th>
			<th>仕入れた日</th>
			<th>備考</th>
			<th></th>
			<th></th>

		</tr>
		<?php
            print $list_display;

?>
	</table>
	<br>
	<a href="./register_purchase.php">▶仕入れ情報登録画面</a>
	<br>
	<a href="./export_purchase.php">▶仕入れ情報をCSVに出力</a>

</body>

</html>
