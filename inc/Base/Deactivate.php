<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc\Base;

class Deactivate{
    public static function deactivate(){
        flush_rewrite_rules();

    }
}