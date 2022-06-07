<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
 
class TestController extends Controller
{
    public function func()
    {
        $company = new Company;
        $datas = $company
            ->offset(1)//スタート位置
            ->limit(10)
            ->where('deleted', null)
            ->get();
        $prefecture = config('config.PREFECTURES');
        return view('sample', compact('datas', 'prefecture'));
    }
    // public function getConfigPrefecture()
    // {
    //     return redirect(config('config.PREFECTURES'));
    // }
}
