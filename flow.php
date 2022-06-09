<?php
function exercise()
{
    $arr = [9, 4, 3, 1, 5, 7, 10, 0];
    $cnt = count($arr);
    $tmp = 0;
    for ($j = $cnt; $j > 0; $j--) {
        for ($i=0; $i<$j-1; $i++) {
            if ($arr[$i] > $arr[$i+1]) {
                $tmp = $arr[$i];
                $arr[$i] = $arr[$i+1];
                $arr[$i+1] = $tmp;
            }
        }
    }
    print_r($arr);
}
exercise();
