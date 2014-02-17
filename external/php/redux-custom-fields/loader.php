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

add_filter( "redux/{$redux_opt_name}/field/class/pzspinner", "pzspinner_field_path" ); // Adds the local field
function pzspinner_field_path($field) {
  return dirname( __FILE__ ).'/pzspinner/field_pzspinner.php';
}
