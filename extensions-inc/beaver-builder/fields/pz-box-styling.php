<?php

  function pz_box_styling($name, $value, $field, $settings) {
    $value          = ( array )$value; // Make sure we have an array and not an object.
    $default_values = array(
        'number'       => '',
        'type'         => '',
        'colour'       => '',
        'border_style' => '',
    );
    $field_defaults = isset($field['default']) ? (array)$field['default'] : array();

    $defaults                = array_replace($default_values, $field_defaults);
    $css_units               = array(
        ''    => '',
        'px'  => 'px',
        '%'   => '%',
        'em'  => 'em',
        'rem' => 'rem',
    );
    $border_styles           = array(
        ''       => '',
        'none'   => 'None',
        'solid'  => 'Solid',
        'dotted' => 'Dotted',
        'dashed' => 'Dashed',
    );
    $unit_number_name        = $name . '[][number]';
    $unit_number_value       = empty($value['number']) ? $defaults['number'] : $value['number'];
    $unit_colour_name        = $name . '[][colour]';
    $unit_colour_value       = empty($value['colour']) ? $defaults['colour'] : $value['colour'];
    $unit_type_name          = $name . '[][type]';
    $unit_type_value         = empty($value['type']) ? $defaults['type'] : $value['type'];
    $unit_border_style_name  = $name . '[][border_style]';
    $unit_border_style_value = empty($value['border_style']) ? $defaults['border_style'] : $value['border_style'];

    echo '<div class="fl-field-box-styling">';

    // Numeric value
    if (in_array('number', $field['show'])) {
      echo '<input type="number"  class="text" name="' . $unit_number_name . '" value="' . $unit_number_value . '" />';
    }
    // Unit type
    if (in_array('type', $field['show'])) {
      echo '
    <select name="' . $unit_type_name . '" class="fl-field-css-units-units">';
      foreach ($css_units as $k => $unit_name) {
        echo '<option value = "' . $k . '" ' . ($k == $unit_type_value ? ' selected ' : '') . ' > ' . $unit_name . '</option >';
      }
      echo '</select>';
    }

    // Colour
    if (in_array('colour', $field['show']) || in_array('color', $field['show'])) {
      echo '<input type="color"  class="text" name="' . $unit_colour_name . '" value="' . $unit_colour_value . '" />';
    }

    // Border style
    if (in_array('border_style', $field['show'])) {
      echo '    
      <select name="' . $unit_border_style_name . '" class="fl-field-css-units-border-style">';
      foreach ($border_styles as $k => $border_style) {
        echo '<option value = "' . $k . '" ' . ($k == $unit_border_style_value ? ' selected ' : '') . ' > ' . $border_style . '</option >';
      }
      echo '</select>';
    }

    echo '</div>';
    //  var_dump($name, $value, $field, $settings);
  }

  add_action('fl_builder_control_pz-box-styling', 'pz_box_styling', 1, 4);

