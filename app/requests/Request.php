<?php
namespace App\Requests

{//カッコがないと、子クラスでnamespaceとrequireでnamespaceが衝突してしまうので閉じた。
    class Request
    {

        public function isError($err, $nonerror)
        {
            return $err !== $nonerror;
        }
        public function blank($value)
        {
            if ($value === '' || $value === 'empty') {
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
            if (strlen($value)>$maxLength) {
                return 'long';
            } else {
                return '';
            }
        }
        public function size($value, $maxSize, $minSize)
        {
            if ($value>$maxSize || $value<$minSize) {
                return 'size';
            } else {
                return '';
            }
        }
    }
}
