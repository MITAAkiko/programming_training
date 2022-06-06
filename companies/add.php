<?php

require_once('../config.php');
require_once('../functions.php');
require_once('../app/controllers/CompaniesController.php');

 use App\Controllers\CompaniesController;

 $cmp = new CompaniesController;
 $error = $cmp->add($_POST);
 //error['postal_code'] = $this->blank($post['postal_code'], 'postal_code');


var_dump($error);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" type="text/css" href="../style_join.css">
    <title>プログラミング実習</title>
</head>

<body>
<main>
    <div class="content_add">
    <div><span class="title">会社登録</span><a class="btn" href="./index.php">戻る</a></div>
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
                <?php elseif ($error['name']==='long') : ?>
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
                <?php elseif ($error['manager']==='long') : ?>
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
                    <?php elseif ($error['phone'] === 'type') :?>
                        <p class="error">※半角数字で入力してください</p>
                    <?php elseif ($error['phone'] === 'long') :?>
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
                    <?php elseif ($error['postal_code'] === 'type') : ?>
                        <p class="error">※半角数字で入力してください</p>
                    <?php elseif ($error['postal_code'] === 'long') : ?> 
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
                    <?php elseif ($error['prefecture_code'] === 'long') : ?>
                        <p class="error">※正しく選択してください</p>
                    <?php elseif ($error['prefecture_code'] === 'type') : ?>
                        <p class="error">※正しく選択してください</p>
                    <?php elseif ($error['prefecture_code'] === 'size') : ?>
                        <p class="error">※正しく選択してください</p>
                    <?php elseif ($error['prefecture_code'] === 'error') :?>
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
                    <?php elseif ($error['address']==='long') : ?>
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
                    <?php elseif ($error['email'] === 'long') : ?>
                        <p class="error">※長すぎるため使用できません</p>
                    <?php elseif ($error['email'] === 'type') : ?>
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
                    <?php elseif ($error['prefix'] === 'long') : ?>
                        <p class="error">※16字以内で入力してください</p>
                    <?php elseif ($error['prefix'] === 'type') : ?>
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
