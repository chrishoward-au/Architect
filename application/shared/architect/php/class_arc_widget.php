<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 7/05/2014
   * Time: 10:46 PM
   */
// Creating the widget
  class arc_Widget extends WP_Widget
  {

    function __construct()
    {
      parent::__construct(
// Base ID of your widget
          'arc_Widget',

// Widget name will appear in UI
          __('Architect blueprint', 'pzarchitect'),

// Widget description
          array('description' => __('Display an Architect blueprint', 'pzarchitect'),)
      );
      add_action('arc_do_widget', 'pzarc', 10, 3);
    }

// Creating widget front-end
// This is where the action happens
    public function widget($args, $instance)
    {
      $pzarc_caller       = 'widget';
      $title              = apply_filters('widget_title', $instance[ 'title' ]);
      $pzarc_blueprint_id = pzarc_convert_name_to_id($instance[ 'arc_bp_shortname' ]);
      $pzarc_blueprint    = get_post_meta($pzarc_blueprint_id, '_blueprints_short-name', true);

      $pzarc_ids       = !empty($instance[ 'arc_bp_overrides' ]) ? $instance[ 'arc_bp_overrides' ] : null;
      $pzarc_tax       = !empty($instance[ 'arc_bp_tax' ]) ? $instance[ 'arc_bp_tax' ] : null;
      $pzarc_terms     = !empty($instance[ 'arc_bp_terms' ]) ? $instance[ 'arc_bp_terms' ] : null;

      $pzarc_overrides = array('ids'=>$pzarc_ids,'tax'=>$pzarc_tax,'terms'=>$pzarc_terms);

// before and after widget arguments are defined by themes
      echo $args[ 'before_widget' ];
      if (!empty($title)) {
        echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
      }
      do_action("arc_before_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
      do_action("arc_do_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
      do_action("arc_after_{$pzarc_caller}", $pzarc_blueprint, $pzarc_overrides, $pzarc_caller);
      echo $args[ 'after_widget' ];
    }

// Widget Backend
    public function form($instance)
    {
      if (isset($instance[ 'title' ])) {
        $title = $instance[ 'title' ];
      } else {
        $title = __('New title', 'pzarchitect');
      }
      if (isset($instance[ 'arc_bp_shortname' ])) {
        $arc_bp_shortname = $instance[ 'arc_bp_shortname' ];
      } else {
        $arc_bp_shortname = __('', 'pzarchitect');
      }
      if (isset($instance[ 'arc_bp_overrides' ])) {
        $arc_bp_overrides = $instance[ 'arc_bp_overrides' ];
      } else {
        $arc_bp_overrides = __('', 'pzarchitect');
      }
      if (isset($instance[ 'arc_bp_tax' ])) {
        $arc_bp_tax = $instance[ 'arc_bp_tax' ];
      } else {
        $arc_bp_tax = __('', 'pzarchitect');
      }
      if (isset($instance[ 'arc_bp_terms' ])) {
        $arc_bp_terms = $instance[ 'arc_bp_terms' ];
      } else {
        $arc_bp_terms = __('', 'pzarchitect');
      }
//      pzdebug();
      $blueprint_list = pzarc_get_posts_in_post_type();
      $blank_array = array('none'=>'');
      $taxonomy_list=get_taxonomies(array(
         'public'   => true,
          '_builtin' => false

      ) );
      foreach ($taxonomy_list as $k => $v) {
        $tax_obj = get_taxonomy($k);
        $taxonomy_list[$k]=$tax_obj->labels->name;
      }
      $extras = array(0=>'','category'=>'Categories','post_tag'=>'Tags');
      $taxonomy_list = $extras+$taxonomy_list;


// Widget admin form
      ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
               name="<?php echo $this->get_field_name('title'); ?>" type="text"
               value="<?php echo esc_attr($title); ?>"/>
      </p>
      <p>

        <label for="<?php echo $this->get_field_id('arc_bp_shortname'); ?>"><?php _e('Blueprint shortname:'); ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id('arc_bp_shortname'); ?>"
                name="<?php echo $this->get_field_name('arc_bp_shortname'); ?>">
          <?php pzarc_array_to_options_list($blueprint_list, $arc_bp_shortname); ?>
        </select>
      </p>
      <h3>Overrides</h3>
      <p>You can override the filtering in a Blueprint:</p>
      <p>
        <label
            for="<?php echo $this->get_field_id('arc_bp_overrides'); ?>"><?php _e('Specific IDs (comma separated)'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('arc_bp_overrides'); ?>"
               name="<?php echo $this->get_field_name('arc_bp_overrides'); ?>" type="text"
               value="<?php echo esc_attr($arc_bp_overrides); ?>"/>
      </p>
      <p>

        <label for="<?php echo $this->get_field_id('arc_bp_tax'); ?>"><?php _e('Taxonomy'); ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id('arc_bp_tax'); ?>"
                name="<?php echo $this->get_field_name('arc_bp_tax'); ?>">
          <?php pzarc_array_to_options_list($taxonomy_list, $arc_bp_tax); ?>
        </select>
      </p>

      <p>
        <label
            for="<?php echo $this->get_field_id('arc_bp_terms'); ?>"><?php _e('Terms (comma separated)'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('arc_bp_terms'); ?>"
               name="<?php echo $this->get_field_name('arc_bp_terms'); ?>" type="text"
               value="<?php echo esc_attr($arc_bp_terms); ?>"/>
      </p>
    <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
      $instance                       = array();
      $instance[ 'title' ]            = (!empty($new_instance[ 'title' ])) ? strip_tags($new_instance[ 'title' ]) : '';
      $instance[ 'arc_bp_shortname' ] = (!empty($new_instance[ 'arc_bp_shortname' ])) ? strip_tags($new_instance[ 'arc_bp_shortname' ]) : '';
      $instance[ 'arc_bp_overrides' ] = (!empty($new_instance[ 'arc_bp_overrides' ])) ? strip_tags($new_instance[ 'arc_bp_overrides' ]) : '';
      $instance[ 'arc_bp_tax' ]       = (!empty($new_instance[ 'arc_bp_tax' ])) ? strip_tags($new_instance[ 'arc_bp_tax' ]) : '';
      $instance[ 'arc_bp_terms' ]     = (!empty($new_instance[ 'arc_bp_terms' ])) ? strip_tags($new_instance[ 'arc_bp_terms' ]) : '';

      return $instance;
    }
  } // Class wpb_widget ends here

// Register and load the widget
  function pzarc_load_widget()
  {
    register_widget('arc_Widget');
  }

  add_action('widgets_init', 'pzarc_load_widget');
