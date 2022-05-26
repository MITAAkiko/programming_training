<?php
//session_start();
require('../dbconnect.php');
require('../config.php');


//htmlspecialchars
function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}

//バリデーションチェック
//エラーチェック
function isError($err)
{
    $nonerror=[
        'name' => '',
        'manager' => '',
        'phone' => '',
        'postal_code' => '',
        'prefecture_code' => '',
        'address' => '',
        'email' => '',
        'prefix' => '',
    ];
    return $err !== $nonerror;
}
//初期値
$error = [
    'name' => '',
    'manager' => '',
    'phone' => '',
    'postal_code' => '',
    'prefecture_code' => '',
    'address' => '',
    'email' => '',
    'prefix' => '',
];
$isError = '';

//エラーについて
if (!empty($_POST)) {
    if (($_POST['name'])==='') {
        $error['name']='blank';
    }
    if (strlen($_POST['name'])>64) {
        $error['name']='long';
    }

    if (($_POST['manager'])==='') {
        $error['manager']='blank';
    }
    if (strlen($_POST['manager'])>32) {
        $error['manager']='long';
    }

    if (($_POST['phone'])==='') {
        $error['phone']='blank';
    } elseif (!preg_match('/^[0-9]+$/', $_POST['phone'])) { //空文字ダメの半角数値
        $error['phone']='type';
    } elseif (strlen($_POST['phone'])>11) {
        $error['phone']='long';
    }

    if (($_POST['postal_code'])==='') {
        $error['postal_code']='blank';
    } elseif (!preg_match("/^[0-9]+$/", $_POST['postal_code'])) { //空文字ダメの半角数値
        $error['postal_code']='type';
    } elseif (strlen($_POST['postal_code'])>7) {
        $error['postal_code']='long';
    }
    if (($_POST['prefecture_code'])==='') {
        $error['prefecture_code']='blank';
    } elseif (($_POST['prefecture_code'])==="empty") {
        $error['prefecture_code']='blank';
    } elseif (!preg_match("/^[0-9]+$/", $_POST['prefecture_code'])) { //空文字ダメの半角数値
        $error['prefecture_code']='type';
    } elseif (($_POST['prefecture_code'])>47 || ($_POST['prefecture_code'])<1) {
        $error['prefecture_code']='long47';
    }
    if (($_POST['address'])==='') {
        $error['address']='blank';
    } elseif (strlen($_POST['address'])>100) {
        $error['address']='long';
    }
    if (($_POST['email'])==='') {
        $error['email']='blank';
    } elseif (!preg_match("/^[a-zA-Z0-9_+-]+(.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/", $_POST['email'])) {
        $error['email']='type';
    } elseif (strlen($_POST['email'])>100) {
        $error['email']='long';
    }

    if (($_POST['prefix'])==='') {
        $error['prefix']='blank';
    } elseif (strlen($_POST['prefix'])>16) {
        $error['prefix']='long';
    } elseif (!preg_match("/^[0-9a-zA-Z]+$/", $_POST['prefix'])) {//半角英数字、空文字NG
        $error['prefix']='type';
    }
}

//エラーがある.ファンクションそのまま使えないから変数に代入
$isError = isError($error);

