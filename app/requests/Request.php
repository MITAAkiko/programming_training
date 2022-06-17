<?php
namespace App\Requests

{//カッコがないと、子クラスでnamespaceとrequireでnamespaceが衝突してしまうので閉じた。
    class Request
    {

        public function isError($err, $nonerror)
        {
            return $err !== $nonerror;
        }
        //項目のエラーチェック
        public function errors($errors)
        {
            foreach ($errors as $error) {
                if ($error !== '') {
                    return $error;
                }
            }
            $error = '';
            return $error;
        }
        public function blank($value)
        {
            if ($value === '') {
                return 'blank';
            } else {
                return '';
            }
        }
        public function type($value, $preg)
        {
            if (!preg_match($preg, $value)) { //空文字ダメの半角数値
                return 'type';
            } else {
                return '';
            }
        }
        public function length($value, $maxLength)
        {
            if (strlen($value) > $maxLength) {
                return 'long';
            } else {
                return '';
            }
        }
        public function digit($value, $length)
        {
            if (strlen($value) !== $length) {
                return 'long';
            } else {
                return '';
            }
        }
        public function size($value, $maxSize, $minSize)
        {
            if ($value > $maxSize || $value < $minSize) {
                return 'size';
            } else {
                return '';
            }
        }
        public function checkDate($value)
        {
            if (strtotime($value) === false) {
                return 'check_date';
            } else {
                return '';
            }
        }
        public function compareDates($value1, $value2)
        {
            if ($value1 < $value2) {
                return 'time';
            } else {
                return '';
            }
        }
    }
}
