<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc\Pages;

class Admin{
    function register(){
        add_action('admin_menu', array($this,'add_admin_pages'));
    }

    function add_admin_pages(){
        add_menu_page('UFF Mobile App Menus', 'UFF Mobile App Menus', 'manage_options', 'uff_mobile_app_menus_plugin', array($this, 'admin_index'), 'dashicons-smartphone', 110);
    }

    function admin_index(){
        require_once plugin_dir_path (UFF_MOBILE_APP_MENUS_FILE) . 'templates/admin.php';
    }
}