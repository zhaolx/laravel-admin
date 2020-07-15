<?php
namespace App\Api\Controllers;

use App\Api\Controllers\BaseController as Controller;
use App\Api\Model\{Menu,Roles,Permission,User};

class SystemController extends Controller{

	//菜单列表
	public function menuList(){

		$is_show = intval($this->request->input('is_show'));
		$status = intval($this->request->input('status'));
		$page = intval($this->request->input('page'));
		$pageSize = intval($this->request->input('pageSize'));
		$page = max(1,$page);
		$pageSize = formatPageSize($pageSize);

		if($status<0){
			$status = 0;
		}
		$query = Menu::where('status','>=',$status)->where('type',1);
		if($is_show==1){
			$query->where('is_show',1);
		}
		$query_1 = clone $query;
		$menu_list = $query->offset(($page-1)*$pageSize)->limit($pageSize)->get()->toArray();
		$button_list = Menu::where('status','>=',$status)->where('parent_id','>',0)->where('type',2)->select('id','name','title','parent_id')->get()->toArray();
		foreach ($menu_list as $k => $menu) {
			foreach ($button_list as $k2 => $button) {
				if($button['parent_id']==$menu['id']){
					$menu_list[$k]['button'][] = $button;
				}
			}
		}
		$total = $query_1->count();
		return $this->result(1,'success',['list'=>$menu_list,'total'=>intval($total)]);
	}
	//添加菜单/按钮
	public function addMenu(){

		$name = trim($this->request->input('name'));
		$title = $this->request->input('title');
		$route = $this->request->input('route');
		$path = $this->request->input('path');
		$icon = $this->request->input('icon');
		$component = $this->request->input('component');
		$parent_id = intval($this->request->input('parent_id'));
		$is_show = intval($this->request->input('is_show'));
		$is_cache = intval($this->request->input('is_cache'));
		$status = intval($this->request->input('status'));
		$type = intval($this->request->input('type'));
		$sort = intval($this->request->input('sort'));
		

		if(!$name||!$title||!$component){
			return $this->result(0,'参数错误');
		}
		if($type==2&&!$parent_id){
			return $this->result(0,'参数错误');
		}
		$insert = [
					'name'=>$name,
					'title'=>$title,
					'route'=>$route,
					'path'=>$path,
					'icon'=>$icon,
					'component'=>$component,
					'parent_id'=>$parent_id,
					'is_show'=>$is_show,
					'is_cache'=>$is_cache,
					'sort'=>$sort,
					'type'=>$type,
					'status'=>1,
					'in_time'=>date("Y-m-d H:i:s"),
				 ];
		$res = Menu::insertGetId($insert);
		if($res){
			return $this->result(1,'success');
		}else{
			return $this->result(0,'新增失败');
		}
	}
	//编辑菜单/按钮
	public function editMenu(){

		$id = intval($this->request->input('id'));
		if(!$id){
			return $this->result(0,'参数错误');
		}
		$name = trim($this->request->input('name'));
		$title = $this->request->input('title');
		$route = $this->request->input('route');
		$path = $this->request->input('path');
		$icon = $this->request->input('icon');
		$component = $this->request->input('component');
		$parent_id = intval($this->request->input('parent_id'));
		$is_show = intval($this->request->input('is_show'));
		$is_cache = intval($this->request->input('is_cache'));
		$status = intval($this->request->input('status'));
		$sort = intval($this->request->input('sort'));

		$menu = Menu::where('status','>=',0)->find($id);
		
		if($menu){
			if($title!=$menu->title){
				$menu->title = $title;
			}
			if($name!=$menu->name){
				$menu->name = $name;
			}
			if($route!=$menu->route){
				$menu->route = $route;
			}
			if($path!=$menu->path){
				$menu->path = $path;
			}
			if($icon!=$menu->icon){
				$menu->icon = $icon;
			}
			if($component!=$menu->component){
				$menu->component = $component;
			}
			if($parent_id!=$menu->parent_id){
				$menu->parent_id = $parent_id;
			}
			if($is_show!=$menu->is_show){
				$menu->is_show = $is_show;
			}
			if($is_cache!=$menu->is_cache){
				$menu->is_cache = $is_cache;
			}
			if($status!=$menu->status){
				$menu->status = $status;
			}
			if($sort!=$menu->sort){
				$menu->sort = $sort;
			}
			if($menu->save()){
				return $this->result(1,'success');
			}
		}
		return $this->result(0,'修改失败');
	}
	//获取菜单/按钮详情
	public function getMenu(){

		$id = intval($this->request->input('id'));
		if(!$id){
			return $this->result(0,'参数错误');
		}
		$item = Menu::find($id);
		return $this->result(1,'success',['detail'=>$item]);
	}
	//删除菜单/按钮
	public function delMenu(){

		$id = intval($this->request->input('id'));
		$ids_arr = $this->request->input('ids_arr');

		if(!$id&&empty($ids_arr)){
			return $this->result(0,'参数错误');
		}
		if(is_array($ids_arr)&&!empty($ids_arr)){
			$res = Menu::whereIn('id',$ids_arr)->update(['status'=>-1]);
		}else{
			$res = Menu::where('id',$id)->update(['status'=>-1]);
		}
		
		if($res){
			return $this->result(1,'success');
		}else{
			return $this->result(0,'删除失败');
		}
	}
	//获取角色列表
	public function roleList(){

		$title = $this->request->input('title');
		$is_more = intval($this->request->input('is_more'));
		$status = intval($this->request->input('status'));
		$page = intval($this->request->input('page'));
		$pageSize = intval($this->request->input('pageSize'));

		$page = max(1,$page);
		$pageSize = formatPageSize($pageSize);

		$query = Roles::where('status','>=',0);
		if($title){
			$query->where('name','like','%'.$title.'%');
		}
		if($status>=0){
			$query->where('status',$status);
		}
		$query_1 = clone $query;

		$list = $query->offset(($page-1)*$pageSize)->limit($pageSize)->get()->toArray();
		$total = $query_1->count();
		if($is_more==1){
			foreach ($list as $k => $item) {
				$list[$k]['count'] = User::where(['role_id'=>$item['id']])->where('status','>=',0)->count();
			}
		}
		return $this->result(1,'success',['list'=>$list,'total'=>$total]);
	}
	//添加角色
	public function addRoles(){

		$name = $this->request->input('name');
		$description = $this->request->input('description');
		$status = intval($this->request->input('status'));
		if(empty($name)){
			return $this->result(0,'参数错误');
		}
		$insert = [
					'name'=>$name,
					'description'=>$description,
					'status'=>$status,
					'in_time'=>date('Y-m-d H:i:s'),
				 ];
		$res = Roles::insertGetId($insert);
		if($res){
			return $this->result(1,'success',['id'=>$res]);
		}else{
			return $this->result(0,'新增失败');
		}
	}
	//获取角色详情
	public function getRole(){

		$id = intval($this->request->input('id'));
		if(!$id){
			return $this->result(0,'参数错误');
		}
		$item = Roles::find($id);
		return $this->result(1,'success',['detail'=>$item]);
	}
	//编辑菜单/按钮
	public function editRole(){

		$id = intval($this->request->input('id'));
		if(!$id){
			return $this->result(0,'参数错误');
		}
		$name = trim($this->request->input('name'));
		$description = $this->request->input('description');
		$status = intval($this->request->input('status'));

		$role = Roles::where('status','>=',0)->find($id);
		
		if($role){
			if($name!=$role->name){
				$role->name = $name;
			}
			if($description!=$role->description){
				$role->description = $description;
			}
			if($status!=$role->status){
				$role->status = $status;
			}
			if($role->save()){
				return $this->result(1,'success');
			}
		}
		return $this->result(0,'修改失败');
	}
	//删除角色
	public function deleteRole(){

		$id = intval($this->request->input('id'));
		$ids_arr = $this->request->input('ids_arr');

		if(!$id&&empty($ids_arr)){
			return $this->result(0,'参数错误');
		}
		if(is_array($ids_arr)&&!empty($ids_arr)){
			$res = Roles::whereIn('id',$ids_arr)->update(['status'=>-1]);
		}else{
			$res = Roles::where('id',$id)->update(['status'=>-1]);
		}
		
		if($res){
			return $this->result(1,'success');
		}else{
			return $this->result(0,'删除失败');
		}
	}
	//获取角色权限
	public function getRolePermission(){

		$id = intval($this->request->input('id'));
		if(!$id){
			return $this->result(0,'参数错误');
		}
		$role = Roles::where('status','>=',0)->find($id);
		if(!$role){
			return $this->result(0,'ID错误');
		}
		$list = Permission::where(['type'=>1,'data_id'=>$id,'status'=>1])->select('id','menu_id')->get()->toArray();
		return $this->result(1,'success',['list'=>$list,'is_admin'=>$role->is_admin]);
	}
	//保存角色权限
	public function savePermission(){

		$id = intval($this->request->input('id'));
		$is_admin = intval($this->request->input('is_admin'));
		$p_list = $this->request->input('p_list');
		if($is_admin!=1&&empty($p_list)){
			return $this->result(0,'参数错误');
		}
		$res = Roles::where('id',$id)->update(['is_admin'=>$is_admin]);
		if($is_admin==1){
			Permission::where(['type'=>1,'data_id'=>$id,'status'=>1])->update(['status'=>-1]);
		}elseif(!empty($p_list)){
			Permission::getInstance()->setPermissionData($id,$p_list);
		}
		return $this->result(1,'success');
	}
	//获取用户列表
	public function userList(){

		$keyword = $this->request->input('keyword');
		$status = intval($this->request->input('status'));
		$page = intval($this->request->input('page'));
		$pageSize = intval($this->request->input('pageSize'));

		$page = max(1,$page);
		$status = max(0,$status);
		$pageSize = formatPageSize($pageSize);

		$query = User::where('status','>=',$status);
		if($keyword){
			$query->where(function($query) use($keyword){
				$query->where('account','like','%'.$keyword.'%')->orWhere('nickname','like','%'.$keyword.'%');
			});
		}
		$query_1 = clone $query;

		$list = $query->select('id','account','nickname','avatar','role_id','status','in_time')->offset(($page-1)*$pageSize)->limit($pageSize)->get()->toArray();
		$total = $query_1->count();
		$role_list = Roles::getInstance()->getAll();
		foreach ($list as $k => $item) {
			if(isset($role_list[$item['role_id']])){
				$list[$k]['role_name'] = $role_list[$item['role_id']];
			}else{
				$list[$k]['role_name'] = '未分配';
			}
		}
		return $this->result(1,'success',['list'=>$list,'total'=>$total]);
	}
	//添加用户
	public function addUser(){

		$account = trim($this->request->input('account'));
		$role_id = intval($this->request->input('role_id'));
		$status = intval($this->request->input('status'));
		$password = $this->request->input('password');
		$avatar = $this->request->input('avatar');
		$nickname = $this->request->input('nickname');

		if(!$account||!$role_id){
			return $this->result(0,'参数错误');
		}
		$exist = User::where('account',$account)->where('status','>',-1)->exists();
		//var_dump($user);
		if($exist){
			return $this->result(0,'该账号已存在');
		}
		if(!$password){
			//默认为123456
			$password = '123456';
		}
		$insert = [
			       'account'=>$account,
			       'role_id'=>$role_id,
			       'password'=>password_hash($password,PASSWORD_DEFAULT),
			       'status'=>$status,
			       'avatar'=>$avatar,
			       'nickname'=>$nickname,
			       'in_time'=>date('Y-m-d H:i:s')
				  ];
		$res = User::insertGetId($insert);
		if($res){
			return $this->result(1,'success',['id'=>$res]);
		}else{
			return $this->result(0,'新增失败');
		}
	}
	//获取用户信息
	public function getUser(){

		$id = intval($this->request->input('id'));
		if(!$id){
			return $this->result(0,'参数错误');
		}
		$user = User::where('status','>=',0)->select('id','account','nickname','avatar','role_id','status','in_time')->find($id);
		
		return $this->result(1,'success',['detail'=>$user]);
	}
		//编辑菜单/按钮
	public function editUser(){

		$id = intval($this->request->input('id'));
		if(!$id){
			return $this->result(0,'参数错误');
		}
		//$account = trim($this->request->input('account'));
		$role_id = intval($this->request->input('role_id'));
		$status = intval($this->request->input('status'));
		$password = $this->request->input('password');
		$avatar = $this->request->input('avatar');
		$nickname = $this->request->input('nickname');

		$user = User::where('status','>=',0)->find($id);
		
		if($user){
			if($role_id!=$user->role_id){
				$user->role_id = $role_id;
			}
			if($status!=$user->status){
				$user->status = $status;
			}
			if($nickname!=$user->nickname){
				$user->nickname = $nickname;
			}
			if($avatar!=$user->avatar){
				$user->avatar = $avatar;
			}
			if($password){
				$user->password = password_hash($password,PASSWORD_DEFAULT);
			}
			if($user->save()){
				return $this->result(1,'success');
			}
		}
		return $this->result(0,'修改失败');
	}
	//删除用户
	public function deleteUser(){

		$id = intval($this->request->input('id'));
		$ids_arr = $this->request->input('ids_arr');

		if(!$id&&empty($ids_arr)){
			return $this->result(0,'参数错误');
		}
		if(is_array($ids_arr)&&!empty($ids_arr)){
			$res = User::whereIn('id',$ids_arr)->update(['status'=>-1]);
		}else{
			$res = User::where('id',$id)->update(['status'=>-1]);
		}
		
		if($res){
			return $this->result(1,'success');
		}else{
			return $this->result(0,'删除失败');
		}
	}
}