<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc\Base;

class Activate{
    public static function activate(){
        //create database
        flush_rewrite_rules();

    }
}