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
                // ->offset(0)//スタート位置 ページ数から出来るよう調整
                // ->limit(10)
                ->where('deleted', null)
                ->paginate(10);
              //  ->get();
        return $datas;
    }
    public function fetchDataSearched($search)
    {
        $src = '%' . addcslashes($search, '%_\\') . '%';
        $company = new Company;
        $datas = $company
            // ->offset(0)//スタート位置 ページ数から出来るよう調整
            // ->limit(10)
            ->where(function ($query) {
                $query->where('deleted', null);
            })
            ->where(function ($query) use ($src) {
                $query->where('company_name', 'like', $src)
                      ->orWhere('manager_name', 'like', $src);
            })
            ->paginate(10)
            ;
              //  ->get();
        return $datas;
    }
}
