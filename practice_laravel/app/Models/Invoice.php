<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Company;//リレーションでつかう？

class Invoice extends Model
{
    use HasFactory;
    public function company()//１対多リレーション（多）
    {
        return $this->belongsTo(Company::class);
    }
    public function fetchCompanyName($cid)
    {
        $data //= new Company;
        = DB::table('companies')
            ->where('id', $cid)
            ->first()
            ;
            $company = [
                'id' => $data->id,
                'company_name' => $data->company_name,
                'manager_name' => $data->manager_name,
            ];
            return $company;
    }
    public function fetchDataSearched($get, $order = null)
    {
        $invoice = new Invoice;//manager_name紐づけなければいけない
        if ($order === 'DESC') {
            $datas = $invoice
            ->orderBy('id', 'desc')
            ->where('deleted', null)
            ->where('company_id', $get['id'])
            ->where('status', $get['search'])
            ->paginate(10)
            ;
        } else {
            $datas = $invoice
            ->where('deleted', null)
            ->where('company_id', $get['id'])
            ->where('status', $get['search'])
            ->paginate(10)
            ;
        }
        // $datas['manager_name'] = Invoice::find('id')->company->manager_name;
        return $datas;
    }
    public function fetchData($id, $order = null)
    {
        $invoice = new Invoice;
        if ($order === 'DESC') {
            $datas = $invoice
            ->orderBy('id', 'desc')
            ->where('deleted', null)
            ->where('company_id', $id)
            ->paginate(10)
            ;
        } else {
            $datas = $invoice
            ->where('deleted', null)
            ->where('company_id', $id)
            ->paginate(10)
            ;
        }

        return $datas;
    }
}
