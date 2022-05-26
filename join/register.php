<?php

//登録してない人を返す
if(empty($_POST)){
    header('Location: index.php');
    exit();
}


//htmlspecialchars
function h($value){
    return htmlspecialchars($value,ENT_QUOTES);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" type="text/css" href="style_join.css">
    <title>プログラミング実習</title>
</head>

<body>
<main>
    <div class="content_add">
    <div><span class="title">確認画面</span><a class="btn" href="index.php">書き直す</a></div>
    <hr>
<form>
    <table class="join_table">
        <tr><th>会社名</th> <td><?php echo($_POST['name']);?></td></tr>
        <tr><th>担当者名</th> <td><?php echo($_POST['manager']);?></td></tr>
        <tr><th>電話番号</th> <td><?php echo($_POST['phone']);?></td></tr>
        <tr><th rowspan="3">住所</th> <td>郵便番号<?php echo($_POST['postal_code']);?></td></tr>
            <tr><td>都道府県<?php echo($_POST['prefecture_code']);?></td></tr>
            <tr><td>市区町村<?php echo($_POST['address']);?></td></tr>
        <tr><th>メールアドレス</th> <td><?php echo($_POST['email']);?></td></tr>
        <tr><th>プレフィックス</th> <td><?php echo($_POST['prefix']);?></td></tr>
    </table>
    <hr>
    <a href="#" class="long_btn">新規登録</a>
</form>

    <!--
    value="<?php //if(isset($_POST['name'])){echo h($_POST['name']);}?>"
    value="<?php //if(!empty($_POST['name'])){echo h($_POST['name']);}?>"
    -->

    </div>
</main>
</body>