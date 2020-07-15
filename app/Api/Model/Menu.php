<?php

namespace App\Api\Model;


use Illuminate\Database\Eloquent\Model;

class Menu extends Model{

    protected $table = 'ims_menus';

    public $timestamps = false;


    public static $instance;

    public static function getInstance(){

        if(!self::$instance) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    protected function getAllMeun(){

       
    }
}