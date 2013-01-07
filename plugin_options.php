<?php
//--------------------------------------------------
//Options Page Code
//--------------------------------------------------

add_action('admin_menu', 'my_plugin_options');


function my_plugin_options() {  
	add_options_page('CX-Filelists', 'CX-Filelists', 'administrator', __FILE__, 'cxfl_build_options_page');
}

function cxfl_build_options_page() {  ?>  
	<?php $options = get_option('cxfl_plugin_options'); ?>
	<div id="plugin-options-wrap">   
		<div class="icon32" id="icon-tools"> 
			<br /> 
		</div>    
		<h2>CX-Filelists - Options</h2>    
   
		<form method="post" action="options.php" enctype="multipart/form-data">   
			<?php settings_fields('cxfl_plugin_options'); ?>  
			<?php do_settings_sections(__FILE__); ?>
		  <p class="submit">        
			  <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />      
		  </p>    
		</form>  
	</div>
<?php }

add_action('admin_init', 'cxfl_register_and_build_fields');

function cxfl_register_and_build_fields() {   
	register_setting('cxfl_plugin_options', 'cxfl_plugin_options', 'cxfl_validate_setting');
	add_settings_section('cxfl_main_section', 'Main Settings', 'cxfl_section_cb', __FILE__);
	add_settings_field('exclude_list', 'CX-Filelists Excluded Files:', 'cxfl_exclude_setting', __FILE__, 'cxfl_main_section');
}

//API Key Function
function cxfl_exclude_setting(){
	$options = get_option('cxfl_plugin_options');  
	echo "<input name='cxfl_plugin_options[exclude_list]' type='text' value='{$options['exclude_list']}' /> List all directories you dont want to show in the list, comma separated e.g. images,css,cgi-bin <p>Defaults are [cgi-bin,.,..]</p>";
}

function cxfl_section_cb() {}

function cxfl_validate_setting($cxfl_plugin_options) { 
	return $cxfl_plugin_options;
}
?>
