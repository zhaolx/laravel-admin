<?php

namespace App\Api\Model;


use Illuminate\Database\Eloquent\Model;

class Permission extends Model{

    protected $table = 'ims_permission_list';

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

    public  function setPermissionData($role_id,$data){

    	if(!intval($role_id)||!is_array($data)||empty($data)){
    		return false;
    	}
    	$exsit_ids = [];
    	$res = $this->where(['type'=>1,'data_id'=>$role_id,'status'=>1])->select('menu_id')->get()->toArray();
    	foreach ($res as $item) {
    		$exsit_ids[] = $item['menu_id'];
    	}
    	
    	foreach ($data as $menu_id) {
    		$index = array_search($menu_id, $exsit_ids);
    		if(!isset($exsit_ids[$index])){
    			$insert = [
    					   'type'=>1,
    					   'data_id'=>$role_id,
    					   'menu_id'=>$menu_id,
    					   'status'=>1,
    					   'in_time'=>date('Y-m-d H:i:s'),
    					 ];
    			$this->insertGetId($insert);		 
    		}else{
    			unset($exsit_ids[$index]);
    		}
    	}
    	
    	if(!empty($exsit_ids)){
    		$this->where(['type'=>1,'data_id'=>$role_id,'status'=>1])->whereIn('menu_id',$exsit_ids)->update(['status'=>-1]);
    	}
    	return true;
    }
}