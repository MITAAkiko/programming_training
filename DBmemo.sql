INSERT INTO companies SET 
    company_name = '株式会社テスト00',
    manager_name = 'テスト太郎' ,
    phone_number = 00000000000 ,
    postal_code = 0000000 ,
    prefecture_code = 11,
    address = '東京都テスト区テスト0-0-0' ,
    mail_address = 'test@test.co.jp' ,
    prefix = 'test',
    created = NOW(),
    modified = now();



| id              | int(11)      | NO   | PRI | NULL    | auto_increment |
| company_name    | varchar(64)  | NO   |     | NULL    |                |
| manager_name    | varchar(32)  | NO   |     | NULL    |                |
| phone_number    | varchar(11)  | NO   |     | NULL    |                |
| postal_code     | varchar(7)   | NO   |     | NULL    |                |
| prefecture_code | int(11)      | NO   |     | NULL    |                |
| address         | varchar(100) | NO   |     | NULL    |                |
| mail_address    | varchar(100) | NO   |     | NULL    |                |
| prefix          | varchar(16)  | NO   |     | NULL    |                |
| created         | datetime     | NO   |     | NULL    |                |
| modified        | datetime     | NO   |     | NULL    |                |
| deleted 


INSERT INTO quotations SET 
    company_id = '3',
    no = 'yep000-q000' ,
    title = '見積名' ,
    total = 100000,
    validity_period = 2022-05-23 ,
    due_date  = '2022-05-30',
    status = 1,
    created = NOW(),
    modified = now();

    mysql> show columns from quotations;
+-----------------+--------------+------+-----+---------+----------------+
| Field           | Type         | Null | Key | Default | Extra          |
+-----------------+--------------+------+-----+---------+----------------+
| id              | int(11)      | NO   | PRI | NULL    | auto_increment |
| company_id      | int(11)      | NO   | MUL | NULL    |                |
| no              | varchar(100) | NO   |     | NULL    |                |
| title           | varchar(64)  | NO   |     | NULL    |                |
| total           | int(11)      | NO   |     | NULL    |                |
| validity_period | varchar(32)  | NO   |     | NULL    |                |
| due_date        | date         | NO   |     | NULL    |                |
| status          | int(11)      | NO   |     | NULL    |                |
| created         | datetime     | NO   |     | NULL    |                |
| modified        | datetime     | NO   |     | NULL    |                |
| deleted         | datetime     | YES  | MUL | NULL    |                |



alter table quotations alter column validity_period date;
ALTER TABLE quotations ALTER COLUMN validity_period date;

ALTER TABLE quotations MODIFY validity_period date;

DELETE FROM quotations;

ALTER TABLE quotations MODIFY validity_period NOT NULL;

ALTER TABLE quotations MODIFY validity_period NOT NULL;


ALTER TABLE quotations MODIFY COLUMN validity_period date NOT NULL COMMENT '見積書有効期限';



mysql> show columns from invoices;
+------------------+--------------+------+-----+---------+----------------+
| Field            | Type         | Null | Key | Default | Extra          |
+------------------+--------------+------+-----+---------+----------------+
| id               | int(11)      | NO   | PRI | NULL    | auto_increment |
| company_id       | int(11)      | NO   | MUL | NULL    |                |
| no               | varchar(100) | NO   |     | NULL    |                |
| quotation_no     | varchar(100) | NO   |     | NULL    |                |
| title            | varchar(64)  | NO   |     | NULL    |                |
| total            | int(11)      | NO   |     | NULL    |                |
| payment_deadline | date         | NO   |     | NULL    |                |
| date_of_issue    | date         | NO   |     | NULL    |                |
| status           | int(11)      | NO   |     | NULL    |                |
| created          | datetime     | NO   |     | NULL    |                |
| modified         | datetime     | NO   |     | NULL    |                |
| deleted          | datetime     | YES  | MUL | NULL    |                |
+------------------+--------------+------+-----+---------+----------------+

INSERT INTO invoices SET 
    company_id = '2',
    no = 'yep2-i-00000000' ,
    quotation_no = 'yahho000',
    title = '請求名' ,
    total = 100000,
    payment_deadline = '20220505' ,
    date_of_issue  = '20220505',
    status = 1,
    created = NOW(),
    modified = now();

    mysql> show columns from quotations;