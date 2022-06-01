<?php
namespace App\Requests;

{
class Request
{

    public function isError($err, $nonerror)
    {
        return $err !== $nonerror;
    }
    public function blank($value, $subject)
    {
        if ($value === '' || $value === 'empty') {
            return $error[$subject]='blank';
        }
    }
    public function type($value, $subject, $preg)
    {
        if (!preg_match($preg, $value)) { //空文字ダメの半角数値
            return $error[$subject]='type';
        }
    }
    public function length($value, $subject, $maxLength)
    {
        if (strlen($value)>$maxLength) {
            return $error[$subject]='long';
        }
    }
    public function size($value, $subject, $maxSize, $minSize)
    {
        if ($value>$maxSize || $value<$minSize) {
            return $error[$subject]='size';
        }
    }
}

// if (($post['postal_code'])==='') {
//     $error['postal_code']='blank';
// } elseif (!preg_match("/^[0-9]+$/", $post['postal_code'])) { //空文字ダメの半角数値
//     $error['postal_code']='type';
// } elseif (strlen($post['postal_code'])>7) {
//     $error['postal_code']='long';
// }
}
