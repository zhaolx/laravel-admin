<?php
namespace App\Api\Controllers;

use App\Api\Controllers\BaseController as Controller;

class TestController extends Controller{

	public function index(){
		
		return $this->result(1,'请求成功');
	}
}
?>