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
$companies->bindParam(1, $_GET['cid'], PDO::PARAM_INT);
$companies -> execute();
$company = $companies -> fetch();
//編集用
$quotations = $db -> prepare('SELECT no, title, total, validity_period, due_date, status 
    FROM quotations WHERE id = ?');
$quotations ->bindParam(1, $_GET['id'], PDO::PARAM_INT);
$quotations -> execute();
$quotation = $quotations -> fetch();


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
        'period' => '',
        'due' => '',
        'status' => ''
    ];
    return $err !== $nonerror;
}
//初期値
$error = [
    'title' => '',
    'total' => '',
    'period' => '',
    'due' => '',
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
    if (($_POST['period'])==='') {
        $error['period']='blank';
    } elseif (!preg_match('/^[0-9]{8}$/', $_POST['period'])) {
        $error['period']='type';
    } elseif (strtotime($_POST['period'])===false) {
        $error['period']='check_date';
    }
    if (($_POST['due'])==='') {
        $error['due']='blank';
    } elseif (!preg_match('/^[0-9]{8}$/', $_POST['due'])) {
        $error['due']='type';
    } elseif (strtotime($_POST['period']) > strtotime($_POST['due'])) {
        $error['due']='time';
    } elseif (strtotime($_POST['due'])===false) {
        $error['due']='check_date';
    }
    if (($_POST['status'])==='') {
        $error['status']='blank';
    } elseif (!preg_match("/^[0-9]+$/", $_POST['status'])) { //空文字ダメの半角数値
        $error['status']='type';
    } elseif (strlen($_POST['status'])>1) {
        $error['status']='long';
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
        //  validity_period=?, due_date=?,

        $statement = $db->prepare('UPDATE quotations
            SET  title=?, total=?, validity_period=?, due_date=?, status=?,
            modified=NOW() WHERE id=?');
        
        $statement->bindParam(1, $_POST['title'], PDO::PARAM_STR);
        $statement->bindParam(2, $_POST['total'], PDO::PARAM_INT);
        $statement->bindParam(3, $_POST['period'], PDO::PARAM_INT);
        $statement->bindParam(4, $_POST['due'], PDO::PARAM_INT);
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
                <?php endif; ?>
                <?php if ($error['title']==='long') : ?>
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
                    <?php endif; ?>
                    <?php if ($error['total'] === 'type') :?>
                        <p class="error">※半角数字で入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['total'] === 'long') :?>
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
                    <?php endif; ?>
                    <?php if ($error['period'] === 'type') : ?>
                        <p class="error">※半角数字のみで入力してください（例:20210525）</p>
                    <?php endif; ?>
                    <?php if ($error['period']==='check_date') : ?>
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
                    <?php endif; ?>
                    <?php if ($error['due'] === 'time') : ?>
                        <p class="error">※見積書有効期限より後に設定してください</p>
                    <?php endif; ?>
                    <?php if ($error['due'] === 'type') : ?>
                        <p class="error">※半角数字のみで入力してください（例:20210525）</p>
                    <?php endif; ?>
                    <?php if ($error['due']==='check_date') : ?>
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
