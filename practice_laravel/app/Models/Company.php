<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;//リレーションでつかう？

class Company extends Model
{
    use HasFactory;
    public function invoices()//1対多リレーション（１）
    {
        return $this->hasMany(Invoice::class);
    }
    public function fetchDatas($order = null)
    {
        $company = new Company;
        if ($order === 'DESC') {//降順
            $datas = $company
            ->orderBy('id', 'desc')
            ->where('deleted', null)
            ->paginate(10);
        } else {//昇順
            $datas = $company
            ->where('deleted', null)
            ->paginate(10);
        }
        return $datas;
    }
    public function fetchSearchedDatas($search, $order = null)//検索あり
    {
        $src = '%' . addcslashes($search, '%_\\') . '%';
        $company = new Company;

        if ($order === 'DESC') {//降順
            $datas = $company
            ->orderBy('id', 'desc')
            ->where(function ($query) {
                $query->where('deleted', null);
            })
            ->where(function ($query) use ($src) {
                $query->where('company_name', 'like', $src)
                    ->orWhere('manager_name', 'like', $src);
            })
            ->paginate(10)
            ;
        } else {//昇順
            $datas = $company
            ->where(function ($query) {
                $query->where('deleted', null);
            })
            ->where(function ($query) use ($src) {
                $query->where('company_name', 'like', $src)
                    ->orWhere('manager_name', 'like', $src);
            })
            ->paginate(10)
            ;
        }
        return $datas;
    }
    public function fetchDataById($id)//１つ分のデータ
    {
        $company = new Company;
            $datas = $company
                ->where('id', $id)
                ->get()
                ->first()
                ;
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
    public function updateData($id, $value)
    {
        DB::table('companies')
        ->where('id', $id)
        ->update([
            'company_name' => $value['name'],
            'manager_name' => $value['manager'],
            'phone_number' => $value['phone'],
            'postal_code' => $value['postal'],
            'prefecture_code' => $value['prefecture_code'],
            'address' => $value['address'],
            'mail_address' => $value['email'],
            'prefix' => $value['prefix'],
            'modified' => NOW()
        ]);
    }
    public function deleteData($id)
    {
        DB::table('companies')
        ->where('id', $id)
        ->update([ 'deleted' => NOW() ]);
    }
}
