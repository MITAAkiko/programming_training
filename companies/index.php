<?php

require_once('../config.php');
require_once(HOME.'/functions.php');
require_once(APP.'/controllers/CompaniesController.php');

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
    <h2 class="home"><a class="index" href='./'>会社一覧</a></h2>
    <hr>

    <!--検索フォーム-->
    <form action="index.php" method="get">
    <a href="./add.php" class="long_btn">新規登録</a>
        <input class="search_btn" type="submit" value="検索">
        <input class="text_search" type="text" name="search" value="<?php
        if (!empty($_GET['search'])) {
            echo h($_GET['search']);
        } ?>">
    </form>
    <br>
    <table id='companies_list'>
        <tr class="table_heading">
            <form action='index.php' method=get>
                <th class="th ID">会社番号　<input class="ascdesc" type="submit" value="&#9660;"></th><!--▼-->
                <?php if ($order === 'DESC') :?>
                    <?php $order = 'ASC' ?><!--ここはボタンの値を決める。-->
                <?php else : ?>
                    <?php $order = 'DESC'?><!--初期値はASCでボタンはDESC-->
                <?php endif; ?>
                <?php if (!empty($_GET['search'])) : ?>
                    <input type='hidden' name='search' value="<?php echo h($_GET['search']); ?>" >
                <?php endif; ?>
                <input type='hidden' name='order' value="<?php echo h($order) ?>">
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
                <td class="td">
                    <?php echo h($company['postal_code']);?><br>
                    <?php echo h(PREFECTURES[$company['prefecture_code']]).h($company['address']);?>
                </td>
                <td class="td"><?php echo h($company['mail_address']);?></td>
                <td class="td">
                    <a class="list_btn" href="quotations/index.php?id=<?php echo h($company['id']); ?>">
                        見積一覧
                    </a>
                </td>
                <td class="td">
                    <a class="list_btn" href="invoices/index.php?id=<?php echo h($company['id']); ?>">
                        請求一覧
                    </a>
                </td>
                <td class="td">
                    <a class="edit_delete" href="./edit.php?id=<?php echo h($company['id']); ?>">
                        編集
                    </a>
                </td>
                <form action='delete.php' method=post>
                    <td class="td">
                        <a href="./delete.php">
                            <input type='submit' class="edit_delete" onclick="return cfm()" value='削除'>
                        </a>
                        <input type='hidden' name='delete_id' value="<?php echo h($company['id']); ?>">
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>
<hr>
<div class="paging">
    <?php if ($page > 1) :  ?>
        <span><a class="pgbtn" href="index.php?page=1<?php
        /*検索結果あり*/
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order']) ;
        } ?>">&laquo;最初</a></span>
    <?php endif; ?>
    <?php if ($page > 1) :  ?>
        <span><a class="pgbtn" href="index.php?page=<?php print(h($page) -1);
        /*検索結果あり*/
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order']) ;
        } ?>">&lsaquo;</a></span>
    <?php endif; ?>
    <?php if ($page > 2) :  ?>
        <span><a class="pgbtn" href="index.php?page=<?php print(h($page) -2);
        /*検索結果あり*/
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order']) ;
        } ?>"><?php print(h($page) -2)?></a></span>
    <?php endif; ?>
    <?php if ($page > 1) :  ?>
        <span><a class="pgbtn" href="index.php?page=<?php print(h($page) -1);
        /*検索結果あり*/
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order']) ;
        } ?>"><?php print(h($page) -1)?></a></span>
    <?php endif; ?>
    <span class="pgbtn nowpage"><?php print(h($page)); ?></span>
    <?php if ($page < $maxPage) : ?>
        <span><a class="pgbtn" href="index.php?page=<?php print(h($page) + 1);
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order']) ;
        } ?>"><?php print(h($page) + 1)?></a></span>
    <?php endif; ?>
    <?php if ($page < $maxPage-1) : ?>
        <span><a class="pgbtn" href="index.php?page=<?php print(h($page) + 2);
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order']) ;
        } ?>"><?php print(h($page) + 2)?></a></span>
    <?php endif; ?>
    <?php if ($page < $maxPage) : ?>
        <span><a class="pgbtn" href="index.php?page=<?php print(h($page) + 1);
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order']) ;
        } ?>">&rsaquo;</a></span>
    <?php endif; ?>
    <?php if ($page < $maxPage) : ?>
        <span><a class="pgbtn" href="index.php?page=<?php print(h($maxPage));
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order']) ;
        } ?>">最後&raquo;</a></span>
    <?php endif; ?>
</div>
</main>
<script src="../scripts.js"></script>
</body>
</html>






