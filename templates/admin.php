<div class="wrap">
	<h1>plugin teste</h1>
	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php 
			settings_fields( 'alecaddd_options_group' );
			do_settings_sections( 'alecaddd_plugin' );
			submit_button();
		?>
	</form>


	<h1><?php 
	echo "teste conteudo pll compatibiliade";
	
	 ?></h1>
	

	 <h1><?php 
	
	var_dump( pll_default_language() );
	 ?></h1>

<h1><?php 
	

	var_dump( icl_get_languages() );
	
	 ?></h1>
	
	<h1><?php 
	
	$wp_menus = wp_get_nav_menus();
	var_dump( $wp_menus );
	
	 ?></h1>

<h1><?php 
	
	foreach( icl_get_languages() as $language){
		var_dump($language['language_code']);
	}
	
	 ?></h1>
	
	

</div>