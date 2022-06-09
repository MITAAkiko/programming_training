<?php

require_once('../../dbconnect.php');
require_once('../../config.php');
require_once('../../functions.php');
require_once('../../app/controllers/QuotationsController.php');
use App\Controllers\QuotationController;

$cmp = new QuotationController;
$res = $cmp->index($_GET);
$company = $res['company'];
$page = $res['page'];
$maxPage = $res['maxPage'];
$quo = $res['quo'];
$quo_count = $res['quoCount'];
$_GET['order'] = $res['order'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../../style.css">
<link rel="stylesheet" type="text/css" href="q_style.css">
    <title>プログラミング実習</title>
</head>

<body>
<main>
    <div class="contents">
    <h2 class="home">見積一覧 <a href="../" class="btn">会社一覧へ戻る</a>
        <span class="company_name"><?php echo h($company['company_name']) ?></span></h2>
    <hr>
    <a href="./make_quote.php?id=<?php echo h($_GET['id']) ?>" class="long_btn">見積作成</a>
    <!--絞り込み-->
    <form action='./' method="get" href='./?id=<?php echo h($_GET['id']) ?>&search=<?php echo h($_GET['search']) ?>'>
        <input class="search_btn" type="submit" value="検索">
        <select class="text_search" name="search">
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
                <th class="no">見積番号　<input class="ascdesc" type="submit" value="▼"></th>
                
            </form>
            <th class="title">見積名</th><th class="manager">担当者名</th>
            <th class="total">金額</th><th class="period">見積書有効期限</th><th class="due">納期</th>
            <th class="status">状態</th><th class="q_edit">編集</th><th class="q_delete">削除</th>
        </tr>
<!--配列に代入-->

        <?php if ($_GET['order']>0) :
            for ($i=$quo_count-1-10*(h($page)-1); $i>$quo_count-1-10*(h($page)) && $i >=0; $i--) :
                ?><!--最大キー引く１０＊ページ数-->
                <tr>
                    <td class="td"><?php echo h($quo[$i]['no']);?></td>
                    <td class="td"><?php echo h($quo[$i]['title']);?></td>
                    <td class="td"><?php echo h($quo[$i]['manager']);?></td>
                    <td class="td"><?php echo h($quo[$i]['total']);?>円</td><!--カンマをつける-->
                    <td class="td"><?php echo h($quo[$i]['period']);?><br>
                    <td class="td"><?php echo h($quo[$i]['due']);?></td>
                    <td class="td"><?php echo h($quo[$i]['status']);?></td>
                    <td class="td"><a class="edit_delete" href="q_edit.php?id=<?php echo h($quo[$i]['id']) ?>&cid=<?php echo h($company['id']) ?>">編集</a></td>
                    <form action='q_delete.php' method=post>
                        <td class="td">
                            <a href="q_delete.php"><input type='submit' class="edit_delete" onclick="return cfm()" value='削除'></a>
                            <input type='hidden' name='cid' value="<?php echo h($company['id']) ?>">
                            <input type='hidden' name='delete_id' value="<?php echo h($quo[$i]['id']);?>">
                        </td>
                    </form>
                    <?php // var_dump($quo) ?>
                </tr>
            <?php endfor; ?>
        <?php else : //初期昇順設定
            for ($i=(h($page-1))*10; $i<(h($page))*10 && $i < $quo_count; $i++) :
                ?><!--0-9/10-19/-->
                <tr>
                    <td class="td"><?php echo h($quo[$i]['no']);?></td>
                    <td class="td"><?php echo h($quo[$i]['title']);?></td>
                    <td class="td"><?php echo h($quo[$i]['manager']);?></td>
                    <td class="td"><?php echo h($quo[$i]['total']);?>円</td>
                    <td class="td"><?php echo h($quo[$i]['period']);?><br>
                    <td class="td"><?php echo h($quo[$i]['due']);?></td>
                    <td class="td"><?php echo h($quo[$i]['status']);?></td>
                    <td class="td"><a class="edit_delete" href="q_edit.php?id=<?php echo h($quo[$i]['id']) ?>&cid=<?php echo h($company['id']) ?>">編集</a></td>
                    <form action='q_delete.php' method=post>
                        <td class="td">
                            <a href="q_delete.php"><input type='submit' class="edit_delete" onclick="return cfm()" value='削除'></a>
                            <input type='hidden' name='cid' value="<?php echo h($company['id']) ?>">
                            <input type='hidden' name='delete_id' value="<?php echo h($quo[$i]['id']);?>">
                        </td>
                    </form>
                </tr>
            <?php endfor; ?>
        <?php endif; ?>        
      
    </table>
<hr>

<div class="paging">
    <!--前へ-->
    <?php if ($page > 1) :  ?>
        <span><a class="pgbtn" href="index.php?id=<?php echo h($_GET['id']) ?>&page=<?php print(h($page) -1);
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order'])*-1 ;
        } ?>">←前</a></span>
    <?php endif; ?>
    <!--今のページ-->
    <span class="pgbtn nowpage"><?php print(h($page)); ?></span>
    <!--次へ-->
    <?php if ($page < $maxPage) : ?>
        <span><a class="pgbtn" href="index.php?id=<?php echo h($_GET['id']) ?>&page=<?php print(h($page) + 1);
        if (!empty($_GET['search'])) {
            echo '&search='.h($_GET['search']) ;
        }
        if (!empty($_GET['order'])) {
            echo '&order='.h($_GET['order'])*-1 ;
        } ?>">次へ→</a></span>
    <?php endif; ?>
</div>
</main>
</body>
<script>
function cfm()
{
    return confirm('本当に削除しますか');
}
</script>
</html>
