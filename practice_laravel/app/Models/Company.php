<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    public function fetchData()
    {
        $company = new Company;
            $datas = $company
                ->offset(0)//スタート位置 ページ数から出来るよう調整
                ->limit(10)
                ->where('deleted', null)
                ->get();
        return $datas;
    }
}
