<?php
//htmlspecialchars
function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}
//状態の入力値があっているか
function sttnum($value)
{
    if ($value === '1' || $value === '2' || $value === '9') {
        return $value;
    } else {
        return $value = null;
    }
}
function ordnum($value)
{
    if ($value === '1' || $value === '-1') {
        return $value;
    } else {
        return $value = null;
    }
}
