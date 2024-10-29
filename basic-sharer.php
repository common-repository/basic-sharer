<?php
/*
Plugin Name: Basic Sharer
Description: Very simple plugin to add share links
Author: Angel Aparicio
Author URI: https://angelaparicio.dev
Version: 0.5
Text Domain: basic_sharer
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function basic_sharer_load_plugin_textdomain() {
	load_plugin_textdomain( 'basic_sharer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'basic_sharer_load_plugin_textdomain' ); 


add_filter( 'the_content', function($content){
	
	$permalink = get_permalink();
	$title = urlencode(get_the_title());
	
	$links = array(
		'Facebook' => array(
			'link' => 'https://www.facebook.com/sharer.php?u='.$permalink.'&t='.$title,
			'logo' => plugin_dir_url(__FILE__).'images/fb-24.png',
			'visible' => get_option('basic_sharer_facebook', true)
		),	
		'Twitter' => array(
			'link' => 'https://twitter.com/share?text='.$title.'&url='.$permalink,
			'logo' => plugin_dir_url(__FILE__).'images/tw-24.png',
			'visible' => get_option('basic_sharer_twitter', true)			
		),
		'Linkedin' => array(
			'link' => 'https://www.linkedin.com/shareArticle?mini=true&title='.$title.'&url='.$permalink,
			'logo' => plugin_dir_url(__FILE__).'images/ln-24.png',
			'visible' => get_option('basic_sharer_linkedin', true)			
		),
	);
	
	$share_links  = '<div id="sharer_links">';
	$share_links .= '<span class="share_links_text">'.__('Share', 'basic_sharer').': </span>';

	foreach ( $links as $network_name => $link_info ){
		if ($link_info['visible']) {
			$share_links .= '<a href="'.$link_info['link'].'" class="external share_'.strtolower($network_name).'" target="_blank"><img style="display: inline" src="'.$link_info['logo'].'" alt="'.$network_name.'" /></a> ';
		}
	}	
	$share_links .= '</div>';
	
	return $content.$share_links;
	
});


add_action( 'admin_menu', function(){
	add_submenu_page( 'options-general.php', 'Basic Sharer Options', 'Basic Sharer', 'manage_options', 'basic_sharer_options', 'basic_sharer_render_options_page');
});

function basic_sharer_render_options_page(){

	if ( isset($_POST['basic_sharer_saving_data']) ){
	
		$basic_sharer_facebook = isset($_POST['basic_sharer_facebook']);
		$basic_sharer_twitter  = isset($_POST['basic_sharer_twitter']);
		$basic_sharer_linkedin = isset($_POST['basic_sharer_linkedin']);
		
		update_option('basic_sharer_facebook', $basic_sharer_facebook);
		update_option('basic_sharer_twitter', $basic_sharer_twitter);
		update_option('basic_sharer_linkedin', $basic_sharer_linkedin);

		echo '<div class="updated"><p><strong>'.__('Updated', 'basic_sharer').'</strong></p></div>';
	
	}
	else {
		$basic_sharer_facebook = get_option('basic_sharer_facebook', true);
		$basic_sharer_twitter = get_option('basic_sharer_twitter', true);
		$basic_sharer_linkedin = get_option('basic_sharer_linkedin', true);
	}
	
	include('options_page.php');
}