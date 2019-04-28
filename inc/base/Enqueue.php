<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc\Base;

class Enqueue{
    function register(){
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
    }

     function enqueue(){
        wp_enqueue_style('uffmobilestyle', plugins_url('/assets/style.css', UFF_MOBILE_APP_MENUS_FILE));
        wp_enqueue_script('uffmobilescript', plugins_url('/assets/script.css', UFF_MOBILE_APP_MENUS_FILE));
    }
}