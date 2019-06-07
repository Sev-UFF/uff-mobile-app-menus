<div class="wrap">

	<h1>UFF Mobile Menus</h1>
	<?php settings_errors(); ?>


	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-1">Seleção de Menus</a></li>
		<li class=""><a href="#tab-2">Itens do menu</a></li>
	</ul>

	<div class="tab-content">
		<div id="tab-1" class="tab-pane active">
			<form method="post" action="options.php">
				<?php 
					settings_fields( 'uff_mobile_menus_options_group' );
					do_settings_sections( 'uff_mobile_menus_section' );
					submit_button();
				?>
			</form>
		</div>

		<div id="tab-2" class="tab-pane">

			<form method="post" action="options.php">
				<div class="menu" >
					<?php 
						settings_fields( 'uff_mobile_menu_items_group' );
						do_settings_sections( 'uff_mobile_menu_items_section' );
						submit_button();
					?>
				</div>
			</form>

		</div>
	</div>

</div>