<?php

namespace App\Api\Model;


use Illuminate\Database\Eloquent\Model;

class Roles extends Model{

    protected $table = 'ims_role_list';

    public $timestamps = true;

	const CREATED_AT = 'in_time';
	const UPDATED_AT = 'update_time';

    public static $instance;

    public static function getInstance(){
        if(!self::$instance) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function getAll(){

    	$res = $this->where('status',1)->select('id','name')->get();
    	$array = [];
    	foreach ($res as $role) {
    		$array[$role['id']] = $role['name'];
    	}
    	return $array;
    }
}