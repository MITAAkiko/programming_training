<?php
//idがない時はindex.phpに返す
require('../dbconnect.php');
require_once('../config.php');

if (empty($_GET)) {
    header('Location:./');
}
if ($_GET['id'] == '' || $_GET['cid'] == '') {
    header('Location:./');
} else {
    $id = $_GET['id'];
    $cid = $_GET['cid'];
}
//DBに接続する
//会社名
$companies = $db -> prepare('SELECT id, company_name, prefix
    FROM companies WHERE id=?');
$companies -> bindParam(1, $_GET['cid'], PDO::PARAM_INT);
$companies -> execute();
$company = $companies -> fetch();
//編集用
$invoices = $db -> prepare('SELECT no, title, total, payment_deadline, date_of_issue, quotation_no, status 
    FROM invoices WHERE id = ?');
$invoices -> bindParam(1, $_GET['id'], PDO::PARAM_INT);
$invoices -> execute();
$invoice = $invoices -> fetch();


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
        'title' => '',
        'total' => '',
        'pay' => '',
        'date' => '',
        'status' => ''
    ];
    return $err !== $nonerror;
}
//初期値
$error = [
    'title' => '',
    'total' => '',
    'pay' => '',
    'date' => '',
    'status' => ''
];
$isError = '';

//エラーについて
if (!empty($_POST)) {
    if (($_POST['title'])==='') {
        $error['title']='blank';
    } elseif (strlen($_POST['title'])>64) {
        $error['title']='long';
    }
    if (($_POST['total'])==='') {
        $error['total']='blank';
    } elseif (!preg_match('/^[0-9]+$/', $_POST['total'])) { //空文字ダメの半角数値
        $error['total']='type';
    } elseif (strlen($_POST['total'])>10) {
        $error['total']='long';
    }
    if (!preg_match('/^[0-9]{8}$/', $_POST['pay'])) {
        $error['pay']='type';
    } elseif (($_POST['pay'])==='') {
        $error['pay']='blank';
    } elseif (strtotime($_POST['pay']) < strtotime($_POST['date'])) {
        $error['pay']='time';
    }
    if (($_POST['date'])==='') {
        $error['date']='blank';
    } elseif (!preg_match('/^[0-9]{8}$/', $_POST['date'])) {
        $error['date']='type';
    }
    if (!preg_match("/^[0-9]+$/", $_POST['status'])) { //空文字ダメの半角数値
        $error['status']='type';
    } elseif (strlen($_POST['status'])>1) {
        $error['status']='long';
    } elseif (($_POST['status'])==='') {
        $error['status']='blank';
    }
}

//エラーがある.ファンクションそのまま使えないから変数に代入
$isError = isError($error);
//エラーがあったときに状態をもう一度選択する促し
if ($isError) {
    $error['status']='iserr';
}
//エラーがない時にデータベースに登録する
if (!empty($_POST)) {
    if (!$isError) {
        $statement = $db->prepare('UPDATE invoices
            SET  title=?, total=?, payment_deadline=?, date_of_issue=?, status=?,
            modified=NOW() WHERE id=?');
        
        $statement->bindParam(1, $_POST['title'], PDO::PARAM_STR);
        $statement->bindParam(2, $_POST['total'], PDO::PARAM_INT);
        $statement->bindParam(3, $_POST['pay'], PDO::PARAM_INT);
        $statement->bindParam(4, $_POST['date'], PDO::PARAM_INT);
        $statement->bindParam(5, $_POST['status'], PDO::PARAM_INT);
        $statement->bindParam(6, $_GET['id'], PDO::PARAM_INT);
        echo $ret=$statement->execute();
        header('Location:./?id='.$company['id']);
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" type="text/css" href="../join/style_join.css">
<link rel="stylesheet" type="text/css" href="./i_style.css">
    <title>プログラミング実習</title>
</head>

<body>
<main>
    <div class="content_add">
    <div><span class="title">請求書編集</span><a class="btn" href="index.php?id=<?php echo $company['id'] ?>">戻る</a></div>
    <hr>
<form action="" method="post">
    <table class="join_table">
        <tr><th>請求番号</th> 
            <td><?php echo $invoice['no'] ?></td>
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
                <?php endif; ?>
                <?php if ($error['title']==='long') : ?>
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
                        echo $_POST['total'];
                       } else {
                            echo $invoice['total'];
                       } ?>"> 円
                    <?php if ($error['total']==='blank') : ?>
                        <p class="error">※金額を入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['total'] === 'type') :?>
                        <p class="error">※半角数字で入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['total'] === 'long') :?>
                        <p class="error">※10桁以内で入力してください</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th>支払期限</th> 
            <td>
                <input class="text_join" type="text" name="pay" 
                value="<?php if (!empty($_POST['pay'])) {
                        echo str_replace('-', '', $_POST['pay']);
                       } else {
                           echo str_replace('-', '', $invoice['payment_deadline']);
                       } ?>">
                <?php if ($error['pay']==='blank') : ?>
                    <p class="error">※日付を入力してください</p>
                <?php endif; ?>
                <?php if ($error['pay'] === 'type') : ?>
                    <p class="error">※半角数字のみで入力してください（例:20210525）</p>
                <?php endif; ?>
                <?php if ($error['pay'] === 'time') : ?>
                    <p class="error">※請求日より後に設定してください</p>
                <?php endif; ?>
            </td>
        </tr>
        <tr><th>請求日</th> 
            <td>
                <input class="text_join" type="text" name="date"
                value="<?php if (!empty($_POST['date'])) {
                        echo str_replace('-', '', $_POST['date']);
                       } else {
                           echo str_replace('-', '', $invoice['date_of_issue']);
                       }?>">
                    <?php if ($error['date']==='blank') : ?>
                        <p class="error">※請求日を入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['date'] === 'type') : ?>
                        <p class="error">※半角数字のみで入力してください（例:20210525）</p>
                    <?php endif; ?>
            </td>
        </tr>
        <tr><th>見積番号</th> 
            <td><?php echo $invoice['quotation_no'] ?></td>
        </tr>
        <tr><th>状態</th>
            <td><select class="select_status" name="status">
                        <option value="<?php echo $invoice['status'] ?>"><?php echo STATUSES[$invoice['status']] ?></option>
                        <?php foreach (STATUSES as $number => $value) : ?>
                        <option value="<?php echo $number ?>"><?php echo $value ?></option>
                        <?php endforeach; ?>
                </select>
                    <?php if ($error['status']==='blank') : ?>
                        <p class="error">※選択してください</p>
                    <?php endif; ?>
                    <?php if ($error['status']==='type') : ?>
                        <p class="error">※正しく選択してください</p>
                    <?php endif; ?>
                    <?php if ($error['status']==='long') : ?>
                        <p class="error">※正しく選択してください</p>
                    <?php endif; ?>
                    <?php if ($error['status']==='iserr') : ?>
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
