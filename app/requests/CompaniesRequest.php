<?php
namespace App\Requests;

//親クラスを呼び出す
require_once('Request.php');
use App\Requests\Request;

class CompaniesRequest extends Request
{
    //初期値
    private $nonerror=[
        'name' => '',
        'manager' => '',
        'phone' => '',
        'postal_code' => '',
        'prefecture_code' => '',
        'address' => '',
        'email' => '',
        'prefix' => '',
    ];
    private $error = '';

    public function __construct()
    {
        $this->error = $this->nonerror;
    }
    //エラーがあるか
    public function checkIsError($post)
    {
        $this->nameError($post['name']);
        $this->managerError($post['manager']);
        $this->phoneError($post['phone']);
        $this->postalError($post['postal_code']);
        $this->prefectureError($post['prefecture_code']);
        $this->addressError($post['address']);
        $this->emailError($post['email']);
        $this->prefixError($post['prefix']);
        //エラーチェック
        return $this->isError($this->error, $this->nonerror);
    }
    //エラー内容は何か
    public function getError()
    {
        return $this->error;
    }
    //各エラーチェック
    private function nameError($input)
    {
        $errors = [
            $this->blank($input),
            $this->length($input, 64)
        ];
        $this->error['name'] = $this->errors($errors);
        return $this->error['name'];
    }
    private function managerError($input)
    {
        $errors = [
            $this->blank($input),
            $this->length($input, 32),
        ];
        $this->error['manager'] = $this->errors($errors);
        return $this->error['manager'];
    }
    private function phoneError($input)
    {
        $errors = [
            $this->blank($input),
            $this->type($input, '/^[0-9]+$/'),
            $this->length($input, 11)
        ];
        $this->error['phone'] = $this->errors($errors);
        return $this->error['phone'];
    }
    private function postalError($input)
    {
        $errors = [
            $this->blank($input),
            $this->type($input, "/^[0-9]+$/"),
            $this->digit($input, 7),
        ];
        $this->error['postal_code'] = $this->errors($errors);
        return $this->error['postal_code'];
    }
    private function prefectureError($input)
    {
        $errors = [
            $this->blank($input),
            $this->type($input, "/^[0-9]+$/"),
            $this->length($input, 2),
            $this->size($input, 47, 1)
        ];
        $this->error['prefecture_code'] = $this->errors($errors);
        return $this->error['prefecture_code'];
    }
    private function addressError($input)
    {
        $errors = [
            $this->blank($input),
            $this->length($input, 100)
        ];
        $this->error['address'] = $this->errors($errors);
        return $this->error['address'];
    }
    private function emailError($input)
    {
        $errors = [
            $this->blank($input),
            $this->type($input, "/^[a-zA-Z0-9_+-]+(.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/"),
            $this->length($input, 100),
        ];
        $this->error['email'] = $this->errors($errors);
        return $this->error['email'];
    }
    private function prefixError($input)
    {
        $errors = [
            $this->blank($input),
            $this->type($input, "/^[0-9a-zA-Z]+$/"),
            $this->length($input, 16)
        ];
        $this->error['prefix'] = $this->errors($errors);
        return $this->error['prefix'];
    }
}
