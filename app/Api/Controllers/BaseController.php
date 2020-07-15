<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller{

	protected $request;
	protected $loginUser;
	protected $user_id;
	public function __construct(Request $request){

	 	$this->request = $request;
	 	$session_user = $request->session()->get('USERINFO');
	 	if($session_user&&$session_user['uid']){
	 		$this->loginUser = $session_user;
	 		$this->user_id = $session_user['uid'];
	 	}
	 }
    //返回json数据
    public function result($code=0, $msg = 'error', $data = [], $httpStatus = 200){

        $result = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        http_response_code($httpStatus);
        return response()->json($result);
        exit;
    }

    protected  function generateRandomString($length = 32){

        if (!extension_loaded('openssl')) {
            throw new \Exception('请开启 PHP OpenSSL');
        }
        
        $bytes = openssl_random_pseudo_bytes($length);
        return strtr(substr(base64_encode($bytes), 0, $length), '+/=', '_-.');
       
    }
}