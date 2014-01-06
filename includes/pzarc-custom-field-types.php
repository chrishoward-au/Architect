<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chrishoward
 * Date: 18/10/13
 * Time: 11:13 PM
 * To change this template use File | Settings | File Templates.
 */

define ('YOUR_PLUGIN_URL',PZARC_PLUGIN_URL );

add_filter('admin_body_class','pzarc_add_screen_class');
function pzarc_add_screen_class() {
  $screen = get_current_screen();
  return 'screen-'.$screen->id;
}

add_filter('cmb_field_types', 'pzarc_custom_fields');
function pzarc_custom_fields($fields)
{
  $fields[ 'coreimage' ]  = 'Pizazz_Core_Image_Field';
  $fields[ 'radioimage' ] = 'Pizazz_Radio_Image_Field';
  $fields[ 'pzwysiwyg' ]  = 'Pizazz_Wysiwyg_Field';
  $fields[ 'pzlayout' ]   = 'Pizazz_Layout_Field';
  $fields[ 'pzselect' ]   = 'Pizazz_Select_Field';
  $fields[ 'pzdata' ]     = 'Pizazz_Data_Field';
  $fields[ 'pzspinner' ]  = 'Pizazz_Spinner_Field';
  $fields[ 'pzrange' ]  = 'Pizazz_Range_Field';
  $fields[ 'pzsubmit' ]  = 'Pizazz_Submit_Button';
  $fields[ 'pzgallery' ]  = 'Pizazz_Gallery_Field';
  $fields[ 'pzinfo' ]  = 'Pizazz_Info_Field';
  $fields[ 'pztabs' ]  = 'Pizazz_Tabs_Field';
  return $fields;
}


if (!class_exists('Pizazz_Core_Image_Field'))
{
  class Pizazz_Core_Image_Field extends CMB_Field
  {

    public function html()
    {
      ?>

      <p>
        <img src="<?php echo YOUR_PLUGIN_URL . esc_attr($this->args[ 'default' ]) ?>"/>
      </p>
    <?php
    }

  }
}


if (!class_exists('Pizazz_Radio_Image_Field'))
{
  /**
   * Standard radio field.
   *
   * Args:
   *  - bool "inline" - display the radio buttons inline
   */
  class Pizazz_Radio_Image_Field extends CMB_Field
  {

    public function enqueue_scripts()
    {
      parent::enqueue_scripts();
      wp_enqueue_script('cmb_radio_image_js', YOUR_PLUGIN_URL . 'js/field.radioimage.js', array('jquery'));
    }

    public function enqueue_styles()
    {
      parent::enqueue_styles();
      wp_enqueue_style('cmb_radio_image_css', YOUR_PLUGIN_URL . 'css/field.radioimage.css');
    }

    public function html()
    {

      if ($this->has_data_delegate())
      {
        $this->args[ 'options' ] = $this->get_delegate_data();
      } ?>

      <?php foreach ($this->args[ 'options' ] as $key => $value): ?>

      <span class="field-radio-image">
      <label <?php $this->for_attr('item-' . $key); ?> >
        <img src="<?php echo YOUR_PLUGIN_URL . esc_attr(strtolower($this->args[ 'imgsrc' ][ $key ])) ?>" <?php checked($key, $this->get_value()); ?><?php $this->id_attr('item-' . $key . '-img'); ?>/>
      </label>
      <input <?php $this->id_attr('item-' . $key); ?> <?php $this->boolean_attr(); ?> <?php $this->class_attr(); ?>
              type="radio" <?php $this->name_attr(); ?>
              value="<?php echo esc_attr($key); ?>" <?php checked($key, $this->get_value()); ?> />
      </span>
    <?php endforeach; ?>

    <?php
    }

  }
}

