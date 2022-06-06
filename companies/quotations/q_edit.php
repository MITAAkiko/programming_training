<?php
//idがない時はindex.phpに返す
require('../../dbconnect.php');
require_once('../../config.php');
require_once('../../functions.php');
require_once('../../app/controllers/QuotationsController.php');
use App\Controllers\QuotationController;

$cmp = new QuotationController;
$res = $cmp->edit($_GET, $_POST);
$company = $res['company'];
$quotation = $res['quotation'];
$error = $res['error'];
$isError = $res['isError'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../../style.css">
<link rel="stylesheet" type="text/css" href="../../style_join.css">
<link rel="stylesheet" type="text/css" href="./q_style.css">
    <title>プログラミング実習</title>
</head>

<body>
<main>
    <div class="content_add">
    <div><span class="title">見積書編集</span><a class="btn" href="index.php?id=<?php echo h($company['id']) ?>">戻る</a></div>
    <hr>
<form action="" method="post">
    <table class="join_table">
    <tr><th>見積番号</th> 
            <td><?php echo h($quotation['no']) ?></td>
        </tr>
    <tr><th>見積名</th> 
            <td>
                <input class="text_join" type="text" name="title" 
                value="<?php if (!empty($_POST['title'])) {
                        echo h($_POST['title']);
                       } else {
                            echo h($quotation['title']);
                       } ?>">
                <?php if ($error['title']==='blank') : ?>
                    <p class="error">※見積名を入力してください</p>
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
                       } else {
                            echo h($quotation['total']);
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

        <tr><th>見積書有効期限</th> 
            <td>
                <input class="text_join" type="text" name="period" 
                value="<?php if (!empty($_POST['period'])) {
                        echo str_replace('-', '', h($_POST['period']));
                       } else {
                            echo str_replace('-', '', h($quotation['validity_period']));
                       } ?>">
                    <?php if ($error['period']==='blank') : ?>
                        <p class="error">※日付を入力してください</p>
                    <?php elseif ($error['period'] === 'type') : ?>
                        <p class="error">※半角数字のみで入力してください（例:20210525）</p>
                    <?php elseif ($error['period']==='check_date') : ?>
                        <p class="error">※正しい日付を入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th>納期</th> 
            <td>
                <input class="text_join" type="text" name="due" 
                value="<?php if (!empty($_POST['date'])) {
                        echo str_replace('-', '', h($_POST['date']));
                       } else {
                            echo str_replace('-', '', h($quotation['due_date']));
                       }?>">
                    <?php if ($error['due']==='blank') : ?>
                        <p class="error">※納期を入力してください</p>
                    <?php elseif ($error['due'] === 'time') : ?>
                        <p class="error">※見積書有効期限より後に設定してください</p>
                    <?php elseif ($error['due'] === 'type') : ?>
                        <p class="error">※半角数字のみで入力してください（例:20210525）</p>
                    <?php elseif ($error['due']==='check_date') : ?>
                        <p class="error">※正しい日付を入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th>状態</th>
            <td><select class="select_status" name="status">
                        <option value="<?php echo h($quotation['status']) ?>"><?php echo STATUSES[h($quotation['status'])] ?></option>
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
