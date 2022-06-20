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
        $this->nameIsError($post['name']);
        $this->managerIsError($post['manager']);
        $this->phoneIsError($post['phone']);
        $this->postalIsError($post['postal_code']);
        $this->prefectureIsError($post['prefecture_code']);
        $this->addressIsError($post['address']);
        $this->emailIsError($post['email']);
        $this->prefixIsError($post['prefix']);
        //エラーチェック
        return $this->isError($this->error, $this->nonerror);
    }
    //エラー内容は何か
    public function getError()
    {
        return $this->error;
    }
    //各エラーチェック
    private function nameIsError($input)
    {
        $errors = [
            $this->blank($input),
            $this->length($input, 64)
        ];
        $this->error['name'] = $this->errors($errors);
        return $this->error['name'];
    }
    private function managerIsError($input)
    {
        $errors = [
            $this->blank($input),
            $this->length($input, 32),
        ];
        $this->error['manager'] = $this->errors($errors);
        return $this->error['manager'];
    }
    private function phoneIsError($input)
    {
        $errors = [
            $this->blank($input),
            $this->type($input, '/^[0-9]+$/'),
            $this->length($input, 11)
        ];
        $this->error['phone'] = $this->errors($errors);
        return $this->error['phone'];
    }
    private function postalIsError($input)
    {
        $errors = [
            $this->blank($input),
            $this->type($input, "/^[0-9]+$/"),
            $this->digit($input, 7),
        ];
        $this->error['postal_code'] = $this->errors($errors);
        return $this->error['postal_code'];
    }
    private function prefectureIsError($input)
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
    private function addressIsError($input)
    {
        $errors = [
            $this->blank($input),
            $this->length($input, 100)
        ];
        $this->error['address'] = $this->errors($errors);
        return $this->error['address'];
    }
    private function emailIsError($input)
    {
        $errors = [
            $this->blank($input),
            $this->type($input, "/^[a-zA-Z0-9_+-]+(.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/"),
            $this->length($input, 100),
        ];
        $this->error['email'] = $this->errors($errors);
        return $this->error['email'];
    }
    private function prefixIsError($input)
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
