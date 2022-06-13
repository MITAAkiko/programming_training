<?php
//見積作成画面
require('../../dbconnect.php');
require_once('../../config.php');
require('../../functions.php');
require_once('../../app/controllers/InvoicesController.php');
use App\Controllers\InvoicesController;

$cmp = new InvoicesController;
$res = $cmp->add($_GET, $_POST);
$error = $res['error'];
$company = $res['company'];
$isError = $res['isError'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../../style.css">
<link rel="stylesheet" type="text/css" href="i_style.css">
<link rel="stylesheet" type="text/css" href="../../style_join.css">
    <title>プログラミング実習</title>
</head>

<body>
<main>
    <div class="content_add">
    <div><span class="title">請求作成</span><a class="btn" href="./index.php?id=<?php echo h($_GET['id']) ?>">戻る</a></div>
    <hr>
<form action="" method="post">
    <table class="join_table">
        <tr><th>請求名</th> 
            <td>
                <input class="text_join" type="text" name="title" 
                value="<?php if (!empty($_POST['title'])) {
                        echo h($_POST['title']);
                       } ?>">
                <?php if ($error['title']==='blank') : ?>
                    <p class="error">※請求名を入力してください</p>
                <?php elseif ($error['title']==='long') : ?>
                    <p class="error">※64文字以内で入力してください</p>
                <?php endif; ?>
            </td>
        </tr>
<!--会社名取得-->
        <tr><th>会社名</th> 
            <td><?php echo h($company['company_name']) ?></td>
        </tr>
        <tr><th>金額</th> 
            <td>
                <input class="text_join_en" type="text" name="total" 
                value="<?php if (!empty($_POST['total'])) {
                         echo h($_POST['total']);
                       } ?>"> 円
                    <?php if ($error['total']==='blank') : ?>
                        <p class="error">※金額を入力してください</p>
                    <?php elseif ($error['total'] === 'type') :?>
                        <p class="error">※半角数字のみで入力してください</p>
                    <?php elseif ($error['total'] === 'long') :?>
                        <p class="error">※10桁以内で入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th>支払期限<br><span class="advice">(例:20210625)</span></th> 
            <td>
                <input class="text_join" type="text" name="pay" 
                value="<?php if (!empty($_POST['pay'])) {
                         echo h($_POST['pay']);
                       } ?>">
                    <?php if ($error['pay']==='blank') : ?>
                        <p class="error">※日付を入力してください</p>
                    <?php elseif ($error['pay'] === 'type') : ?>
                        <p class="error">※半角数字のみで入力してください（例:20210625）</p>
                    <?php elseif ($error['pay']==='check_date') : ?>
                        <p class="error">※正しい日付を入力してください</p>
                    <?php elseif ($error['pay'] === 'time') : ?>
                        <p class="error">※請求日より後に設定してください</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th>請求日<br><span class="advice">(例:20210525)</span></th> 
            <td>
                <input class="text_join" type="text" name="date" 
                value="<?php if (!empty($_POST['date'])) {
                         echo h($_POST['date']);
                       } ?>">
                    <?php if ($error['date']==='blank') : ?>
                        <p class="error">※請求日を入力してください</p>
                    <?php elseif ($error['date'] === 'type') : ?>
                        <p class="error">※半角数字のみで入力してください（例:20210525）</p>
                    <?php elseif ($error['date']==='check_date') : ?>
                        <p class="error">※正しい日付を入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th>見積番号<br><span class="advice">(半角英数字のみ)</span></th> 
            <td>
                <input class="text_join" type="text" name="quo" 
                value="<?php if (!empty($_POST['quo'])) {
                        echo h($_POST['quo']);
                       } ?>">
                    <?php if ($error['quo']==='blank') : ?>
                        <p class="error">※見積番号を入力してください</p>
                    <?php elseif ($error['quo'] === 'long') : ?>
                        <p class="error">※100文字以内で入力してください</p>
                    <?php elseif ($error['quo'] === 'type') : ?>
                        <p class="error">※半角英数字のみで入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th>状態</th>
            <td><select class="select_status" name="status">
                        <option value="">選択してください</option>
                        <?php foreach (STATUSES as $number => $value) : ?>
                        <option value="<?php echo $number ?>"><?php echo $value ?></option>
                        <?php endforeach; ?>
                </select>
                    <?php if ($error['status']==='blank') : ?>
                        <p class="error">※選択してください</p>
                    <?php elseif ($error['status']==='type') : ?>
                        <p class="error">※正しく選択してください</p>
                    <?php elseif ($error['status']==='long') : ?>
                        <p class="error">※正しく選択してください</p>
                    <?php elseif ($isError) : ?>
                        <p class="error">※もう一度選択してください</p>
                    <?php endif; ?>
            </td>
        </tr>
    </table>
    <hr>
    <input type="submit" value="請求作成" class="long_btn">
    <input type="hidden" name="prefix" value="<?php echo h($company['prefix']) ?>">
    <input type="hidden" name="return_id" value="<?php echo h($company['id']) ?>"><!--一覧にもどるため-->
</form>
    </div>
</main>
</body>
