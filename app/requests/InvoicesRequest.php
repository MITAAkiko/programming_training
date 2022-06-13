<?php
namespace App\Requests
{
    //親クラスを呼び出す
    require_once('Request.php');
    use App\Requests\Request;

    class InvoicesRequest extends Request
    {
        //初期値
        private $nonerror=[
            'title' => '',
            'total' => '',
            'pay' => '',
            'date' => '',
            'quo' => '',
            'status' => ''
        ];
        private $error = '';

        public function __construct()
        {
            $this->error = $this->nonerror;
        }
        public function checkIsError($post)
        {
            $this->titleIsError($post['title']);
            $this->totalIsError($post['total']);
            $this->payIsError($post['pay'], $post['date']);
            $this->dateIsError($post['date']);
            $this->quoIsError($post['quo']);
            $this->statusIsError($post['status']);
            return $this->isError($this->error, $this->nonerror);
        }
        //エラー内容は何か
        public function getError()
        {
            return $this->error;
        }
        //各エラーチェック
        private function titleIsError($input)
        {
            $errors=[
                $this->blank($input),
                $this->length($input, 64)
            ];
            $this->error['title'] = $this->errors($errors);
            return $this->error['title'];
        }
        private function totalIsError($input)
        {
            $errors=[
                $this->blank($input),
                $this->type($input, "/^[0-9]+$/"),
                $this->length($input, 10)
            ];
            $this->error['total'] = $this->errors($errors);
            return $this->error['total'];
        }
        private function payIsError($input, $input2)
        {
            $errors=[
                $this->blank($input),
                $this->type($input, "/^[0-9]{8}$/"),
                $this->checkDate($input),
                $this->cfTime($input, $input2),//あっているか
            ];
            $this->error['pay'] = $this->errors($errors);
            return $this->error['pay'];
        }
        private function dateIsError($input)
        {
            $errors=[
                $this->blank($input),
                $this->type($input, "/^[0-9]{8}$/"),
                $this->checkDate($input),
            ];
            $this->error['date'] = $this->errors($errors);
            return $this->error['date'];
        }
        private function quoIsError($input)
        {
            $errors=[
                $this->blank($input),
                $this->length($input, 100),
                $this->type($input, "/^[0-9a-zA-Z]+$/"),
            ];
            $this->error['quo'] = $this->errors($errors);
            return $this->error['quo'];
        }
        private function statusIsError($input)
        {
            $errors=[
                $this->blank($input),
                $this->type($input, "/^[0-9]+$/"),
                $this->length($input, 2)
            ];
            $this->error['status'] = $this->errors($errors);
            return $this->error['status'];
        }
    // if (!preg_match("/^[0-9a-zA-Z]+$/", $post['quo'])) { //空文字ダメの半角数値
            //         $error['quo']='type';
            //     }
            //     if (($post['quo'])==='') {
            //         $error['quo']='blank';
            //     } elseif (strlen($post['quo'])>100) {
            //         $error['quo']='long';
            //     }
            //     if (($post['title'])==='') {
            //         $error['title']='blank';
            //     } elseif (strlen($post['title'])>64) {
            //         $error['title']='long';
            //     }
            //     if (($post['total'])==='') {
            //         $error['total']='blank';
            //     } elseif (!preg_match('/^[0-9]+$/', $post['total'])) { //空文字ダメの半角数値
            //         $error['total']='type';
            //     } elseif (strlen($post['total'])>10) {
            //         $error['total']='long';
            //     }
            //     if (($post['pay'])==='') {
            //         $error['pay']='blank';
            //     } elseif (!preg_match('/^[0-9]{8}$/', $post['pay'])) {
            //         $error['pay']='type';
                // } elseif (strtotime($post['pay'])===false) {
                //     $error['pay']='check_date';
            //     } elseif (strtotime($post['pay']) < strtotime($post['date'])) {
            //         $error['pay']='time';
            //     }

            //     if (($post['date'])==='') {
            //         $error['date']='blank';
            //     } elseif (!preg_match('/^[0-9]{8}$/', $post['date'])) {
            //         $error['date']='type';
            //     } elseif (strtotime($post['date'])===false) {
            //         $error['date']='check_date';
            //     }
            //     if (!preg_match("/^[0-9]+$/", $post['status'])) { //空文字ダメの半角数値
            //         $error['status']='type';
            //     } elseif (strlen($post['status'])>1) {
            //         $error['status']='long';
            //     } elseif (($post['status'])==='') {
            //         $error['status']='blank';
            //     }
    }
}
