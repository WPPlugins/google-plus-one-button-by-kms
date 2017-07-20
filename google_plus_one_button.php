<?php
/*
Plugin Name: Google 'Plus one' Button by kms
Description: WordPress bővítmény a Google +1 (plus one) gomb elhelyezésére. Megjeleníthető bejegyzés előtt, után, illetve az írások mellett bal oldalon úsztatva is.
Author: Miko Andras
Author URI: http://mikoandras.hu/en/
Plugin URI: http://mikoandras.hu/blog/2011/09/12/wordpress-google-plus-one-gomb/
Version: 1.5.0
License: GPL
*/
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2, 
    as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

if (! function_exists('is_admin')) {
  header('Status: 403 Forbidden');
  header('HTTP/1.1 403 Forbidden');
  exit();
}
/* Runs when plugin is activated */
register_activation_hook(__FILE__, 'google_plus_one_install'); 
/* Runs on plugin deactivation*/
register_deactivation_hook(__FILE__, 'google_plus_one_remove');
function google_plus_one_install () {
  /* Do Nothing */
  add_option("rp_gpo_button_size",     'standard', '', 'yes');
  add_option("rp_gpo_include_count",   'false',    '', 'yes');
  add_option("rp_gpo_button_location", 'top',      '', 'yes');
  add_option("rp_gpo_script_load",     'true',     '', 'yes');
  add_option("rp_gpo_top_space",       '',         '', 'yes');
  add_option("rp_gpo_left_space",      '',         '', 'yes');
  add_option("rp_gpo_position",        'fixed',    '', 'yes');
  add_option("rp_gpo_show_on_home",    'false',    '', 'yes');
  add_option("rp_gpo_show_on_single",  'false',    '', 'yes');
  add_option("rp_gpo_filter_tag",      '',         '', 'yes');
}
function google_plus_one_remove () {
  /* Deletes the database field */
  //delete_option('google_share');
  delete_option('rp_gpo_button_size');
  delete_option('rp_gpo_include_count');
  delete_option('rp_gpo_button_location');
  delete_option('rp_gpo_script_load');
  delete_option('rp_gpo_top_space');
  delete_option('rp_gpo_left_space');
  delete_option('rp_gpo_position');
  delete_option('rp_gpo_show_on_home');
  delete_option('rp_gpo_show_on_single');
  delete_option('rp_gpo_filter_tag');
}
// Add the google plus one js library
function enqueue_rp_gpo_scripts () {
  wp_enqueue_script('googleplusone', 'https://apis.google.com/js/plusone.js');
}
// Add the css
function rp_gpo_head () {
  $plugin_dir = trailingslashit(plugins_url(dirname(plugin_basename(__FILE__))));
  $css_url = $plugin_dir . 'gpo_plusone.css';
  if (file_exists(STYLESHEETPATH . "/gpo_plugin.css")) {
    $css_url = get_bloginfo("stylesheet_directory") . "/gpo_plusone.css";
  }
  echo '    <link rel="stylesheet" href="' . $css_url . '" type="text/css" media="screen" />';
}
if (is_admin()) {
  $plugin_dir = dirname(plugin_basename(__FILE__));
  load_plugin_textdomain('google_plus_one_button_by_kms', false, $plugin_dir . '/languages');
  require_once('gpo_admin_page.php');
  add_action('admin_menu', 'google_plus_one_admin_menu');
} else {
  require_once('gpo_display.php');
  add_action('init', 'google_plus_one_share_init');
  add_action('wp_head', 'rp_gpo_head');
  $auto = get_option('rp_gpo_button_location');
  if ($auto != 'manual')
    add_filter('the_content', 'google_plus_one_contents');
}
