<?php

require('../dbconnect.php');
require_once('../config.php');
//初期値
$page = 1;
if (!empty($_GET['page'])) {
    $page= $_GET['page'];
    if ($page == '') {
        $page=1;
    }
}
if (empty($_GET['order'])) {
    $_GET['order']=1;
}
//最小値
$page = max($page, 1);
//最後のページを取得する
if (!empty($_GET['search'])) {
    $counts = $db->prepare('SELECT COUNT(*) AS cnt FROM invoices WHERE company_id=? AND deleted IS NULL AND status=?');
    $counts -> bindParam(1, $_GET['id'], PDO::PARAM_INT);
    $counts -> bindParam(2, $_GET['search'], PDO::PARAM_INT);
    $counts -> execute();
    $cnt = $counts->fetch();
    $maxPage = ceil($cnt['cnt']/10);
} else {
    $counts = $db->prepare('SELECT COUNT(*) AS cnt FROM invoices WHERE company_id=? AND deleted IS NULL');
    $counts -> bindParam(1, $_GET['id'], PDO::PARAM_INT);
    $counts -> execute();
    $cnt = $counts->fetch();
    $maxPage = ceil($cnt['cnt']/10);
}

//最大値
$maxPage = max($maxPage, 1);
$page = min($page, $maxPage);

//ページ
$start = ($page - 1) * 10;

//DBに接続する用意
//絞り込みあり
if (!empty($_GET['search'])) {
    if (($_GET['order'])>0) {
        $invoices = $db -> prepare('SELECT   i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
            FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL AND i.status=? ORDER BY i.no ASC LIMIT ?,10');
        $invoices -> bindParam(1, $_GET['id'], PDO::PARAM_INT);
        $invoices -> bindParam(2, $_GET['search'], PDO::PARAM_INT);
        $invoices -> bindParam(3, $start, PDO::PARAM_INT);
        $invoices -> execute();
    } else {
        $invoices = $db -> prepare('SELECT   i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
            FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL AND i.status=? ORDER BY i.no DESC LIMIT ?,10');
        $invoices -> bindParam(1, $_GET['id'], PDO::PARAM_INT);
        $invoices -> bindParam(2, $_GET['search'], PDO::PARAM_INT);
        $invoices -> bindParam(3, $start, PDO::PARAM_INT);
        $invoices -> execute();
    }
} else {
    if (($_GET['order'])>0) {
        $invoices = $db -> prepare('SELECT  i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
            FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL ORDER BY i.no ASC LIMIT ?,10');
        $invoices -> bindParam(1, $_GET['id'], PDO::PARAM_INT);
        $invoices -> bindParam(2, $start, PDO::PARAM_INT);
        $invoices -> execute();
    } else {
        $invoices = $db -> prepare('SELECT  i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
            FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL ORDER BY i.no DESC LIMIT ?,10');
        $invoices -> bindParam(1, $_GET['id'], PDO::PARAM_INT);
        $invoices -> bindParam(2, $start, PDO::PARAM_INT);
        $invoices -> execute();
    }
}
//会社名を表示させる（見積がないときなど）
$companies = $db -> prepare('SELECT  company_name, id FROM companies WHERE id=? AND deleted IS NULL');
$companies -> bindParam(1, $_GET['id'], PDO::PARAM_INT);
$companies -> execute();
$company = $companies -> fetch();

//htmlspecialchars
function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}
//idのない人を返す
if (empty($_GET['id']) || $_GET['id']=='') {
    header('Location:../');
    exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../style.css">
<link rel="stylesheet" type="text/css" href="i_style.css">
    <title>プログラミング実習</title>
</head>

<body>
<main>
    <div class="contents">
    <h2 class="home">請求一覧 <a href="../" class="btn">会社一覧へ戻る</a>
        <span class="company_name"><?php echo h($company['company_name']) ?></span></h2>
    <hr>
    <a href="./make_invoice.php?id=<?php echo h($_GET['id']) ?>" class="long_btn">請求作成</a>
    <form action='./' method="get" href='./?id=<?php echo h($_GET['id']) ?>&search=<?php echo h($_GET['search']) ?>'><!--getにhrefいらない？自動で入力？-->
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
    
    <br><br>
    <table>
        <tr class="table_heading">
            <form action='index.php' method=get>

                <input type='hidden' name='id' value="<?php echo h($_GET['id']); ?>" >
                <?php if (!empty($_GET['search'])) : ?>
                    <input type='hidden' name='search' value="<?php echo h($_GET['search']); ?>" >
                <?php endif; ?>
                <input type='hidden' name='order' value="<?php echo h($_GET['order'] *= -1) ?>" >
                <th class="no">請求番号　<input class="ascdesc" type="submit" value="▼"></th>

            </form>
                
            <th class="title">請求名</th><th class="manager">担当者名</th><th class="total">金額</th>
            <th class="pay">支払期限</th><th class="date">請求日</th><th class="quo">見積番号</th>
            <th class="status">状態</th><th class="i_edit">編集</th><th class="i_delete">削除</th>
            
        </tr>
        
        <?php  foreach ($invoices as $invoice) : ?>
                <tr>
                    <td class="td"><?php echo h($invoice['no']);?></td>
                    <td class="td"><?php echo h($invoice['title']);?></td>
                    <td class="td"><?php echo h($invoice['manager_name']);?></td>
                    <td class="td"><?php echo h(number_format($invoice['total']));?>円</td><!--カンマをつける-->
                    <td class="td"><?php echo h(str_replace('-', '/', $invoice['payment_deadline']));?><br>
                    <td class="td"><?php echo h(str_replace('-', '/', $invoice['date_of_issue']));?></td>
                    <td class="td"><?php echo h($invoice['quotation_no']);?></td>
                    <td class="td"><?php echo h(STATUSES[$invoice['status']]);?></td>
                    <td class="td"><a class="edit_delete" href="i_edit.php?id=<?php echo h($invoice['id']) ?>&cid=<?php echo h($company['id']) ?>">編集</a></td>
                    <td class="td"><a class="edit_delete" href="i_delete.php?id=<?php echo h($invoice['id']);?>&cid=<?php echo h($company['id']) ?>" onclick="return cfm()">削除</a></td>
                </tr>
        <?php endforeach; ?>
            <!--==koko-->       
    </table>
<hr>
<div class="paging">
    <?php if ($page > 1) :  ?>
        <span><a class="pgbtn" href="index.php?id=<?php echo h($_GET['id']) ?>&page=<?php print(h($page) -1);
        if (!empty($_GET['search'])) {
             echo '&search='.h($_GET['search']) ;
        }
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order']*-1) ;
        } ?>">←前</a></span>
    <?php endif; ?>
    <span class="pgbtn nowpage"><?php print h($page); ?></span>
    <?php if ($page < $maxPage) : ?>
        <span><a class="pgbtn" href="index.php?id=<?php echo h($_GET['id']) ?>&page=<?php print h($page) + 1;
        if (!empty($_GET['search'])) {
             echo '&search='.h($_GET['search']) ;
        }
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order']*-1) ;
        } ?>">次へ→</a></span>
    <?php endif; ?>
</div>

</main>
</body>


<script>
function cfm(){
    return confirm('本当に削除しますか');
}

</script>


</html>






