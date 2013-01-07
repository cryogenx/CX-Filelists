<?php
/*
Plugin Name: CX-Filelists
Plugin URI: http://gwelsted.com
Description: This plugin displays a listing of files from a supplied directory.
Version: 0.1
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
	define( 'CXFL_PATH', plugin_dir_path(__FILE__) );
	$path = $atts['path'];
	
	echo '<ul class="pde">';
		getDirectory($path);
	echo '</ul>';

}

add_shortcode('cxfilelist','cxFilelist');


function getDirectory($path = '.' , $level = 0){
	define( 'CXFL_PATH', plugin_dir_path(__FILE__) );
	$options = get_option('cxfl_plugin_options');
	
	$default_ignore = array('cgi-bin', '.', '..');
	
	if (isset($options['exclude_list'])){
		$custom_ign = $options['exclude_list'];
				
		$custom_ignore = explode(",", $custom_ign);
		$ignore = array_merge($default_ignore, $custom_ignore);
	} else {
		$ignore = $default_ignore;
	}

$replace = array("[A]");
    // Directories to ignore when listing output. Many hosts
    // will deny PHP access to the cgi-bin.

    $dh = @opendir( $path );
    // Open the directory to the handle $dh
    
    while( false !== ( $file = readdir( $dh ) ) ){
    // Loop through the directory
    
        if( !in_array( $file, $ignore ) ){
        // Check that this file is not to be ignored
            
            $spaces = str_repeat( '&nbsp;', ( $level * 4 ) );
            // Just to add spacing to the list, to better
            // show the directory tree.
            
            if( is_dir( "$path/$file" ) ){
            // Its a directory, so we need to keep reading down...
				$file2 = str_replace($replace,"",$file);
                echo "<li><a href='#'><b>$spaces $file2</b></a>";
                echo "<ul>";
				getDirectory( "$path/$file", ($level+1) );
				echo "</ul>";
                // Re-call this same function but on a new directory.
                // this is what makes function recursive.
           
            } else {
            	$file3 = str_replace("!","",$file);
                echo "<li><a href='$path/$file' target='blank'>$spaces $file3</a></li>";
			
                // Just print out the filename
            
            }
        	
        }
    
    }
    
    closedir( $dh );
    // Close the directory handle
	
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