<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc\Api\Callbacks;


class AdminCallbacks
{
	public function adminMenus()
	{
		return require_once( plugin_dir_path (UFF_MOBILE_APP_MENUS_FILE) . "/templates/admin.php" );
	}
	
	
    
    public function selectMenuItens( $args )
	{
		$option_id = $args['option_id'];
		$checkbox = get_option( $option_id );
		$checked = $checkbox ? 'checked' : '';
		$menu_title = $args['menu_title'];
		$depth = $args['depth'];

		$flag = '';

		if (array_key_exists('flag', $args)){
			$url = $args['flag'];
			$flag = "<img class='flag-image' src='$url'>";
		}

		$item = "
		$flag
		<div id='menu-item-$option_id' class='menu-item menu-item-depth-$depth  menu-item-edit-inactive'>
			<div class='menu-item-bar'>
				<div class='menu-item-handle '>
					<span class='item-title'>
						<span class='menu-item-title'>
							$menu_title
						</span> 
					</span>
					<span class='item-controls'>
						<span class='item-type'>
							<input type='checkbox' name='$option_id' value='1' $checked >
						</span>
					</span>
				</div>
			</div>
		</div>
		";

       echo $item;
	}
    
    public function selectMenus($args)
	{
		$option_name = $args['label_for'];
        
		$options = get_option($option_name);
		
		echo "<select name='$option_name'>";
		echo "<option  value='-1' >Não selecionado</option>";
        foreach(wp_get_nav_menus() as $wp_menu) {
			$menu = (array) $wp_menu;
			$id = $menu['term_id'];
			$name = $menu['name'];

            $selected = ($options==$id) ? 'selected="selected"' : '';
            echo "<option value='$id' $selected>$name</option>";
        }
        echo "</select>";
	}

	public function checkboxSanitize($input){
		return ( isset($input) ? true : false ); 
	}
    

}