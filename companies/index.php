<?php

require_once('../dbconnect.php');
require_once('../config.php');
require_once('../functions.php');
require_once('../app/controllers/CompaniesController.php');

 use App\Controllers\CompaniesController;

 $cmp = new CompaniesController;
 $res = $cmp->index($_GET);
 $companies = $res['companies'];
 $maxPage = $res['maxPage'];
 $page = $res['page'];
 $order = $res['order'];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../style.css">



<title>プログラミング実習</title>
</head>

<body>
<main>
    <div class="contents">
    <h2 class="home" href='./'>会社一覧</h2>
    <hr>
    <a href="./add.php" class="long_btn">新規登録</a>
    <!--検索フォーム-->
    <form action="index.php" method="get">
        <input class="search_btn" type="submit" value="検索">
        <input class="text_search" type="text" name="search" value="<?php
        if (!empty($_GET['search'])) {
            echo h($_GET['search']);
        } ?>">
    </form>
    <br><br>

    <table id='companies_list'>
        <tr class="table_heading">
            <form action='index.php' method=get>
                <?php if (!empty($_GET['search'])) : ?>
                    <input type='hidden' name='search' value="<?php echo h($_GET['search']); ?>" >
                <?php endif; ?>
                <?php if ($order === 'DESC') :?>
                    <?php $order = 'ASC' ?><!--ここはボタンの値を決める。-->
                <?php else : ?>
                    <?php $order = 'DESC'?><!--初期値はASCでボタンはDESC-->
                <?php endif; ?>
                <input type='hidden' name='order' value="<?php echo h($order) ?>">
                <th class="th ID">会社番号　<input class="ascdesc" type="submit" value="▼"></th>
            </form>
            <th class="th name">会社名</th><th class="th PIC">担当者名</th><th class="th tel">電話番号</th>
            <th class="th address">住所</th><th class="th email">メールアドレス</th>
            <th class="th quotation">見積一覧</th><th class="th invoice">請求一覧</th>
            <th class="th edit">編集</th><th class="th delete">削除</th>
        </tr>

        <?php foreach ($companies as $company) : ?>
            <tr>
                <td class="td"><?php echo h($company['id']);?></td>
                <td class="td"><?php echo h($company['company_name']);?></td>
                <td class="td"><?php echo h($company['manager_name']);?></td>
                <td class="td"><?php echo h($company['phone_number']);?></td>
                <td class="td"><?php echo h($company['postal_code']);?><br>
                <?php echo h(PREFECTURES[$company['prefecture_code']]).h($company['address']);?></td>
                <td class="td"><?php echo h($company['mail_address']);?></td>
                <td class="td"><a class="list_btn" href="quotations/index.php?id=<?php echo h($company['id']); ?>">見積一覧</a></td>
                <td class="td"><a class="list_btn" href="invoices/index.php?id=<?php echo h($company['id']); ?>">請求一覧</a></td>
                <td class="td"><a class="edit_delete" href="./edit.php?id=<?php echo h($company['id']); ?>">編集</a></td>
                <td class="td"><a class="edit_delete" href="./delete.php?id=<?php echo h($company['id']); ?>" onclick="return cfm()">削除</a></td>
            </tr>
        <?php endforeach; ?>

    </table>
<hr>
<div class="paging">
    <?php if ($page > 1) :  ?>
        <span><a class="pgbtn" href="index.php?page=<?php print(h($page) -1);
        /*検索結果あり*/
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($order)) {
            echo '&order='.h($_GET['order']) ;
        } ?>">←前へ</a></span>
    <?php endif; ?>
    <span class="pgbtn nowpage"><?php print(h($page)); ?></span>
    <?php if ($page < $maxPage) : ?>
        <span><a class="pgbtn" href="index.php?page=<?php print(h($page) + 1);
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($order)) {
            echo '&order='.h($_GET['order']) ;
        } ?>">次へ→</a></span>
    <?php endif; ?>
</div>

</main>

<script>
    function cfm(){
        return confirm('本当に削除しますか');
}
</script>
</body>

</html>






