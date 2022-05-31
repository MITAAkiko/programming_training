<?php
//session_start();
require('dbconnect.php');
require_once('./config.php');
require_once('functions.php');

require_once('page_class.php');
require_once('page_data.php');

//初期値
if (empty($_GET['order'])) {
    $_GET['order']=1;
}
/*
$page = 0;
if (!empty($_GET['page'])) {
    $page= $_GET['page'];
    if ($page == '') {
        $page=1;
    }
}
//最小値
$page = max($page, 1);
//最後のページを取得する

if (!empty($_GET['search'])) {
    $searched = '%'.$_GET['search'].'%' ;
    $counts = $db->prepare('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)');
    $counts->bindParam(1, $searched, PDO::PARAM_STR);
    $counts->bindParam(2, $searched, PDO::PARAM_STR);
    $counts->execute();
    $cnt = $counts->fetch();
    $maxPage = ceil($cnt['cnt']/10);
} else {
    $counts = $db->query('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL');
    $cnt = $counts->fetch();
    $maxPage = ceil($cnt['cnt']/10);
}

//最大値
$page = min($page, $maxPage);
//ページ
$start = ($page - 1) * 10;
*/
//DBに接続する用意
//検索した場合
$start = $cmpPage->getStartPage();
$page = $cmpPage->getPage();

if (!empty($_GET['search'])) {//GETでおくる
    if (($_GET['order'])>0) {
        $searched = '%'.$_GET['search'].'%' ;
        $companies=$db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)
            ORDER BY id ASC LIMIT ?,10');
        $companies->bindParam(1, $searched, PDO::PARAM_STR);
        $companies->bindParam(2, $searched, PDO::PARAM_STR);
        $companies->bindParam(3, $start, PDO::PARAM_INT);
        $companies->execute();
    } else {
        $searched = '%'.$_GET['search'].'%' ;
        $companies=$db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)
            ORDER BY id DESC LIMIT ?,10');
        $companies->bindParam(1, $searched, PDO::PARAM_STR);
        $companies->bindParam(2, $searched, PDO::PARAM_STR);
        $companies->bindParam(3, $start, PDO::PARAM_INT);
        $companies->execute();
    }
} else {//検索なかった場合
    if (($_GET['order'])>0) {
        $companies=$db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL ORDER BY id ASC LIMIT ?,10');
        $companies->bindParam(1, $start, PDO::PARAM_INT);
        $companies->execute();
    } else {
        $companies=$db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL ORDER BY id DESC LIMIT ?,10');
        $companies->bindParam(1, $start, PDO::PARAM_INT);
        $companies->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">



<title>プログラミング実習</title>
</head>

<body>
<main>
    <div class="contents">
    <h2 class="home" href='./'>会社一覧</h2>
    <hr>
    <a href="join/" class="long_btn">新規登録</a>
    <!--検索フォーム-->
    <form action="index.php" method="get" href='./?search=<?php echo h($_GET['search']) ?>'>
        <input class="search_btn" type="submit" value="検索">
        <input class="text_search" type="text" name="search" value="<?php
        if (!empty($_GET['search'])) {
             echo h($_GET['search']);
        } ?>">
    </form>
    <br><br>

    <table id='companies_list'>
 <!--   <thead>-->
        <tr class="table_heading">
        <form action='index.php' method=get>

            <?php if (!empty($_GET['search'])) : ?>
                <input type='hidden' name='search' value="<?php echo h($_GET['search']); ?>" >
            <?php endif; ?>
            <input type='hidden' name='order' value="<?php echo h($_GET['order'] *= -1) ?>" >
            <th class="th ID">会社番号　<input class="ascdesc" type="submit" value="▼"></th>

        </form>

            <th class="th name">会社名</th><th class="th PIC">担当者名</th><th class="th tel">電話番号</th>
            <th class="th address">住所</th><th class="th email">メールアドレス</th>
            <th class="th quotation">見積一覧</th><th class="th invoice">請求一覧</th>
            <th class="th edit">編集</th><th class="th delete">削除</th>
        </tr>
<!--    </thead>
    <tbody>    -->
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
                    <td class="td"><a class="edit_delete" href="edit.php?id=<?php echo h($company['id']); ?>">編集</a></td>
                    <td class="td"><a class="edit_delete" href="delete.php?id=<?php echo h($company['id']); ?>" onclick="return cfm()">削除</a></td>
                </tr>
        <?php endforeach; ?>
<!--    </tbody>-->
    </table>
<hr>
<div class="paging">
    <?php if ($page > 1) :  ?>
        <span><a class="pgbtn" href="index.php?page=<?php print(h($page) -1);
        /*検索結果あり*/
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order'])*-1 ;
        } ?>">←前へ</a></span>
    <?php endif; ?>
    <span class="pgbtn nowpage"><?php print(h($page)); ?></span>
    <?php if ($page < $cmpPage->getMaxPage()) : ?>
        <span><a class="pgbtn" href="index.php?page=<?php print(h($page) + 1);
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }/*昇順降順*/
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order'])*-1 ;
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






