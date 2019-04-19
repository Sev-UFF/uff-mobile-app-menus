<?php
/**
 * Plugin Name: UFF Mobile Api Menus
 * Plugin URI:  https://#
 * Description: Configurações para os menus do aplicativo da UFF.
 *
 * Version:     1.3.1
 *
 * Author:      Thiago Augusto
 * Author URI:  https://#
 *
 * Text Domain: uff menus
 *
 * @package UFF_MOBILE_APP_MENUS
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'UFF_MOBILE_APP_MENUS_VERSION', '0.0.1' );

if ( ! defined( 'UFF_MOBILE_APP_MENUS_FILE' ) ) {
	define( 'UFF_MOBILE_APP_MENUS_FILE', __FILE__ );
	define( 'UFF_MOBILE_APP_MENUS_DIR', dirname( __FILE__ ) );
}


$autoload = UFF_MOBILE_APP_MENUS_DIR . '/vendor/autoload.php';
if (file_exists( autoload )){
	require_once $autoload;
}

use Inc\Activate;

if (!class_exists('UFFMobileAppMenusPlugin')){

	class UFFMobileAppMenusPlugin{


		public $plugin;

		function __construct(){
			$this->$plugin = plugin_basename(UFF_MOBILE_APP_MENUS_FILE);
		}


		static function uninstall(){
			//deletando posts como exemplo
			global $wpdb;
			$wpdb->query("delete from wp_posts where post_type = 'book'");
			$wpdb->query("delete from wp_postmeta where post_id not in (select id from wp_posts)");
			$wpdb->query("delete from wp_term_relationships where object not in (select id from wp_posts)");
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

//uninstall
register_uninstall_hook(UFF_MOBILE_APP_MENUS_FILE, array($UFFPlugin, 'uninstall'));



//require_once( UFF_MOBILE_APP_MENUS_DIR . '/inc/qtx_class_translator.php' );
//
//if ( is_admin() ) {
//	require_once( UFF_MOBILE_APP_MENUS_DIR . '/admin/qtx_activation_hook.php' );
//	qtranxf_register_activation_hooks();
//}