if (!class_exists('Pizazz_Wysiwyg_Field'))
{

  /**
   * wysiwyg field.
   *
   */
  class Pizazz_Wysiwyg_Field extends CMB_Field
  {

    function enqueue_scripts()
    {
      parent::enqueue_scripts();
      wp_enqueue_script('cmb-wysiwyg', CMB_URL . '/js/field-wysiwyg.js', array('jquery'));
    }

    public function html()
    {

      $id   = $this->get_the_id_attr();
      $name = $this->get_the_name_attr();

      $field_id = $this->get_js_id();

      printf('<div class="cmb-wysiwyg" data-id="%s" data-name="%s" data-field-id="%s">', $id, $name, $field_id);

      if ($this->is_placeholder())
      {

        // For placeholder, output the markup for the editor in a JS var.
        ob_start();
        $this->args[ 'options' ][ 'textarea_name' ] = 'cmb-placeholder-name-' . $field_id;
        wp_editor('', 'cmb-placeholder-id-' . $field_id, $this->args[ 'options' ]);
        $editor = ob_get_clean();
//      $editor = str_replace( array( "\n", "\r" ), "", $editor );
//      $editor = str_replace( array( "'" ), '"', $editor );

        ?>

        <script>
          if ('undefined' === typeof( cmb_wysiwyg_editors ))
            var cmb_wysiwyg_editors = {};
          cmb_wysiwyg_editors.<?php echo $field_id; ?> = '<?php echo $editor; ?>';
        </script>

      <?php

      }
      else
      {

        $this->args[ 'options' ][ 'textarea_name' ] = $name;
        echo wp_editor($this->get_value(), $id, $this->args[ 'options' ]);

      }

      echo '</div>';

    }
  }
}

if (!class_exists('Pizazz_Code_Field'))
{
  /**
   * Standard text field.
   *
   * @extends CMB_Field
   */
  class Pizazz_Layout_Field extends CMB_Field
  {

    public function enqueue_scripts()
    {

      parent::enqueue_scripts();

    }

    public function title() {}
    
    public function html()
    {
      echo($this->args[ 'code' ]);
      ?>

      <input type="hidden" <?php $this->id_attr(); ?> <?php $this->boolean_attr(); ?> <?php $this->class_attr(); ?> <?php $this->name_attr(); ?>
             value="<?php echo esc_attr($this->get_value()); ?>"/>

    <?php
    }
  }

  if (!class_exists('Pizazz_Select_Field'))
  {
    /**
     * Standard select field.
     *
     * @supports "data_delegate"
     * @args
     *     'options'     => array Array of options to show in the select, optionally use data_delegate instead
     *     'allow_none'   => bool Allow no option to be selected (will palce a "None" at the top of the select)
     *     'multiple'     => bool whether multiple can be selected
     */
    class Pizazz_Select_Field extends CMB_Field
    {

      public function __construct()
      {

        $args = func_get_args();

        call_user_func_array(array('parent', '__construct'), $args);

        $this->args = wp_parse_args($this->args, array('multiple' => false));

      }

      public function parse_save_values()
      {

        if (isset($this->group_index) && isset($this->args[ 'multiple' ]) && $this->args[ 'multiple' ])
        {
          $this->values = array($this->values);
        }

      }

      public function get_options()
      {

        if ($this->has_data_delegate())
        {
          $this->args[ 'options' ] = $this->get_delegate_data();
        }

        return $this->args[ 'options' ];
      }


      public function html()
      {

        if ($this->has_data_delegate())
        {
          $this->args[ 'options' ] = $this->get_delegate_data();
        }

        $this->output_field();


      }

      public function output_field()
      {

        $val = (array)$this->get_value();

        $name = $this->get_the_name_attr();
        $name .= !empty($this->args[ 'multiple' ]) ? '[]' : null;

        ?>

        <select
                <?php $this->id_attr(); ?>
                <?php $this->boolean_attr(); ?>
                <?php printf('name="%s"', esc_attr($name)); ?>
                <?php printf('data-field-id="%s" ', esc_attr($this->get_js_id())); ?>
                <?php echo !empty($this->args[ 'multiple' ]) ? 'multiple' : '' ?>
                class="cmb_pzselect"
                style="width: 100%"
                >

          <?php if (!empty($this->args[ 'allow_none' ])) : ?>
            <option value=""><?php echo esc_html_x('None', 'select field', 'cmb') ?></option>
          <?php endif; ?>

          <?php foreach ($this->args[ 'options' ] as $value => $name): ?>
            <option <?php selected(in_array($value, $val)) ?>
                    value="<?php echo esc_attr($value); ?>"><?php echo esc_html($name); ?></option>
          <?php endforeach; ?>

        </select>

      <?php
      }


    }
  }
}

if (!class_exists('Pizazz_Data_Field'))
{
  class Pizazz_Data_Field extends CMB_Field
  {


    public function html()
    {
      ?>

      <input type="text" <?php $this->id_attr(); ?> <?php $this->boolean_attr(); ?> <?php $this->class_attr(); ?> <?php $this->name_attr(); ?><?php esc_attr( (!empty( $this->args[ 'class' ] )) ? ' class="' . $this->args[ 'class' ] . '" ' : null  );?>
             value="<?php echo esc_attr($this->get_value()); ?>"/>

    <?php
    }
  }
}

