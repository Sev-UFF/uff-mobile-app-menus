<?php
/** 
* @package UFF_MOBILE_APP_MENUS
*/

namespace Inc\Api\Callbacks;


class AdminCallbacks
{
	public function adminDashboard()
	{
		return require_once( plugin_dir_path (UFF_MOBILE_APP_MENUS_FILE) . "/templates/admin.php" );
	}
	public function adminCpt()
	{
		return require_once( plugin_dir_path (UFF_MOBILE_APP_MENUS_FILE) .  "/templates/admin.php" );
	}
	public function adminTaxonomy()
	{
		return require_once( plugin_dir_path (UFF_MOBILE_APP_MENUS_FILE) .  "/templates/admin.php" );
	}
	public function adminWidget()
	{
		return require_once( plugin_dir_path (UFF_MOBILE_APP_MENUS_FILE) .  "/templates/admin.php" );
	}
	public function alecadddOptionsGroup( $input )
	{
		return $input;
    }
    
    public function testSev( $input )
	{
       return $input;
    }
	public function alecadddAdminSection()
	{
		echo 'Check this beautiful section!';
    }
    
    public function sevTestNew($args)
	{
		$value = esc_attr( get_option( 'test_sev' ) );
        echo '</br></br>' . $value . '</br></br>';
        
        $options = get_option('test_sev');
        $items = array("Red", "Green", "Blue", "Orange", "White", "Violet", "Yellow");
        echo "<select name='test_sev'>";
        foreach($items as $item) {
            $selected = ($options==$item) ? 'selected="selected"' : '';
            echo "<option value='$item' $selected>$item</option>";
        }
        echo "</select>";
	}
	
	public function checkboxField($args){
		$name = $args['label_for'];
		$classes = $args['class'];
		$checkbox = get_option( $name );
		echo '<input type="checkbox" name="' . $name . '" value="1" class="' . $classes . '" ' . ($checkbox ? 'checked' : '') . '>';
	}

	public function checkboxSanitize($input){
		return ( isset($input) ? true : false ); 
	}
    

	public function alecadddTextExample()
	{
		$value = esc_attr( get_option( 'text_example' ) );
		echo '<input type="text" class="regular-text" name="text_example" value="' . $value . '" placeholder="Write Something Here!">';
	}
	public function alecadddFirstName()
	{
		$value = esc_attr( get_option( 'first_name' ) );
		echo '<input type="text" class="regular-text" name="first_name" value="' . $value . '" placeholder="Write your First Name">';
	}
}