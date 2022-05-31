<?php
//見積作成画面

require('../../dbconnect.php');
require_once('../../config.php');
require('../../functions.php');

//エラーチェック
function isError($err)
{
    $nonerror=[
        'title' => '',
        'name' => '',
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
    'name' => '',
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
    if (strtotime($_POST['period']) > strtotime($_POST['due'])) {
        $error['due']='time';
    } elseif (($_POST['due'])==='') {
        $error['due']='blank';
    } elseif (!preg_match('/^[0-9]{8}$/', $_POST['due'])) {
        $error['due']='type';
    } elseif (strtotime($_POST['due'])===false) {
        $error['due']='check_date';
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

//エラーがない時にデータベースに登録する
if (!empty($_POST)) {
    //var_dump($_POST);exit();
    if (!$isError) {
        $getids = $db->prepare('SELECT count(*)+1 AS getid FROM quotations WHERE company_id=?');//idを取得
        $getids->bindParam(1, $_GET['id'], PDO::PARAM_INT);
        $getids->execute();
        $getid = $getids->fetch();
        $quotate_id = str_pad($getid['getid'], 8, 0, STR_PAD_LEFT); // 8桁にする
        $no = $_POST['prefix'].'-q-'.$quotate_id;//見積番号

        $due = new DateTime();
        $due = $due->format('Y-m-d');
        $statement = $db->prepare('INSERT INTO quotations 
            SET company_id=?,no=?,
            title=?, total=?, validity_period=?, due_date=?, status=?, 
            created=NOW(),modified=NOW()');
        $statement->bindParam(1, $_GET['id'], PDO::PARAM_INT);
        $statement->bindParam(2, $no, PDO::PARAM_STR);
        $statement->bindParam(3, $_POST['title'], PDO::PARAM_STR);
        $statement->bindParam(4, $_POST['total'], PDO::PARAM_INT);
        $statement->bindParam(5, $_POST['period'], PDO::PARAM_INT);
        //$statement->bindParam(6, $_POST['due'], PDO::PARAM_INT);
        $statement->bindValue(6, $due, PDO::PARAM_STR);
        $statement->bindParam(7, $_POST['status'], PDO::PARAM_INT);
        echo $ret=$statement->execute();
        header('Location:./?id='.$_POST['return_id']);
        exit();
    }
}

//$_GET['id']ない時戻す
if (empty($_GET)) {
    header('Location:../companies');
    exit();
}
//会社名取得
if (!empty($_GET)) {
    $companies = $db->prepare('SELECT company_name, prefix ,id
        FROM companies WHERE id=?');
    $companies->bindParam(1, $_GET['id'], PDO::PARAM_INT);
    $companies->execute();
    $company = $companies->fetch();
}
   
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
    <div><span class="title">見積作成</span><a class="btn" href="./index.php?id=<?php echo h($_GET['id']) ?>">戻る</a></div>
    <hr>
<form action="" method="post">
    <table class="join_table">
        <tr><th>見積名</th> 
            <td>
                <input class="text_join" type="text" name="title" 
                value="<?php if (!empty($_POST['title'])) {
                        echo h($_POST['title']);
                       } ?>">
                <?php if ($error['title']==='blank') : ?>
                    <p class="error">※見積名を入力してください</p>
                <?php endif; ?>
                <?php if ($error['title']==='long') : ?>
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
                    <?php if ($error['total']==='blank') : ?>
                        <p class="error">※金額を入力してください</p>
                    <?php endif; ?>
                    <?php if ($error['total'] === 'type') :?>
                        <p class="error">※半角数字のみで入力してください</p>
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
                         echo h($_POST['period']);
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
                value="<?php if (!empty($_POST['due'])) {
                        echo h($_POST['due']);
                       } ?>">
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
                        <option value="">選択してください</option>
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
                    <?php if ($isError) : ?>
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