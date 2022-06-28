<?php

$user = 'root';
$pass = 'P@ssw0rd';
$db = new \PDO('mysql:dbname=programming_training;host=127.0.0.1;charset=utf8', $user, $pass);

while (1) {
    echo '何にデータを追加しますか[C/Q/I]: ';
    // 標準入力から取得
    $line = trim(fgets(STDIN));// 入力後エンターキーが押されるまで待ち状態
    if ($line === 'C') {
        echo '何件追加しますか: ';
        $num = trim(fgets(STDIN));
        $getid = $db->query('SELECT max(id)+1 as no from companies')->fetch();//idを取得
        $name = '株式会社テスト'.$getid['no'];
        $manager = 'テスト太郎';
        $phone = '0'.rand(100000000, 999999999);
        $postal_code = rand(1000000, 9999999);
        $prefecture_code = rand(0, 47);
        $address = 'テスト区テスト0-0-0';
        $email = 'test@test.com';
        $prefix = 'test'.$getid['no'];
        for ($i=1; $i<=$num; $i++) {
            $statement = $db->prepare('INSERT INTO companies
                SET company_name = ?, manager_name = ?, phone_number = ?, postal_code = ?,
                prefecture_code = ?, address = ?, mail_address = ?, prefix = ?, created = NOW(), modified = NOW()');
            $statement->bindParam(1, $name, \PDO::PARAM_STR);
            $statement->bindParam(2, $manager, \PDO::PARAM_STR);
            $statement->bindParam(3, $phone, \PDO::PARAM_STR);
            $statement->bindParam(4, $postal_code, \PDO::PARAM_STR);
            $statement->bindParam(5, $prefecture_code, \PDO::PARAM_INT);
            $statement->bindParam(6, $address, \PDO::PARAM_STR);
            $statement->bindParam(7, $email, \PDO::PARAM_STR);
            $statement->bindParam(8, $prefix, \PDO::PARAM_STR);
            $statement->execute();
        }
        echo '完了しました';
        break;
    } elseif ($line === 'Q') {
        echo '会社ID: ';
        $id = trim(fgets(STDIN));// 入力後エンターキーが押されるまで待ち状態

        for ($i=1; $i<=10; $i++) {
            $prefix = $db->query('SELECT prefix AS prf from companies WHERE id ='.$id)->fetch();
            $getid = $db->query('SELECT count(*)+1 AS no FROM quotations WHERE company_id ='.$id)->fetch();//idを取得
            $quoId = str_pad($getid['no'], 8, 0, STR_PAD_LEFT); // 8桁にする
            $no = $prefix['prf'].'-q-'.$quoId;//請求番号
            $title = '見積'.$getid['no'];
            $total = rand(100, 1000000000);
            $minday = strtotime('2020/01/01 00:00:00');
            $maxday = strtotime('2030/12/31 12:59:59');
            $period = date('Ymd', rand($minday, $maxday));
            $day = new Datetime($period);
            $due = $day->modify('+1 year')->format('Ymd');
            if ($i%9 === 0) {
                $status = 9;
            } elseif ($i%2 === 0) {
                $status = 2;
            } else {
                $status = 1;
            }
            $statement = $db->prepare(
                'INSERT INTO quotations SET company_id = ?, no = ?,title = ?, total = ?,
                validity_period = ?, due_date = ?, status = ?, created = NOW(), modified = NOW()'
            );
            $statement->bindParam(1, $id, \PDO::PARAM_INT);
            $statement->bindParam(2, $no, \PDO::PARAM_STR);
            $statement->bindParam(3, $title, \PDO::PARAM_STR);
            $statement->bindParam(4, $total, \PDO::PARAM_INT);
            $statement->bindParam(5, $period, \PDO::PARAM_INT);
            $statement->bindValue(6, $due, \PDO::PARAM_STR);
            $statement->bindParam(7, $status, \PDO::PARAM_INT);
            $statement->execute();
        }
        echo "完了しました。\n";
        break;
    } elseif ($line === 'I') {
        echo '会社ID: ';
        $id = trim(fgets(STDIN));// 入力後エンターキーが押されるまで待ち状態
                
        for ($i=1; $i<=10; $i++) {
            $prefix = $db->query('SELECT prefix AS prf from companies WHERE id ='.$id)->fetch();
            $getid = $db->query('SELECT count(*)+1 AS no FROM invoices WHERE company_id ='.$id)->fetch();//idを取得
            $invoiceId = str_pad($getid['no'], 8, 0, STR_PAD_LEFT); // 8桁にする
            $no = $prefix['prf'].'-i-'.$invoiceId;//請求番号
            $title = '請求'.$getid['no'];
            $total = rand(100, 1000000000);
            $minday = strtotime('2020/01/01 00:00:00');
            $maxday = strtotime('2030/12/31 12:59:59');
            $pay = date('Ymd', rand($minday, $maxday));
            $day = new Datetime($pay);
            $date = $day->modify('-1 year')->format('Ymd');
            $quo = 'test'.$getid['no'];
            if ($i%9 === 0) {
                $status = 9;
            } elseif ($i%2 === 0) {
                $status = 2;
            } else {
                $status = 1;
            }
            $statement = $db->prepare(
                'INSERT INTO invoices SET company_id = ?,no = ?,
                title = ?, total = ?, payment_deadline = ?, date_of_issue = ?, quotation_no = ?, status = ?, 
                created = NOW(), modified = NOW()'
            );
            $statement->bindParam(1, $id, \PDO::PARAM_INT);
            $statement->bindParam(2, $no, \PDO::PARAM_STR);
            $statement->bindParam(3, $title, \PDO::PARAM_STR);
            $statement->bindParam(4, $total, \PDO::PARAM_INT);
            $statement->bindParam(5, $pay, \PDO::PARAM_INT);
            $statement->bindParam(6, $date, \PDO::PARAM_INT);
            $statement->bindParam(7, $quo, \PDO::PARAM_STR);
            $statement->bindParam(8, $status, \PDO::PARAM_INT);
            $statement->execute();
        }
        echo "完了しました。\n";
        break;
    } else {
        echo "処理を終了します。もう一度、初めからやり直してください\n";
        break;
    }
}
