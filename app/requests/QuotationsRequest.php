<?php
namespace App\Requests
{
    //親クラスを呼び出す
    require_once('Request.php');
    use App\Requests\Request;

    class QuotationsRequest extends Request
    {
        //初期値
        private $nonerror=[
            'title' => '',
            'total' => '',
            'period' => '',
            'due' => '',
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
            $this->periodError($post['period']);
            $this->dueError($post['due'], $post['period']);
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
        private function periodError($input)
        {
            $errors = [
                $this->blank($input),
                $this->type($input, "/^[0-9]{8}$/"),
                $this->checkDate($input),
            ];
            $this->error['period'] = $this->errors($errors);
            return $this->error['period'];
        }
        private function dueError($input, $input2)
        {
            $errors = [
                $this->blank($input),
                $this->checkDate($input),
                $this->type($input, "/^[0-9]{8}$/"),
                $this->compareDates($input, $input2),
            ];
            $this->error['due'] = $this->errors($errors);
            return $this->error['due'];
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
