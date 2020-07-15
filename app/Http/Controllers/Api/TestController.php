<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller{

	public function index(){
        return response()->json(['errorCode'=>100002,'data'=>'无该活动地点']);
    }
}