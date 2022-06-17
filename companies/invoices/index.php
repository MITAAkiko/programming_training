<?php
require_once('../../config.php');
require_once(HOME.'/functions.php');
require_once(APP.'/controllers/InvoicesController.php');
use App\Controllers\InvoicesController;

$cmp = new InvoicesController;
$res = $cmp->index($_GET);

$maxPage = $res['maxPage'];
$invoices = $res['invoices'];
$company = $res['company'];
$page = $res['page'];
$order = $res['order'];//IDでの昇順降順
$order2 = $res['order2'];//請求日での昇順降順
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../../style.css">
<link rel="stylesheet" type="text/css" href="i_style.css">
    <title>プログラミング実習</title>
</head>

<body>
<main>
    <div class="contents">
    <h2 class="home">請求一覧 <a href="../" class="btn">会社一覧へ戻る</a>
        <span class="company_name"><?php echo h($company['company_name']) ?></span>
    </h2>
    <hr>
    
    <form action='./' method="get" href='./?id=<?php echo h($_GET['id']) ?>&search=<?php echo h($_GET['search']) ?>'><!--getにhrefいらない？自動で入力？-->
    <a href="./i_add.php?id=<?php echo h($_GET['id']) ?>" class="long_btn">請求作成</a>
        <input class="search_btn" type="submit" value="検索">
        <select class="text_search" name="search">
            <!--検索した後の初期値-->
            <?php if (!empty($_GET['search'])) :?>
                <option value="<?php echo h($_GET['search']); ?>"><?php echo STATUSES[h($_GET['search'])]?></option>
            <?php endif; ?>
            <option value="">すべての状態</option>
                <?php foreach (STATUSES as $number => $value) : ?>
                    <option value="<?php echo $number ?>"><?php echo $value ?></option>
                <?php endforeach; ?>
        </select>
        <input type='hidden' name='id' value="<?php echo h($_GET['id']) ?>" >
    </form>
    
    <br>
    <table>
        <tr class="table_heading">
            <th class="makeInv">請求書発行</th>
            <form action='index.php' method=get>
                <input type='hidden' name='id' value="<?php echo h($_GET['id']); ?>">
                <?php if (!empty($_GET['search'])) : ?>
                    <input type='hidden' name='search' value="<?php echo h($_GET['search']); ?>" >
                <?php endif; ?>
                <input type='hidden' name='order' value="<?php echo h($order * -1) ?>" >
                <th class="no">請求番号　<input class="ascdesc" type="submit" value="▼"></th>
            </form>
            <th class="title">請求名</th><th class="manager">担当者名</th>
            <th class="total">金額</th><th class="pay">支払期限</th>
            <form action='index.php' method=get>
                <input type='hidden' name='id' value="<?php echo h($_GET['id']); ?>">
                <?php if (!empty($_GET['search'])) : ?>
                    <input type='hidden' name='search' value="<?php echo h($_GET['search']); ?>" >
                <?php endif; ?>
                <input type='hidden' name='order2' value="<?php echo h($order2 * -1) ?>" >
                <th class="date">請求日<input class="ascdesc" type="submit" value="▼"></th>
                <!-- $_GET['id']と$_GET['search']の値は渡すが、IDと請求日の値をお互いに渡さないことで上書き？ -->
            </form>
            <th class="quo">見積番号</th><th class="status">状態</th>
            <th class="i_edit">編集</th><th class="i_delete">削除</th>
        </tr>
        
        <?php  foreach ($invoices as $invoice) : ?>
            <tr>
                <td class="td">
                    <a class="edit_delete" href="xcelCreation.php?id=<?php echo h($invoice['id']) ?>&cid=<?php echo h($company['id']) ?>&make=pdf">PDF</a>
                    <span class="separation"> | </span> 
                    <a class="edit_delete" href="xcelCreation.php?id=<?php echo h($invoice['id']) ?>&cid=<?php echo h($company['id']) ?>&make=exl">Excel</a>
                </td>
                <td class="td"><?php echo h($invoice['no']);?></td>
                <td class="td"><?php echo h($invoice['title']);?></td>
                <td class="td"><?php echo h($invoice['manager_name']);?></td>
                <td class="td"><?php echo h(number_format($invoice['total']));?>円</td><!--カンマをつける-->
                <td class="td"><?php echo h(str_replace('-', '/', $invoice['payment_deadline']));?><br>
                <td class="td"><?php echo h(str_replace('-', '/', $invoice['date_of_issue']));?></td>
                <td class="td"><?php echo h($invoice['quotation_no']);?></td>
                <td class="td"><?php echo h(STATUSES[$invoice['status']]);?></td>
                <td class="td"><a class="edit_delete" href="i_edit.php?id=<?php echo h($invoice['id']) ?>&cid=<?php echo h($company['id']) ?>">編集</a></td>
                <form action='i_delete.php' method=post>
                    <td class="td">
                        <a href="i_delete.php"><input type='submit' class="edit_delete" onclick="return cfm()" value='削除'></a>
                        <input type='hidden' name='cid' value="<?php echo h($company['id']) ?>">
                        <input type='hidden' name='delete_id' value="<?php echo h($invoice['id']);?>">
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>
<hr>
<div class="paging">
    <?php if ($page > 1) :  ?>
        <span>
            <a class="pgbtn" href="index.php?id=<?php echo h($_GET['id']) ?>&page=<?php print(h($page) -1);
            if (!empty($_GET['search'])) {
                echo '&search='.h($_GET['search']) ;
            }
            if (!empty($_GET['order'])) {
                echo '&order='.h($_GET['order']) ;
            } elseif (!empty($_GET['order2'])) {
                echo '&order2='.h($_GET['order2']) ;
            } ?>">←前へ</a>
        </span>
    <?php endif; ?>
    <span class="pgbtn nowpage"><?php print h($page); ?></span>
    <?php if ($page < $maxPage) : ?>
        <span>
            <a class="pgbtn" href="index.php?id=<?php echo h($_GET['id']) ?>&page=<?php print h($page) + 1;
            if (!empty($_GET['search'])) {
                echo '&search='.h($_GET['search']) ;
            }
            if (!empty($_GET['order'])) {
                echo '&order='.h($_GET['order']) ;
            } elseif (!empty($_GET['order2'])) {
                echo '&order2='.h($_GET['order2']) ;
            } ?>">次へ→</a>
        </span>
    <?php endif; ?>
</div>
</main>
</body>
<script src="../../get_from_post.js"></script>
</html>
