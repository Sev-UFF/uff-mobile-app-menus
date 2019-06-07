<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc\Pages;


use Inc\Api\SettingsApi;
use Inc\Base\Menus;
use Inc\Api\Callbacks\AdminCallbacks;


class Admin
{
	public $settings;
	public $callbacks;
	public $pages = array();
	public $subpages = array();

	public $languages = array();
	public $default_language;

	public $menus_and_languages = array();


	public function register() 
	{
		add_action('plugins_loaded', array( $this,'plugins_loaded'));
	}

	public function plugins_loaded(){

		add_action( 'wp_loaded',array( $this,'lazy_register') );
	}

	public function lazy_register(){

		$this->default_language = pll_default_language();

		$default_menu_id = get_option("language_$this->default_language");
		$all_menus = new Menus();

		foreach( icl_get_languages() as $language){
			$option = array();

			$code = $language['language_code'];

			$menu_id = get_option("language_$code");
			$menu = $all_menus->get_menu($menu_id);

			$option["language"] = $language;
			$option["menu"] = $menu;

			if ($code == $this->default_language){
				array_unshift($this->menus_and_languages, $option);
			}else{
				array_push(
					$this->menus_and_languages,
					 $option
					);
				}
		}
		

		$this->settings = new SettingsApi();
		$this->callbacks = new AdminCallbacks();
		$this->setPages();
		// $this->setSubpages();
		$this->setSettings();
		$this->setSections();
		$this->setFields();
		$this->settings->addPages( $this->pages )->register();
	}

	public function setPages() 
	{
		$this->pages = array(
			array(
				'page_title' => 'UFF Mobile Menus', 
				'menu_title' => 'UFF Mobile Menus', 
				'capability' => 'manage_options', 
				'menu_slug' => 'uff_mobile_menus', 
				'callback' => array( $this->callbacks, 'adminMenus' ), 
				'icon_url' => 'dashicons-smartphone', 
				'position' => 110
			)
		);
	}

	public function setSettings()
	{
		$args = array(
			
		);


		foreach( $this->menus_and_languages as $option){
			$language = $option["language"];
			$menu = $option["menu"];

			$code = $language['language_code'];

			//adicionando idioma às opções
			$new_item = array(
				'option_group' => 'uff_mobile_menus_options_group',
				'option_name' => "language_$code",
			);
			array_push(
				$args,
				 $new_item
				);

			//adicionando menu às opções
			if (array_key_exists('items', $menu)){
				$menu_request = array(
					'items' => $menu['items'],
					'language' => $code,
					'parent_id' => ''
				);
		
				$menu_args = $this->setMenuSettings($menu_request);
		
				$args = array_merge($args, $menu_args);
			}
			
		}


		$this->settings->setSettings( $args );
	}

	private function setMenuSettings($request){
		$args = array(
			
		);

		$parent_name = $request['parent_id'];
		$language_code = $request['language'];

		foreach($request['items'] as $menu_item){
			$current_id = $menu_item['id'];
			$current_name = "$parent_name$current_id";
			$new_item = array(
				'option_group' => 'uff_mobile_menu_items_group',
				'option_name' => "menu_$language_code" . "_$current_name",
				'callback' => array( $this->callbacks, 'checkboxSanitize' )
			);
			array_push(
				$args,
				 $new_item
				);


			if ( array_key_exists('children', $menu_item)){
				$menu_request = array(
					'items'=>$menu_item['children'],
					'language' => $language_code,
					'parent_id'=> $current_name . '_'
				);
				$children = $this->setMenuSettings($menu_request);
				$args = array_merge($args, $children);
			}
		}

		return $args;
	}

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'uff_mobile_menus_section',
				'title' => 'Selecione os menus de cada idioma',
				'page' => 'uff_mobile_menus_section'
			),
			array(
				'id' => 'uff_mobile_menu_items_section',
				'title' => 'Escolha quais itens do menu serão exibidos no aplicativo para cada idioma',
				'page' => 'uff_mobile_menu_items_section'
			)
		);
		$this->settings->setSections( $args );
	}
	public function setFields()
	{
		$args = array(
			
		);

		foreach( $this->menus_and_languages as $option){
			$language = $option["language"];
			$menu = $option["menu"];

			$language_code = $language['language_code'];
			$name = $language['native_name'];

			$defalt = $language_code == $this->default_language ? "(Idioma Default)" : "";

			array_push(
				$args,
				array(
					'id' => "language_$language_code",
					'title' => "Menu $name $defalt",
					'callback' => array( $this->callbacks, 'selectMenus' ),
					'page' => 'uff_mobile_menus_section',
					'section' => 'uff_mobile_menus_section',
					'args' => array(
						'label_for' => "language_$language_code"
					)
				)
				);
			if (array_key_exists('items', $menu)){
				$menu_request = array(
					'items' => $menu['items'],
					'depth' => '0',
					'language' => $language_code,
					'flag' => $language["country_flag_url"],
					'parent_id' => ''
				);
		
				$menu_args = $this->setMenuFields($menu_request);
		
				$args = array_merge($args, $menu_args);
			}
		}

		$this->settings->setFields( $args );
	}

	private function setMenuFields($request){
		$args = array(
			
		);

		$parent_name = $request['parent_id'];
		$depth = $request['depth'];
		$language_code = $request['language'];

		$flag = NULL;

		if(array_key_exists("flag", $request)){
			$flag = $request['flag'];
		}

		foreach($request['items'] as $menu_item){
			$current_id = $menu_item['id'];
			$current_name = "$parent_name$current_id";
			$id = "menu_$language_code" . "_$current_name";
			$new_item = array(
				'id' => $id,
				'title' =>'',
				'callback' => array( $this->callbacks, 'selectMenuItens' ),
				'page' => 'uff_mobile_menu_items_section',
				'section' => 'uff_mobile_menu_items_section',
				'args' => array(
					'option_id' => $id,
					'menu_title' => $menu_item['title'],
					'depth' => $depth
				)
			);

			if ($flag != NULL){
				$new_item['args']['flag'] = $flag;
				$flag = NULL;
			}

			array_push(
				$args,
				 $new_item
				);


			if ( array_key_exists('children', $menu_item)){
				$children = $this->setMenuFields(
					array(
						'items'=>$menu_item['children'],
						'language' => $language_code,
						'parent_id'=> $current_name . '_',
						'depth'=> $depth + 1
						)
				);
				$args = array_merge($args, $children);
			}
		}

		return $args;
	}
}