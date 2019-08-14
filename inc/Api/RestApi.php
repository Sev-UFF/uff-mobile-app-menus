<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc\Api;

use Inc\Base\Menus;
use DOMWrap\Document;

class RestApi
{

    public static function get_plugin_namespace() {
        return 'uff-mobile-menus/v2';
    }

    public function register(){
        add_action( 'init',  array( $this, 'rest_init' ) );
    }

    public function rest_init(){
        add_filter( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    public function register_routes() {

        register_rest_route( self::get_plugin_namespace(), '/menu/(?P<lang>\S+)', array(
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_menu' ),
                'args'     => array(
                    'context' => array(
                    'default' => 'view',
                    ),
                ),
            )
        ) );

        register_rest_route( self::get_plugin_namespace(), '/page/(?P<id>\d+)', array(
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_page' ),
                'args'     => array(
                    'context' => array(
                    'default' => 'view',
                    ),
                ),
            )
        ) );
    }

    public function get_page( $request ) {
        $id = $request['id'];
        $url = get_page_link($id);
        $response = wp_remote_get( $url, array(
            'timeout'     => 120,
        ) );
        $body = $response['body'];
        $doc = new \DOMDocument();
        @$doc->loadHTML($body);
        $main_content = $doc->getElementById('main-content');

        $parsed_content = ($doc->saveHTML($main_content));

        $page = (array) get_post($id);

        $page['post_content'] = $parsed_content;

        return $page;
    }

    public function get_menu( $request ) {

        $current_language =  $request['lang'];

        $default_language = pll_default_language();

        $all_languages = icl_get_languages();

        if (!array_key_exists($current_language,  $all_languages)){
            $current_language = $default_language;
        }

        $menu_id = get_option("language_$current_language");

        if ($menu_id == "-1") $menu_id =  get_option("language_$default_language");

        return (new Menus())->get_menu($menu_id, $current_language);
    }
}