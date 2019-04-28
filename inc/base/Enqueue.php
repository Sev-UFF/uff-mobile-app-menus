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
        wp_enqueue_style('uffmobilestyle',  plugin_dir_url (UFF_MOBILE_APP_MENUS_FILE)  . 'assets/uff-mobile-style.css');
        wp_enqueue_script('uffmobilescript', plugin_dir_url (UFF_MOBILE_APP_MENUS_FILE) . 'assets/uff-mobile-script.js');
    }
}