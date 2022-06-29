<?php
//htmlspecialchars
function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}
//状態(status)の入力値があっているか
function sttnum($value)
{
    if ($value === '1' || $value === '2' || $value === '9') {
        return $value;
    } else {
        return null;
    }
}
//昇順降順(order)の入力値があっているか
function ordnum($value)
{
    if ($value === '1' || $value === '-1') {
        return $value;
    } else {
        return null;
    }
}
//ページの入力値処理
function adjust_page($value, $max)
{
    $value = mb_convert_kana($value, "n"); //半角数字に合わせる
    if (!is_numeric($value)) {//数字かチェック1;
        return 1;
    }
    $value = ceil($value);//小数点繰り上げ
    //最小値
    $value = max($value, 1);
    //最大値（存在しないページを指定された場合）
    $value = min($value, $max);
    return $value;
}
