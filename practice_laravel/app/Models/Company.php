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
            'manager_name' => $value['name'],
            'phone_number' => $value['phone'],
            'postal_code' => $value['postal'],
            'prefecture_code' => $value['prefecture_code'],
            'address' => $value['address'],
            'mail_address' => $value['email'],
            'prefix' => $value['prefix'],
            'created' => NOW(),
            'modified' => NOW()
        ]);
        $statement = $this->db->prepare('INSERT INTO companies SET company_name=?, manager_name=?,phone_number=?,postal_code=?,prefecture_code=?,address=?,mail_address=?,prefix=?,created=NOW(),modified=NOW()');
        $statement->bindParam(1, $value['name'], \PDO::PARAM_STR);
        $statement->bindParam(2, $value['name'], \PDO::PARAM_STR);
        $statement->bindParam(3, $value['phone'], \PDO::PARAM_STR);
        $statement->bindParam(4, $value['postal'], \PDO::PARAM_STR);
        $statement->bindParam(5, $value['prefecture_code'], \PDO::PARAM_STR);
        $statement->bindParam(6, $value['address'], \PDO::PARAM_STR);
        $statement->bindParam(7, $value['email'], \PDO::PARAM_STR);
        $statement->bindParam(8, $value['prefix'], \PDO::PARAM_STR);
        $statement->execute();
    }
}
