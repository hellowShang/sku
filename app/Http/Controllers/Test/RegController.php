<?php

namespace App\Http\Controllers\Test;

use App\Jobs\DateJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegController extends Controller
{
    // 用户注册
    // 页面
    public function index(){
        return view("test.reg");
    }

    // 逻辑处理
    public function reg(){
            $data = request()->all();
            DateJob::dispatch($data['email'])->onQueue('reg_email')->delay(now()->addMinutes(1));
            echo "<pre>";print_r($data);echo "</pre>";

    }
}
