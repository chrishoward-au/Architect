<?php
/**
 * Created by PhpStorm.
 * User: chrishoward
 * Date: 14/02/2014
 * Time: 11:19 AM
 */
$redux_opt_name = '_architect';

add_filter( "redux/{$redux_opt_name}/field/class/code", "code_field_path" ); // Adds the local field
function code_field_path($field) {
  return dirname( __FILE__ ).'/code/field_code.php';
}

add_filter( "redux/{$redux_opt_name}/field/class/tabbed", "tabbed_field_path" ); // Adds the local field
function tabbed_field_path($field) {
  return dirname( __FILE__ ).'/tabbed/field_tabbed.php';
}

  add_filter( "redux/{$redux_opt_name}/field/class/links", "links_field_path" ); // Adds the local field
  function links_field_path($field) {
    return dirname( __FILE__ ).'/links/field_links.php';
  }
  
  add_filter( "redux/{$redux_opt_name}/field/class/spectrum", "spectrum_field_path" ); // Adds the local field
  function spectrum_field_path($field) {
    return dirname( __FILE__ ).'/spectrum/field_spectrum.php';
  }