//エラーがない時にデータベースに登録する
if (!empty($_POST)) {
    if (!$isError) {
        $statement = $db->prepare('INSERT INTO companies 
            SET company_name=?, manager_name=?,phone_number=?,
            postal_code=?,prefecture_code=?,address=?,
            mail_address=?,prefix=?,created=NOW(),modified=NOW()');
        $statement->bindParam(1, $_POST['name'], PDO::PARAM_STR);
        $statement->bindParam(2, $_POST['manager'], PDO::PARAM_STR);
        $statement->bindParam(3, $_POST['phone'], PDO::PARAM_STR);
        $statement->bindParam(4, $_POST['postal_code'], PDO::PARAM_STR);
        $statement->bindParam(5, $_POST['prefecture_code'], PDO::PARAM_STR);
        $statement->bindParam(6, $_POST['address'], PDO::PARAM_STR);
        $statement->bindParam(7, $_POST['email'], PDO::PARAM_STR);
        $statement->bindParam(8, $_POST['prefix'], PDO::PARAM_STR);
        echo $ret=$statement->execute();
        header('Location:../');
        exit();
    }
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
    <div><span class="title">会社登録</span><a class="btn" href="../index.php">戻る</a></div>
    <hr>
<form action="" method="post">
    <table class="join_table">
        <tr><th>会社名</th> 
            <td>
                <input class="text_join" type="text" name="name"
                    value="<?php if (!empty($_POST['name'])) {
                            echo h($_POST['name']);
                           }?>">
                <?php if ($error['name']==='blank') : ?>
                    <p class="error">※会社名を入力してください</p>
                <?php endif; ?>
                <?php if ($error['name']==='long') : ?>
                    <p class="error">※64文字以内で入力してください</p>
                <?php endif; ?>
            </td>
        </tr>
        <tr><th>担当者名</th> 
            <td>
                <input class="text_join" type="text"name="manager" 
                    value="<?php if (!empty($_POST['manager'])) {
                            echo h($_POST['manager']);
                           }?>">
                <?php if ($error['manager']==='blank') : ?>
                    <p class="error">※担当者名を入力してください</p>
                <?php endif; ?>
                <?php if ($error['manager']==='long') : ?>
                    <p class="error">※32文字以内で入力してください</p>
                <?php endif; ?>
            </td>
        </tr>
        <tr><th>電話番号</th> 
            <td>
                <input class="text_join" type="text" name="phone"
                    value="<?php if (!empty($_POST['phone'])) {
                            echo h($_POST['phone']);
                           }?>">
                    <?php if ($error['phone']==='blank') : ?>
                        <p class="error">※電話番号を入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['phone'] === 'type') :?>
                        <p class="error">※半角数字で入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['phone'] === 'long') :?>
                        <p class="error">※11字以内で入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th rowspan="3">住所</th> 
            <td>郵便番号 <input class="text_join_address" type="text" name="postal_code"
                value="<?php if (!empty($_POST['postal_code'])) {
                        echo h($_POST['postal_code']);
                       }?>">
                    <?php if ($error['postal_code']==='blank') : ?>
                        <p class="error">※郵便番号を入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['postal_code'] === 'type') : ?>
                        <p class="error">※半角数字で入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['postal_code'] === 'long') : ?> 
                        <p class="error">※7字で入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
            <tr><td>都道府県<select class="select_address" name="prefecture_code">
                    <option value="empty">選択してください</option>
                    <?php foreach (PREFECTURES as $number => $value) : ?>
                    <option value="<?php echo $number ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                    </select>
                    <?php if ($error['prefecture_code']==='blank') : ?>
                        <p class="error">※選択してください</p>
                    <?php endif; ?>
                    <?php if ($error['prefecture_code'] === 'long') : ?>
                        <p class="error">※正しく選択してください</p>
                    <?php endif; ?>
                    <?php if ($error['prefecture_code'] === 'type') : ?>
                        <p class="error">※正しく選択してください</p>
                    <?php endif; ?>
                    <?php if ($error['prefecture_code'] === 'error') :?>
                        <p class="error">※もう一度入力してください</p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr><td>市区町村 <input class="text_join_address" type="text" name="address"
                value="<?php if (!empty($_POST['address'])) {
                        echo h($_POST['address']);
                       }?>">
                    <?php if ($error['address']==='blank') : ?>
                        <p class="error">※市区町村を入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['address']==='long') : ?>
                        <p class="error">※100文字以内で入力してください</p>
                    <?php endif; ?>
                </td>
            </tr>
        <tr><th>メールアドレス</th> 
            <td>
                <input class="text_join" type="text" name="email"
                    value="<?php if (!empty($_POST['email'])) {
                            echo h($_POST['email']);
                           }?>">
                    <?php if ($error['email']==='blank') : ?>
                        <p class="error">※メールアドレスを入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['email'] === 'long') : ?>
                        <p class="error">※長すぎるため使用できません</p>
                    <?php endif; ?>
                    <?php if ($error['email'] === 'type') : ?>
                        <p class="error">※正しく入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th>プレフィックス</th> 
            <td>
                <input class="text_join" type="text" name="prefix"
                    value="<?php if (!empty($_POST['prefix'])) {
                            echo h($_POST['prefix']);
                           }?>">
                    <?php if ($error['prefix']==='blank') : ?>
                        <p class="error">※プレフィックスを入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['prefix'] === 'long') : ?>
                        <p class="error">※16字以内で入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['prefix'] === 'type') : ?>
                        <p class="error">※半角英数字で入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
    </table>
    <hr>
    <input type="submit" value="新規登録" class="long_btn">
</form>
    </div>
</main>
</body>
