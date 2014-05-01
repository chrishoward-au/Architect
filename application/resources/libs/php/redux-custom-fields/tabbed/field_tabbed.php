<?php

if (!class_exists('ReduxFramework_tabbed'))
{

  class ReduxFramework_tabbed extends ReduxFramework
  {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since ReduxFramework 1.0.0
     */
    function __construct($field = array(), $value = '', $parent)
    {

      //parent::__construct( $parent->sections, $parent->args );
      $this->parent = $parent;
      $this->field  = $field;
      $this->value  = $value;

      wp_enqueue_style(
              'redux-field-tabbed-css',
              plugin_dir_url( __FILE__ ) .'/field_tabbed.css',
              time(),
              true
      );

      wp_enqueue_script(
              'redux-field-tabbed-js',
              plugin_dir_url( __FILE__ ) .'/field_tabbed.js',
              array('jquery'),
              time(),
              true
      );

    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since ReduxFramework 1.0.0
     */
    function render()
    {

      if (!empty($this->field[ 'data' ]) && empty($this->field[ 'options' ]))
      {
        if (empty($this->field[ 'args' ]))
        {
          $this->field[ 'args' ] = array();
        }
        $this->field[ 'options' ] = $this->get_wordpress_data($this->field[ 'data' ], $this->field[ 'args' ]);
      }
      $this->field[ 'data_class' ] = (isset($this->field[ 'multi_layout' ])) ? 'data-' . $this->field[ 'multi_layout' ] : 'data-full';

      if (!empty($this->field[ 'options' ]))
      {

        echo '<ul class="' . $this->field[ 'data_class' ] . '">';
         $i = 0;
        foreach ($this->field[ 'options' ] as $k => $v)
        {
          $targets = array();
          foreach ($this->field['targets'][$k] as $value) {
            $targets[] = '#redux-'.$this->parent->args['opt_name'].'-metabox-'.$value;
          }
          // #redux-_architect-metabox-panels-design
          echo '<li data-targets="'.implode(',',$targets).'" '.($i++==0?'class="active"':null).'>';
//          echo '<label for="' . $this->field[ 'id' ] . '_' . array_search($k, array_keys($this->field[ 'options' ])) . '">';
//          echo '<input type="radio" class="radio ' . $this->field[ 'class' ] . '" id="' . $this->field[ 'id' ] . '_' . array_search($k, array_keys($this->field[ 'options' ])) . '" name="' . $this->field[ 'name' ] . $this->field[ 'name_suffix' ] . '" value="' . $k . '" ' . checked($this->value, $k, false) . '/>';
          echo ' <span>' . $v . '</span>';
//          echo '</label>';
          echo '</li>';

        }
        //foreach

        echo '</ul>';

      }


    }


  } //eoc


}