<?php
//htmlspecialchars
function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}
