<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc\Pages;


use Inc\Api\SettingsApi;
use Inc\Api\Callbacks\AdminCallbacks;
/*
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
*/

class Admin
{
	public $settings;
	public $callbacks;
	public $pages = array();
	public $subpages = array();
	public function register() 
	{
		$this->settings = new SettingsApi();
		$this->callbacks = new AdminCallbacks();
		$this->setPages();
		$this->setSubpages();
		$this->setSettings();
		$this->setSections();
		$this->setFields();
		$this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->addSubPages( $this->subpages )->register();
	}
	public function setPages() 
	{
		$this->pages = array(
			array(
				'page_title' => 'Alecaddd Plugin', 
				'menu_title' => 'Alecaddd', 
				'capability' => 'manage_options', 
				'menu_slug' => 'alecaddd_plugin', 
				'callback' => array( $this->callbacks, 'adminDashboard' ), 
				'icon_url' => 'dashicons-store', 
				'position' => 110
			)
		);
	}
	public function setSubpages()
	{
		$this->subpages = array(
			array(
				'parent_slug' => 'alecaddd_plugin', 
				'page_title' => 'Custom Post Types', 
				'menu_title' => 'CPT', 
				'capability' => 'manage_options', 
				'menu_slug' => 'alecaddd_cpt', 
				'callback' => array( $this->callbacks, 'adminCpt' )
			),
			array(
				'parent_slug' => 'alecaddd_plugin', 
				'page_title' => 'Custom Taxonomies', 
				'menu_title' => 'Taxonomies', 
				'capability' => 'manage_options', 
				'menu_slug' => 'alecaddd_taxonomies', 
				'callback' => array( $this->callbacks, 'adminTaxonomy' )
			),
			array(
				'parent_slug' => 'alecaddd_plugin', 
				'page_title' => 'Custom Widgets', 
				'menu_title' => 'Widgets', 
				'capability' => 'manage_options', 
				'menu_slug' => 'alecaddd_widgets', 
				'callback' => array( $this->callbacks, 'adminWidget' )
			)
		);
	}
	public function setSettings()
	{
		$args = array(
			array(
				'option_group' => 'alecaddd_options_group',
				'option_name' => 'text_example',
				'callback' => array( $this->callbacks, 'alecadddOptionsGroup' )
			),
			array(
				'option_group' => 'alecaddd_options_group',
				'option_name' => 'first_name'
			),
			array(
				'option_group' => 'alecaddd_options_group',
				'option_name' => 'test_sev',
				'callback' => array( $this->callbacks, 'testSev' )
			)
		);

		$items = array("Red", "Green", "Blue", "Orange", "White", "Violet", "Yellow");

		foreach ($items as $color) {
			array_push(
				$args, 
				array(
					'option_group' => 'alecaddd_options_group',
					'option_name' => "color_$color",
					'callback' => array( $this->callbacks, 'checkboxSanitize' )
				)
			);
		}

		$this->settings->setSettings( $args );
	}
	public function setSections()
	{
		$args = array(
			array(
				'id' => 'alecaddd_admin_index',
				'title' => 'Settings',
				'callback' => array( $this->callbacks, 'alecadddAdminSection' ),
				'page' => 'alecaddd_plugin'
			)
		);
		$this->settings->setSections( $args );
	}
	public function setFields()
	{
		$args = array(
			array(
				'id' => 'text_example',
				'title' => 'Text Example',
				'callback' => array( $this->callbacks, 'alecadddTextExample' ),
				'page' => 'alecaddd_plugin',
				'section' => 'alecaddd_admin_index',
				'args' => array(
					'label_for' => 'text_example',
					'class' => 'example-class'
				)
			),
			array(
				'id' => 'first_name',
				'title' => 'First Name',
				'callback' => array( $this->callbacks, 'alecadddFirstName' ),
				'page' => 'alecaddd_plugin',
				'section' => 'alecaddd_admin_index',
				'args' => array(
					'label_for' => 'first_name',
					'class' => 'example-class'
				)
			),
			array(
				'id' => 'test_sev',
				'title' => 'Test Sev',
				'callback' => array( $this->callbacks, 'sevTestNew' ),
				'page' => 'alecaddd_plugin',
				'section' => 'alecaddd_admin_index',
				'args' => array(
					'label_for' => 'test_sev',
					'class' => 'sev-class'
				)
			)
		);


		$items = array("Red", "Green", "Blue", "Orange", "White", "Violet", "Yellow");

		foreach ($items as $color) {
			array_push(
				$args, 
				array(
					'id' => "color_$color",
					'title' => $color,
					'callback' => array( $this->callbacks, 'checkboxField' ),
					'page' => 'alecaddd_plugin',
					'section' => 'alecaddd_admin_index',
					'args' => array(
						'label_for' => "color_$color",
						'class' => 'ui-toggle'
					)
				)
			);
		}

		$this->settings->setFields( $args );
	}
}