<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    public function create($value)
    {
        DB::table('companies')->insert([

            'company_name' => $value['name'],
            'manager_name' => $value['manager'],
            'phone_number' => $value['phone'],
            'postal_code' => $value['postal'],
            'prefecture_code' => $value['prefecture_code'],
            'address' => $value['address'],
            'mail_address' => $value['email'],
            'prefix' => $value['prefix'],
            'created' => NOW(),
            'modified' => NOW()
        ]);
    }
    public function fetchDataById($id)
    {
        $company = new Company;
            $datas = $company
                ->where('id', $id)
                ->get();
        return $datas;
    }
}
