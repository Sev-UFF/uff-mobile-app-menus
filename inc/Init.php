<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc;

final class Init{

    static function get_services(){
        return [
            Pages\Admin::class,
            Base\Enqueue::class,
			Base\SettingsLinks::class,
			// Api\RestApi::class,
        ];
    }

    static function register_services(){
        foreach (self::get_services() as $class){
            $service = self::instatiate($class);
            if (method_exists($service, 'register')){
                $service->register();
            }
        }
    }

    private static function instatiate($class){
        return new $class();
    }
}


/*

use Inc\Activate;

if (!class_exists('UFFMobileAppMenusPlugin')){

	class UFFMobileAppMenusPlugin{


		public $plugin;

		function __construct(){
			$this->$plugin = plugin_basename(UFF_MOBILE_APP_MENUS_FILE);
		}



		function activate(){
			Activate::activate();
		}

		function deactivate(){
			Deactivate::deactivate();
		}


		function register(){
			//add_action('init', array($this, 'create_database'));
			add_action('admin_enqueue_scripts', array($this, 'enqueue'));
			add_action('admin_menu', array($this,'add_admin_pages'));
			add_filter("plugin_action_links_$this->$plugin", array($this, 'settings_link'));
		}

		function settings_link($links){
			$settings_link = '<a href="admin.php?page=uff_mobile_app_menus_plugin">Configurar</a>';
			array_push($links, $settings_link);

			return $links;
		}

		function enqueue(){
			wp_enqueue_style('uffmobilestyle', plugins_url('/assets/style.css', UFF_MOBILE_APP_MENUS_FILE));
			wp_enqueue_script('uffmobilescript', plugins_url('/assets/script.css', UFF_MOBILE_APP_MENUS_FILE));
		}

		function add_admin_pages(){
			add_menu_page('UFF Mobile App Menus', 'UFF Mobile App Menus', 'manage_options', 'uff_mobile_app_menus_plugin', array($this, 'admin_index'), 'dashicons-smartphone', 110);
		}

		function admin_index(){
			require_once plugin_dir_path (UFF_MOBILE_APP_MENUS_FILE, 'templates/admin.php');
		}
		

		function create_database(){
	//no tutorial foi criado um post type
	//aqui eu quero fazer meu plugin provalmente criar uma nova tabela
		}
	}
}

$UFFPlugin = new UFFMobileAppMenusPlugin();
$UFFPlugin->register();

//activation
register_activation_hook(UFF_MOBILE_APP_MENUS_FILE, array($UFFPlugin, 'activate'));

//deactivation
register_deactivation_hook(UFF_MOBILE_APP_MENUS_FILE, array($UFFPlugin, 'deactivate'));


*/