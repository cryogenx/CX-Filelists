<?php
/*
Plugin Name: CX-Filelists
Plugin URI: http://gwelsted.com
Description: This plugin displays a listing of files from a supplied directory.
Version: 0.5
Author: George Welsted
Author URI: http://gwelsted.com
License: GPL2
*/

/*
CX-Filelists (Wordpress Plugin)
Copyright (C) 2012 George Welsted
Contact me at http://www.gwelsted.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/
define( 'CXFL_PATH', plugin_dir_path(__FILE__) );
require(CXFL_PATH . 'plugin_options.php');

$options = get_option('cxfl_plugin_options');

function cxFilelist($atts) {
	$options = get_option('cxfl_plugin_options');
	define( 'CXFL_PATH', plugin_dir_path(__FILE__) );
	$path = $atts['path'];
	
	$default_exclude = array('cgi-bin', '.', '..');
	
	if (isset($options['exclude_list'])){
		$custom_exclude = $options['exclude_list'];				
		$custom_exclude = explode(",", $custom_exclude);
		$exclude = array_merge($default_exclude, $custom_exclude);
	} else {
		$exclude = $default_exclude;
	}
	
	ob_start();
	dir_tree($path, $exclude, 'pde');
	$cxfl_output=ob_get_contents();
	ob_end_clean();
	
	return $cxfl_output;
}

add_shortcode('cxfilelist','cxFilelist');


function dir_tree($dir, $exclude, $class = null){ 
	
    $ffs = scandir($dir); 
	echo '<ul class="'.$class.'">'; 
    foreach($ffs as $ff){ 
        if(is_array($exclude) and !in_array($ff,$exclude)){ 
            if($ff != '.' && $ff != '..'){ 
            if(!is_dir($dir.'/'.$ff)){ 
				echo '<li><a href="/'.$dir.'/'.$ff.'">'.$ff.'</a>'; 
            } else { 
				echo '<li><a href="">'.$ff.'</a>';				 
            } 
				
            if(is_dir($dir.'/'.$ff)) dir_tree($dir.'/'.$ff, $exclude); 
			echo '</li>';
            } 
        } 
    } 
	echo '</ul>'; 
} 

function cxfl_present_css_files() {

	define( 'CXFL_URL', plugin_dir_url(__FILE__) );
	$style_path = CXFL_URL . "css/";
	$script_path = CXFL_URL . "js/";
	$image_path = CXFL_URL . "images/";
	
	$style_name = "pde.css";
	$script_name = "pde.js";


	//Load Styles & Scripts		
	 wp_enqueue_style( "CXFL" , $style_path . $style_name);
	 wp_enqueue_script( "CXFL" , $script_path . $script_name);


}
cxfl_present_css_files();
?>