<?php
require('../../dbconnect.php');
require_once('../../config.php');
require('../../functions.php');
require_once('../../app/controllers/InvoicesController.php');
use App\Controllers\InvoicesController;

$cmp = new InvoicesController;
$res = $cmp->edit($_GET, $_POST);
$invoice = $res['invoice'];
$error = $res['error'];
$company = $res['company'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../../style.css">
<link rel="stylesheet" type="text/css" href="../../style_join.css">
<link rel="stylesheet" type="text/css" href="./i_style.css">
    <title>プログラミング実習</title>
</head>

<body>
<main>
    <div class="content_add">
    <div><span class="title">請求書編集</span><a class="btn" href="index.php?id=<?php echo h($company['id']) ?>">戻る</a></div>
    <hr>
<form action="" method="post">
    <table class="join_table">
        <tr><th>請求番号</th> 
            <td><?php echo h($invoice['no']) ?></td>
        </tr>
        <tr><th>請求名</th> 
            <td>
                <input class="text_join" type="text" name="title" 
                    value="<?php if (!empty($_POST['title'])) {
                            echo $_POST['title'];
                           } else {
                               echo $invoice['title'];
                           } ?>">
                <?php if ($error['title']==='blank') : ?>
                    <p class="error">※請求名を入力してください</p>
                <?php elseif ($error['title']==='long') : ?>
                    <p class="error">※64文字以内で入力してください</p>
                <?php endif; ?>
            </td>
        </tr>
        <tr><th>会社名</th> 
            <td><?php echo $company['company_name'] ?></td>
        </tr>
        <tr><th>金額</th> 
            <td>
                <input class="text_join_en" type="text" name="total" 
                value="<?php if (!empty($_POST['total'])) {
                        echo h($_POST['total']);
                       } else {
                            echo h($invoice['total']);
                       } ?>"> 円
                    <?php if ($error['total']==='blank') : ?>
                        <p class="error">※金額を入力してください</p>
                    <?php elseif ($error['total'] === 'type') :?>
                        <p class="error">※半角数字で入力してください</p>
                    <?php elseif ($error['total'] === 'long') :?>
                        <p class="error">※10桁以内で入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th>支払期限</th> 
            <td>
                <input class="text_join" type="text" name="pay" 
                value="<?php if (!empty($_POST['pay'])) {
                        echo str_replace('-', '', h($_POST['pay']));
                       } else {
                           echo str_replace('-', '', h($invoice['payment_deadline']));
                       } ?>">
                <?php if ($error['pay']==='blank') : ?>
                    <p class="error">※日付を入力してください</p>
                <?php elseif ($error['pay'] === 'type') : ?>
                    <p class="error">※半角数字のみで入力してください（例:20210525）</p>
                <?php elseif ($error['pay'] === 'time') : ?>
                    <p class="error">※請求日より後に設定してください</p>
                <?php elseif ($error['pay']==='check_date') : ?>
                        <p class="error">※正しい日付を入力してください</p>
                <?php endif; ?>
            </td>
        </tr>
        <tr><th>請求日</th> 
            <td>
                <input class="text_join" type="text" name="date"
                value="<?php if (!empty($_POST['date'])) {
                        echo str_replace('-', '', h($_POST['date']));
                       } else {
                           echo str_replace('-', '', h($invoice['date_of_issue']));
                       }?>">
                    <?php if ($error['date']==='blank') : ?>
                        <p class="error">※請求日を入力してください</p>
                    <?php elseif ($error['date'] === 'type') : ?>
                        <p class="error">※半角数字のみで入力してください（例:20210525）</p>
                    <?php elseif ($error['date']==='check_date') : ?>
                        <p class="error">※正しい日付を入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th>見積番号</th> 
            <td><?php echo h($invoice['quotation_no']) ?></td>
        </tr>
        <tr><th>状態</th>
            <td><select class="select_status" name="status">
                        <option value="<?php echo h($invoice['status']) ?>"><?php echo STATUSES[h($invoice['status'])] ?></option>
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
                    <?php elseif ($error['status']==='iserr') : ?>
                        <p class="error">※もう一度選択してください</p>
                    <?php endif; ?>
            </td>
        </tr>
    </table>
    <hr>
    <input type="submit" value="変更" class="long_btn">
</form>
    </div>
</main>
</body>
