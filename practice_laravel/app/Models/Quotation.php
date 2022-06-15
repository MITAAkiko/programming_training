<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quotation extends Model
{
    use HasFactory;
    public function company()//１対多リレーション（多）
    {
        return $this->belongsTo(Company::class);
    }
    public function fetchCompanyName($cid)
    {
        $data = DB::table('companies')
            ->where('id', $cid)
            ->first()
            ;
        $company = [
            'id' => $data->id,
            'company_name' => $data->company_name,
            'manager_name' => $data->manager_name,
            'prefix' => $data->prefix,
        ];
        return $company;
    }
    public function fetchDataSearched($get, $order = null)
    {
        $quotation = new Quotation;//manager_name紐づけなければいけない
        if ($order === 'DESC') {
            $datas = $quotation
            ->orderBy('id', 'desc')
            ->where('deleted', null)
            ->where('company_id', $get['id'])
            ->where('status', $get['search'])
            ->paginate(10)
            ;
        } else {
            $datas = $quotation
            ->where('deleted', null)
            ->where('company_id', $get['id'])
            ->where('status', $get['search'])
            ->paginate(10)
            ;
        }
        return $datas;
    }
    public function fetchData($id, $order = null)
    {
        $quotation = new Quotation;
        if ($order === 'DESC') {
            $datas = $quotation
            ->orderBy('id', 'desc')
            ->where('deleted', null)
            ->where('company_id', $id)
            ->paginate(10)
            ;
        } else {
            $datas = $quotation
            ->where('deleted', null)
            ->where('company_id', $id)
            ->paginate(10)
            ;
        }
        return $datas;
    }
    public function fetchId($id)//カウントして＋１
    {
        $getcount
        = DB::table('quotations')
        ->select(DB::raw('count(*)+1 as cnt'))
        ->where('company_id', $id)
        ->get()
        ->first()
        ;
        return $getcount->cnt;//配列ではなくオブジェクトとしてはいってくる。
    }
    public function create($cid, $quono, $value)
    {
        DB::table('quotations')->insert([
            'company_id' => $cid,
            'no' => $quono,
            'title' => $value['title'],
            'total' => $value['total'],
            'validity_period' => $value['period'],
            'due_date' => $value['due'],
            'status' => $value['status'],
            'created' => NOW(),
            'modified' => NOW(),
        ]);
    }
    public function fetchDataById($cid, $id)
    {
        $data = new Quotation;
        $quotation = $data
        ->where('company_id', $cid)
        ->where('id', $id)
        ->get()
        ->first()
        ;
        return $quotation;
    }
    public function updateData($id, $value)
    {
        DB::table('quotations')
        ->where('id', $id)
        ->update([
            'title' => $value['title'],
            'total' => $value['total'],
            'validity_period' => $value['period'],
            'due_date' => $value['due'],
            'status' => $value['status'],
            'modified' => NOW()
        ])
        ;
    }
}
