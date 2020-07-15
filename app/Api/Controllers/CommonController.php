<?php
namespace App\Api\Controllers;

use App\Api\Controllers\BaseController as Controller;
use Illuminate\Support\Facades\Storage;


class CommonController extends Controller{

	public function uploadImg(){

		if($this->request->hasFile('img')&&$this->request->file('img')->isValid()) {
		    $path = $this->request->file('img')->store(
					    'images/', 'public'
					);
		}
		if(isset($path)&&$path){
			$url = 'http://laravel.admin.cc'.Storage::url($path);
			return $this->result(1,'success',['url'=>$url]);
		}
		return $this->result(0,'上传失败');
	}
}