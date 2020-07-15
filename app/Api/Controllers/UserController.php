<?php
namespace App\Api\Controllers;

use App\Api\Controllers\BaseController as Controller;
use App\Api\Model\{Roles,User};

class UserController extends Controller{



	//用户登录
	public function login(){

		$account = trim($this->request->input('account'));
		$passwd = $this->request->input('passwd');

		if(!$account||!$passwd){
			return $this->result(0,'参数错误');
		}

		if($account=='admin'&&$passwd=='123456'){
			$this->request->session()->put('USERINFO',['uid'=>1,'account'=>'admin','is_admin'=>1]);
			return $this->result(1,'success',['userToken'=>$this->generateRandomString(45)]);
		}else{
			$user = User::where('account',$account)->where('status','>=',0)->first();
			if($user){
				if(!$user->status){
					return $this->result(0,'账号被锁定');
				}
				if(!password_verify($passwd,$user->password)){
					return $this->result(0,'账号和密码不匹配');
				}
				$user_info = ['uid'=>$user->id,'account'=>$user->account,'is_admin'=>0,'role_id'=>$user->role_id,'avatar'=>$user->avatar,'nickname'=>$user->nickname,'login_time'=>time()];
				if($user->role_id){
					$permissions = User::getInstance()->getPermission($user->role_id);
					$role = Roles::where('status',1)->find($user->role_id);
					if($role){
						$user_info['is_admin'] = $role->is_admin;
					}
					//$user_info['permissions'] = $permissions;
				}
				$this->request->session()->put('USERINFO',$user_info);
				$this->request->session()->put('PERMISSIONS',$permissions);
				return $this->result(1,'success',['userToken'=>$this->generateRandomString(45),'user_info'=>$user_info]);
			}
			return $this->result(0,'账号不存在');
			
		}
	}

	//获取用户信息
	public function getUser(){

		if($this->loginUser['is_admin']==1){
			$access = User::getInstance()->getMenu(-1);
		}else{
			$access = User::getInstance()->getMenu((int)$this->loginUser['role_id']);
		}
		
		$admin_menu = [
					 "path"=> "/manage",
					 "component"=> "layout/Layout",
					 "name"=> "manage",
					 "hidden"=> false,
					 "alwaysShow"=> true,
					 "meta"=> [
							"title"=> "系统管理",
							"icon"=> "system",
							"noCache"=> false
						 ],	
					"children"=> [
						[
							"path"=> "user",
							"component"=> "manage/admin",
							"name"=> "Admin",
							"hidden"=> false,
							"alwaysShow"=> false,
							"meta"=> [
								"title"=> "用户管理",
								"icon"=> "",
								"noCache"=> false
							],
					
						],
						[
							"path"=> "roles",
							"component"=> "manage/roles",
							"name"=> "role_list",
							"hidden"=> false,
							"alwaysShow"=> false,
							"meta"=> [
								"title"=> "角色管理",
								"icon"=> "",
								"noCache"=> false
							],
					
						],
						[
							"path"=> "rules",
							"component"=> "manage/rules",
							"name"=> "manage/rules",
							"hidden"=> false,
							"alwaysShow"=> false,
							"meta"=> [
									"title"=> "菜单管理",
									"icon"=> "",
									"noCache"=> false
								]
						]
					]
				
				];
		if($this->loginUser['is_admin']==1){
			$access[] = $admin_menu;
		}
		$session_user = session('USERINFO');
		return $this->result(1,'success',['access'=>$access,'info'=>$session_user]);	
	}
}