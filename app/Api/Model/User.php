<?php

namespace App\Api\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model{

    protected $table = 'ims_user';

    public static $instance;

    public $timestamps = true;

	const CREATED_AT = 'in_time';
	const UPDATED_AT = 'update_time';
	
    public static function getInstance(){
        if(!self::$instance) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    //获取用户权限
    public function getPermission($role_id){

       $res = DB::table('ims_permission_list as pl')->leftJoin('ims_menus as m','m.id','=','pl.menu_id')->where(['pl.type'=>1,'pl.data_id'=>$role_id,'m.status'=>1,'pl.status'=>1])->select('m.id','m.route')->get();
       $permissions = [];
       foreach ($res as $item) {
          if($item->route){
            $permissions[] = $item->route;
          }
       }
       return $permissions;
    }
    //获取用户可见菜单
    public function getMenu($role_id){

        //菜单列表
        if($role_id==-1){
            //管理员
            $res = DB::table('ims_menus')->where(['status'=>1,'type'=>1])->get();
        }else {
           $res = DB::table('ims_permission_list as pl')->leftJoin('ims_menus as m','m.id','=','pl.menu_id')->where(['pl.type'=>1,'pl.data_id'=>$role_id,'m.status'=>1,'pl.status'=>1,'m.type'=>1])->select('m.*')->get();
        }
       
        //按钮列表
        $button_list = DB::table('ims_menus')->where('parent_id','>',0)->where(['type'=>2,'is_show'=>1,'status'=>1])->select('id','name','title','parent_id')->get();
        $menus = [];
        $res = objectToArray($res);
        $button_list = objectToArray($button_list);
        foreach ($res as $k => $item) {
           $temp = [];
           $temp['id'] = $item['id'];
           $temp['path'] = $item['path'];
           $temp['name'] = $item['name'];
           $temp['component'] = $item['component'];
           $temp['hidden'] = $item['is_show']?false:true;
           $temp['alwaysShow'] = $item['parent_id']==0&&$item['is_show']?true:false;
           $temp['meta'] = [
                            'title'=>$item['title'],
                            'icon'=>$item['icon'],
                            'noCache'=>$item['is_cache']?false:true,
                          ];
           $temp['parent_id'] = $item['parent_id'];
           foreach ($button_list as $k2 => $button) {
                if($button['parent_id']==$item['id']){
                    $temp['buttons'][] = $button;
                }
            }
           $menus[$item['id']] = $temp;
        }

        return listToTreeMulti($menus);
    }
}