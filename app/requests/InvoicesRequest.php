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
            $this->titleError($post['title']);
            $this->totalError($post['total']);
            $this->payError($post['pay'], $post['date']);
            $this->dateError($post['date']);
            $this->quoError($post['quo']);
            $this->statusError($post['status']);
            return $this->isError($this->error, $this->nonerror);
        }
        //エラー内容は何か
        public function getError()
        {
            return $this->error;
        }
        //各エラーチェック
        private function titleError($input)
        {
            $errors = [
                $this->blank($input),
                $this->length($input, 64)
            ];
            $this->error['title'] = $this->errors($errors);
            return $this->error['title'];
        }
        private function totalError($input)
        {
            $errors = [
                $this->blank($input),
                $this->type($input, "/^[0-9]+$/"),
                $this->length($input, 10)
            ];
            $this->error['total'] = $this->errors($errors);
            return $this->error['total'];
        }
        private function payError($input, $input2)
        {
            $errors = [
                $this->blank($input),
                $this->type($input, "/^[0-9]{8}$/"),
                $this->checkDate($input),
                $this->compareDates($input, $input2),
            ];
            $this->error['pay'] = $this->errors($errors);
            return $this->error['pay'];
        }
        private function dateError($input)
        {
            $errors = [
                $this->blank($input),
                $this->type($input, "/^[0-9]{8}$/"),
                $this->checkDate($input),
            ];
            $this->error['date'] = $this->errors($errors);
            return $this->error['date'];
        }
        private function quoError($input)
        {
            $errors = [
                $this->blank($input),
                $this->length($input, 100),
                $this->type($input, "/^[0-9a-zA-Z]+$/"),
            ];
            $this->error['quo'] = $this->errors($errors);
            return $this->error['quo'];
        }
        private function statusError($input)
        {
            $errors = [
                $this->blank($input),
                $this->type($input, "/^[0-9]+$/"),
                $this->length($input, 2)
            ];
            $this->error['status'] = $this->errors($errors);
            return $this->error['status'];
        }
    }
}
