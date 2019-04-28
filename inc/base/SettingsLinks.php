<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc\Base;

class SettingsLinks{
    function register(){
        add_filter("plugin_action_links_" . plugin_basename(UFF_MOBILE_APP_MENUS_DIR) . '/' . plugin_basename(UFF_MOBILE_APP_MENUS_DIR) . '.php' , array($this, 'settings_link'));
    }

    function settings_link($links){
        $settings_link = '<a href="admin.php?page=uff_mobile_app_menus_plugin">Configurar</a>';
        array_push($links, $settings_link);

        return $links;
    }
}