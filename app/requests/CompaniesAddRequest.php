<?php
namespace App\Requests;

//親クラスを呼び出す
require_once('Request.php');

use App\Requests\Request;

class CompaniesAddRequest extends Request
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
    private $error = [
        'name' => '',
        'manager' => '',
        'phone' => '',
        'postal_code' => '',
        'prefecture_code' => '',
        'address' => '',
        'email' => '',
        'prefix' => '',
    ];
    //private $isError = '';

    public function __construct()
    {
        // $this->nonerror=[
        //     'name' => '',
        //     'manager' => '',
        //     'phone' => '',
        //     'postal_code' => '',
        //     'prefecture_code' => '',
        //     'address' => '',
        //     'email' => '',
        //     'prefix' => '',
        // ];
        // $this->error=[
        //     'name' => '',
        //     'manager' => '',
        //     'phone' => '',
        //     'postal_code' => '',
        //     'prefecture_code' => '',
        //     'address' => '',
        //     'email' => '',
        //     'prefix' => '',
        // ];
    }

    public function checkIsError($post)
    {
        $this->error['name'] = $this->length($post['name'], 'name', 64);
        $this->error['name'] = $this->blank($post['name'], 'blank');
        

        // if (($post['name'])==='') {
        //     $error['name']='blank';
        // } elseif (strlen($post['name'])>64) {
        //     $error['name']='long';
        // }
        
        
        $this->error['manager'] = $this->length($post['manager'], 'manager', 32);
        $this->error['manager'] = $this->blank($post['manager'], 'manager');
        // if (($post['manager'])==='') {
        //     $error['manager']='blank';
        // } elseif (strlen($post['manager'])>32) {
        //     $error['manager']='long';
        // }
        
        $this->error['phone'] = $this->type($post['phone'], 'phone', '/^[0-9]+$/');
        $this->error['phone'] = $this->length($post['phone'], 'phone', 11);
        $this->error['phone'] = $this->blank($post['phone'], 'phone');
        
        // if (($post['phone'])==='') {
        //     $error['phone']='blank';
        // } elseif (!preg_match('/^[0-9]+$/', $post['phone'])) { //空文字ダメの半角数値
        //     $error['phone']='type';
        // } elseif (strlen($post['phone'])>11) {
        //     $error['phone']='long';
        // }

       
        $this->error['postal_code'] = $this->type($post['postal_code'], 'postal_code', "/^[0-9]+$/");
        $this->error['postal_code'] = $this->length($post['postal_code'], 'postal_code', 7);
        $this->error['postal_code'] = $this->blank($post['postal_code'], 'postal_code');
        // if (($post['postal_code'])==='') {
        //     $error['postal_code']='blank';
        // } elseif (!preg_match("/^[0-9]+$/", $post['postal_code'])) { //空文字ダメの半角数値
        //     $error['postal_code']='type';
        // } elseif (strlen($post['postal_code'])>7) {
        //     $error['postal_code']='long';
        // }
        
        
        $this->error['prefecture_code'] = $this->type($post['prefecture_code'], 'prefecture_code', "/^[0-9]+$/");
        $this->error['prefecture_code'] = $this->length($post['prefecture_code'], 'prefecture_code', 2);
        $this->error['prefecture_code'] = $this->size($post['prefecture_code'], 'prefecture_code', 47, 1);
        $this->error['prefecture_code'] = $this->blank($post['prefecture_code'], 'prefecture_code');// if (($post['prefecture_code'])==='') {
        //     $error['prefecture_code']='blank';
        // } elseif (($post['prefecture_code'])==="empty") {
        //     $error['prefecture_code']='blank';
        // } elseif (!preg_match("/^[0-9]+$/", $post['prefecture_code'])) { //空文字ダメの半角数値
        //     $error['prefecture_code']='type';
        // } elseif (($post['prefecture_code'])>47 || ($post['prefecture_code'])<1) {
        //     $error['prefecture_code']='long47';
        // }
        
        
        $this->error['address'] = $this->length($post['address'], 'address', 100);
        $this->error['address'] = $this->blank($post['address'], 'address');
        // if (($post['address'])==='') {
        //     $error['address']='blank';
        // } elseif (strlen($post['address'])>100) {
        //     $error['address']='long';
        // }
        
        $this->error['email'] = $this->type($post['email'], 'email', "/^[a-zA-Z0-9_+-]+(.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/");
        $this->error['email'] = $this->length($post['email'], 'email', 100);
        $this->error['email'] = $this->blank($post['email'], 'email');
        // if (($post['email'])==='') {
        //     $error['email']='blank';
        // } elseif (!preg_match("/^[a-zA-Z0-9_+-]+(.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/", $post['email'])) {
        //     $error['email']='type';
        // } elseif (strlen($post['email'])>100) {
        //     $error['email']='long';
        // }

        
        $this->error['prefix'] = $this->length($post['prefix'], 'prefix', 16);
        $this->error['prefix'] = $this->type($post['prefix'], 'prefix', "/^[0-9a-zA-Z]+$/");
        $this->error['prefix'] = $this->blank($post['prefix'], 'prefix');
        // if (($post['prefix'])==='') {
        //     $error['prefix']='blank';
        // } elseif (strlen($post['prefix'])>16) {
        //     $error['prefix']='long';
        // } elseif (!preg_match("/^[0-9a-zA-Z]+$/", $post['prefix'])) {//半角英数字、空文字NG
        //     $error['prefix']='type';
        // }

        //エラーチェック
        return $this->isError($this->error, $this->nonerror);
    }
    public function getError()
    {
        return $this->error;
    }
}
