<?php
?>
<!DOCTYPE html>
<html>
  <head>
    <title>laravel練習</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
  </head>
  <main>
    <div class="contents">
    <h2 class="home" href='./'>会社一覧</h2>
    <hr>
    <a href="./add.php" class="long_btn">新規登録</a>
    <input class="search_btn" type="submit" value="検索">
    <input class="text_search" type="text" name="search" value="<?php
    if (!empty($_GET['search'])) {
        echo h($_GET['search']);
    } ?>">
    <br><br>
    <table id='companies_list'>
 
        <tr class="table_heading">
            <th class="th ID">会社番号　<input class="ascdesc" type="submit" value="▼"></th>
            <th class="th name">会社名</th><th class="th PIC">担当者名</th><th class="th tel">電話番号</th>
            <th class="th address">住所</th><th class="th email">メールアドレス</th>
            <th class="th quotation">見積一覧</th><th class="th invoice">請求一覧</th>
            <th class="th edit">編集</th><th class="th delete">削除</th>
        </tr>

    <?php foreach ($datas as $data) : ?>
        <tr>
            <td class="td"><?php echo ($data['id']);?></td>
            <td class="td"><?php echo ($data['company_name']);?></td>
            <td class="td"><?php echo ($data['manager_name']);?></td>
            <td class="td"><?php echo ($data['phone_number']);?></td>
            <td class="td"><?php echo ($data['postal_code']);?><br>
            <!-- <?php //echo (PREFECTURES[$data['prefecture_code']]).($company['address']);?></td> -->
            <?php echo $prefecture[$data["prefecture_code"]].($data['address']); ?></td>
            <td class="td"><?php echo ($data['mail_address']);?></td>
            <td class="td"><a class="list_btn" href="quotations/index.php?id=<?php echo ($data['id']); ?>">見積一覧</a></td>
            <td class="td"><a class="list_btn" href="invoices/index.php?id=<?php echo ($data['id']); ?>">請求一覧</a></td>
            <td class="td"><a class="edit_delete" href="./edit.php?id=<?php echo ($data['id']); ?>">編集</a></td>
            <td class="td"><a class="edit_delete" href="./delete.php?id=<?php echo ($data['id']); ?>" onclick="return cfm()">削除</a></td>
        </tr>
    <?php endforeach ?>
    </table>
    <hr>

  </body>
</html> 

