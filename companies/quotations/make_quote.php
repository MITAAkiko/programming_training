<?php
//見積作成画面
require_once('../../config.php');
require_once(HOME.'/dbconnect.php');
require_once(HOME.'/functions.php');
require_once(APP.'/controllers/QuotationsController.php');
use App\Controllers\QuotationController;

$cmp = new QuotationController;
$res = $cmp->add($_GET, $_POST);
$company = $res['company'];
$error = $res['error'];
$isError = $res['isError'];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../../style.css">
<link rel="stylesheet" type="text/css" href="q_style.css">
<link rel="stylesheet" type="text/css" href="../../style_join.css">
    <title>プログラミング実習</title>
</head>

<body>
<main>
<div class="content_add">
    <div>
        <span class="title">見積作成</span>
        <a class="btn" href="./index.php?id=<?php echo h($_GET['id']) ?>">戻る</a></div>
        <hr>
        <form action="" method="post">
            <table class="join_table">
                <tr><th>見積名</th> 
                    <td>
                        <input class="text_join" type="text" name="title" 
                        value="<?php if (!empty($_POST['title'])) {
                                echo h($_POST['title']);
                               } ?>">
                        <?php if ($error['title'] === 'blank') : ?>
                            <p class="error">※見積名を入力してください</p>
                        <?php elseif ($error['title'] === 'long') : ?>
                            <p class="error">※64文字以内で入力してください</p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr><th>会社名</th> 
                    <td><?php echo h($company['company_name']) ?></td>
                </tr>
                <tr><th>金額</th> 
                    <td>
                        <input class="text_join_en" type="text" name="total" 
                        value="<?php if (!empty($_POST['total'])) {
                                echo h($_POST['total']);
                               } ?>"> 円
                        <?php if ($error['total'] === 'blank') : ?>
                            <p class="error">※金額を入力してください</p>
                        <?php elseif ($error['total'] === 'type') :?>
                            <p class="error">※半角数字のみで入力してください</p>
                        <?php elseif ($error['total'] === 'long') :?>
                            <p class="error">※10桁以内で入力してください</p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr><th>見積書有効期限<br><span class="advice">(例:20210525)</span></th> 
                    <td>
                        <input class="text_join" type="text" name="period" 
                        value="<?php if (!empty($_POST['period'])) {
                                echo h($_POST['period']);
                               } ?>">
                        <?php if ($error['period'] === 'blank') : ?>
                            <p class="error">※日付を入力してください</p>
                        <?php elseif ($error['period'] === 'type') : ?>
                            <p class="error">※半角数字のみで入力してください（例:20210525）</p>
                        <?php elseif ($error['period'] === 'check_date') : ?>
                            <p class="error">※正しい日付を入力してください</p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr><th>納期<br><span class="advice">(例:20210625)</span></th> 
                    <td>
                        <input class="text_join" type="text" name="due" 
                        value="<?php if (!empty($_POST['due'])) {
                                echo h($_POST['due']);
                               } ?>">
                        <?php if ($error['due'] === 'blank') : ?>
                            <p class="error">※納期を入力してください</p>
                        <?php elseif ($error['due'] === 'time') : ?>
                            <p class="error">※見積書有効期限より後に設定してください</p>
                        <?php elseif ($error['due'] === 'type') : ?>
                            <p class="error">※半角数字のみで入力してください（例:20210625）</p>
                        <?php elseif ($error['due'] === 'check_date') : ?>
                            <p class="error">※正しい日付を入力してください</p>
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
                        <?php if ($error['status'] === 'blank') : ?>
                            <p class="error">※選択してください</p>
                        <?php elseif ($error['status'] === 'type') : ?>
                            <p class="error">※正しく選択してください</p>
                        <?php elseif ($error['status'] === 'long') : ?>
                            <p class="error">※正しく選択してください</p>
                        <?php elseif ($isError) : ?>
                            <p class="error">※もう一度選択してください</p>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
            <hr>
            <input type="submit" value="見積作成" class="long_btn">
            <input type="hidden" name="prefix" value="<?php echo h($company['prefix']) ?>">
            <input type="hidden" name="return_id" value="<?php echo h($company['id']) ?>"><!--一覧にもどるため-->
        </form>
    </div>
</main>
</body>