if (!class_exists('Pizazz_Spinner_Field'))
{
  class Pizazz_Spinner_Field extends CMB_Field
  {


    public function html()
    {
      ?>
      <input  type="number" min=<?php echo esc_html( $this->args[ 'min' ])?> max=<?php echo esc_html( $this->args[ 'max' ])?> <?php $this->name_attr(); ?><?php $this->boolean_attr(); ?> <?php $this->class_attr(); ?> <?php $this->id_attr(); ?> value="<?php echo esc_attr($this->get_value()) ?>" /><span class="pzarc-range-spinner spinner-'<?php  echo sanitize_html_class( $this->args[ 'id' ] ) ?>"><?php echo esc_html( (!empty($this->args[ 'suffix' ])?$this->args[ 'suffix' ]:null))?></span><br />

    <?php
    }
  }
}

if (!class_exists('Pizazz_Range_Field'))
{
  class Pizazz_Range_Field extends CMB_Field
  {


    public function html()
    {
      ?>
      <?php echo esc_html( $this->args[ 'min' ])?><input  type="range" style="width:80%;" min=<?php echo esc_html( $this->args[ 'min' ])?> max=<?php echo esc_html( $this->args[ 'max' ])?> <?php $this->name_attr(); ?><?php $this->boolean_attr(); ?> <?php $this->class_attr(); ?> <?php $this->id_attr(); ?> value="<?php echo esc_attr($this->get_value()) ?>" /><span class="pzarc-range-spinner spinner-'<?php  echo sanitize_html_class( $this->args[ 'id' ] ) ?>"><?php echo esc_html( $this->args[ 'max' ])?><?php echo esc_html( $this->args[ 'suffix' ])?></span><br />

    <?php
    }
  }
}
if (!class_exists('Pizazz_Submit_Button'))
{
  class Pizazz_Submit_Button extends CMB_Field
  {

    public function title() {}

    public function html()
    {
      // Note: Currently, this will publish the content. Can be expanded to save draft
      ?>
      <div id="publishing-action">
        <span class="spinner"></span>
        <input name="original_publish" type="hidden" id="original_publish" value="Publish">
        <input type="submit" name="publish" id="publish" class="button button-primary button-large" value="<?php echo esc_html( $this->args['default'])?>" accesskey="p"></div>
     <?php
    }
  }
}

if (!class_exists('Pizazz_Info_Field'))
{
  /**
   * Info field. Only displays the description
   *
   * @extends CMB_Field
   */
  class Pizazz_Info_Field extends CMB_Field
  {

    public function title() {}
    public function html()
    {
    }
  }
}


if (!class_exists('Pizazz_Gallery_Field'))
{
  /**
   * Standard text field.
   *
   * @extends CMB_Field
   */
  class Pizazz_Gallery_Field extends CMB_Field
  {

    public function enqueue_scripts()
    {

      parent::enqueue_scripts();

    }

    public function html()
    {
      ?>

      <input type="text" <?php $this->id_attr(); ?> <?php $this->boolean_attr(); ?> <?php $this->class_attr(); ?> <?php $this->name_attr(); ?>
             value="<?php echo esc_attr($this->get_value()); ?>"/>

    <?php
    }
  }
}

if (!class_exists('Pizazz_Tabs_Field'))
{

  class Pizazz_Tabs_Field extends CMB_Field {

    public function enqueue_scripts()
    {

      parent::enqueue_scripts();
//      wp_enqueue_script('js-buoy', YOUR_PLUGIN_URL.'/external/js/tabby/js/buoy.js');
//      wp_enqueue_script('js-tabby', YOUR_PLUGIN_URL.'/external/js/tabby/js/tabby.js');
      wp_enqueue_script('js-tabify', YOUR_PLUGIN_URL.'/external/js/tabby/js/tabify.js');

    }
    public function enqueue_styles()
    {
      parent::enqueue_styles();
      wp_enqueue_style('tabby-css', YOUR_PLUGIN_URL.'/external/js/tabby/css/tabby-css.css');
    }

    public function title() {}
    public function html() {
      $active = 'active';
      ?><ul class="pztabs tabs"><?php
      foreach ( $this->args['defaults'] as $key => $value ): ?>

        <li <?php $this->class_attr( 'item-' . $key .' '.$active); ?> data-target="<?php echo esc_html( $key );?>">
            <?php echo esc_html( $value ); ?>
        </li>

      <?php $active=null;endforeach; ?>
      </ul>
    <?php }

  }
}