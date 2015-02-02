<?php


  /**
   * Class pzarc_Panel_Layouts
   */
  //  new arc_Panels_Layouts;

  class arc_Panels_Layouts
  {

    public $redux_opt_name = '_architect';
    public $defaults = false;


    /**
     * [__construct description]
     */
    function __construct($defaults = false)
    {


      $this->defaults = $defaults;
      if (is_admin()) {
        add_action('admin_head', array($this, 'cell_layouts_admin_head'));
        add_action('admin_enqueue_scripts', array($this, 'cell_layouts_admin_enqueue'));
        add_filter('manage_arc-panels_posts_columns', array($this, 'add_panel_layout_columns'));
        add_action('manage_arc-panels_posts_custom_column', array($this, 'add_panel_layout_column_content'), 10, 2);

        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this, 'pzarc_panel_tabs'), 10, 1);
        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this, 'pzarc_panels_styling'), 10, 1);
        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this, 'pzarc_panel_general_settings'), 10, 1);
        add_action("redux/metaboxes/$this->redux_opt_name/boxes", array($this, 'pzarc_panels_design'), 10, 1);
        add_filter('views_edit-arc-panels', array($this, 'panels_description'));

      }

    }


    /**
     * [cell_layouts_admin_enqueue description]
     * @param  [type] $hook [description]
     * @return [type]       [description]
     */
    public function cell_layouts_admin_enqueue($hook)
    {

      $screen = get_current_screen();

      if ('arc-panels' == $screen->id) {

        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-resizable');

        wp_enqueue_style('pzarc-admin-panels-css', PZARC_PLUGIN_APP_URL . '/admin/css/arc-admin-panels.css');

        wp_enqueue_script('jquery-pzarc-metaboxes-panels', PZARC_PLUGIN_APP_URL . '/admin/js/arc-metaboxes-panels.js', array('jquery'), null, true);
      }
    }

    /**
     * [cell_layouts_admin_head description]
     * @return [type] [description]
     */
    public function cell_layouts_admin_head()
    {

    }

    /**
     * [add_panel_layout_columns description]
     * @param [type] $columns [description]
     */
    public function add_panel_layout_columns($columns)
    {
      unset($columns[ 'thumbnail' ]);
      $pzarc_front  = array_slice($columns, 0, 2);
      $pzarc_back   = array_slice($columns, 2);
      $pzarc_insert = array
      (
          '_panels_settings_short-name'  => __('Short name', 'pzsp'),
          '_panels_settings_description' => __('Description', 'pzarchitect'),
      );

      return array_merge($pzarc_front, $pzarc_insert, $pzarc_back);
    }

    /**
     * [add_panel_layout_column_content description]
     * @param [type] $column  [description]
     * @param [type] $post_id [description]
     */
    public function add_panel_layout_column_content($column, $post_id)
    {
      $post_meta = get_post_meta($post_id);
      global $post;

      switch ($column) {
        case '_panels_settings_short-name':
          if (isset($post_meta[ $column ])) {
            echo $post_meta[ $column ][ 0 ];
          }
          break;
        case '_panels_settings_description':
          if (isset($post_meta[ $column ])) {
            echo $post_meta[ $column ][ 0 ];
          }
          break;
      }
    }


    function panels_description($content)
    {

      $content[ 'arc-message' ] = '
      <div class="after-title-help postbox">
        <div class="inside">
          <h4>' . __('About Panels', 'pzarchitect') . '</h4>

          <p class="howto">' .
          __('Architect Panels are where you design the layout of the content, that is, choosing how to display the titles, meta data, featured images, excerpts, content etc.', 'pzarchitect') . '</p>

          <p class="howto">' . __('Documentation can be found throughout Architect or online at the', 'pzarchitect') . ' <a
                href="http://architect4wp.com/codex-listings" target="_blank">Architect Codex</a></p>

        </div>
        <!-- .inside -->
      </div><!-- .postbox -->';

      return $content;

    }

    // Load up the metaboxes. Do it all in one hit to easily manage ordering (since order can affect js working)

    function pzarc_panel_tabs($metaboxes)
    {
      $prefix   = '_panels_tabs_';
      $sections = array();
      global $_architect_options;
      if (empty($_architect_options)) {
        $_architect_options = get_option('_architect_options');
      }

      if (!empty($_architect_options[ 'architect_enable_styling' ])) {
        $fields = array(
            array(
                'id'      => $prefix . 'tabs',
                'type'    => 'tabbed',
                'options' => array(
                    'design'  => '<span class="icon-large el-icon-website"></span> ' . __('Design', 'pzarchitect'),
                    'styling' => '<span class="icon-large el-icon-brush"></span> ' . __('Styling', 'pzarchitect')
                ),
                'targets' => array(
                    'design'  => array('panels-design'),
                    'styling' => array('panels-styling')
                )
            ),

        );

        $sections[ ] = array(
            'show_title' => true,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-home',
            'fields'     => $fields
        );

        $metaboxes[ ] = array(
            'id'         => $prefix . 'panel',
            'title'      => __('Show Panels settings for:', 'pzarchitect'),
            'post_types' => array('arc-panels'),
            'sections'   => $sections,
            'position'   => 'normal',
            'priority'   => 'high',
            'sidebar'    => false

        );

        return $metaboxes;
      }
    }

    function pzarc_panel_general_settings($metaboxes,$defaults_only=false)
    {
      $prefix        = '_panels_settings_';
      $sections      = array();
      $sections[ 0 ] = array(
          'show_title' => true,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-home',
          'fields'     => array(
              array(
                  'id'       => '_panels_settings_short-name',
                  'title'    => __('Short name', 'pzarchitect') . '<span class="pzarc-required el-icon-star" title="Required"></span>',
                  'subtitle' => __('Letters, numbers, dashes only', 'pzarchitect'),
                  'hint'     => array('content' => __('A short name for this panel layout to identify it.', 'pzarchitect')),
                  'type'     => 'text',
                  'validate' => 'not_empty',
              ),
              //TODO: Make validation check illegal characters
              array(
                  'id'    => $prefix . 'description',
                  'title' => __('Description', 'pzarchitect'),
                  'type'  => 'textarea',
                  'rows'  => 2,
                  'hint'  => __('A short description to help you or others know what this Panel is for', 'pzarchitect'),
              ),
              array(
                  'title'   => __('Panel Height Type', 'pzarchitect'),
                  'id'      => $prefix . 'panel-height-type',
                  'type'    => 'select',
                  'default' => 'none',
                  'select2' => array('allowClear' => false),
                  'options' => array(
                      'none'       => __('None', 'pzarchitect'),
                      'height'     => __('Exact', 'pzarchitect'),
                      'max-height' => __('Max', 'pzarchitect'),
                      'min-height' => __('Min', 'pzarchitect')
                  ),
                  'hint'    => array('content' => __('Choose if you want an exact height or not for the panels. If you want totally fluid, choose Min, and a height of 0.', 'pzarchitect'))
              ),
              // Hmm? How's this gunna sit with the min-height in templates?
              // We will want to use this for image height cropping when behind.

              array(
                  'title'    => __('Panel Height px', 'pzarchitect'),
                  'id'       => $prefix . 'panel-height',
                  'type'     => 'dimensions',
                  'required' => array($prefix . 'panel-height-type', '!=', 'none'),
                  'width'    => false,
                  'units'    => 'px',
                  'default'  => array('height' => '0'),
                  'hint'     => array('content' => __('Set a height for the panel according to the height type you chose.', 'pzarchitect')),

              ),
          )
      );
      $metaboxes[ ]  = array(
          'id'         => $prefix . 'general-settings',
          'title'      => __('Panel Settings', 'pzarchitect'),
          'post_types' => array('arc-panels'),
          'sections'   => $sections,
          'position'   => 'side',
          'priority'   => 'high',
          'sidebar'    => false

      );

      return $metaboxes;
    }


    /**********
     * Panels Design and Styling
     *********/
    function pzarc_panels_design($metaboxes,$defaults_only=false)
    {
      global $_architect_options;
      if (empty($_architect_options)) {
        $_architect_options = get_option('_architect_options');
      }

      $prefix      = '_panels_design_';
      $sections    = array();
      $sections[ ] = array(
          'title'      => __('Designer', 'pzarchitect'),
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-website',
          'fields'     => array(
              array(
                  'title'        => __('Panel preview', 'pzarchitect'),
                  'id'           => $prefix . 'preview',
                  'type'         => 'code',
                  'readonly'     => false, // Readonly fields can't be written to by code! Weird
                  'code'         => draw_panel_layout(),
                  'default_show' => false,
                  'subtitle'     => __('Drag and drop to reposition and resize components', 'pzarchitect'),
                  'default'      => json_encode(array(
                                                    'title'   => array('width' => 100, 'show' => true),
                                                    'meta1'   => array('width' => 100, 'show' => true),
                                                    'image'   => array('width' => 25, 'show' => true),
                                                    'excerpt' => array('width' => 75, 'show' => true),
                                                    //                                            'caption' => array('width' => 100, 'show' => false),
                                                    'content' => array('width' => 100, 'show' => false),
                                                    'meta2'   => array('width' => 100, 'show' => false),
                                                    'meta3'   => array('width' => 100, 'show' => false),
                                                    'custom1' => array('width' => 100, 'show' => false),
                                                    'custom2' => array('width' => 100, 'show' => false),
                                                    'custom3' => array('width' => 100, 'show' => false))),
                  'hint'         => array('title'   => '',
                                          'content' => __('Drag and drop to sort the order of your elements. <strong>Heights are fluid in panels, so not indicative of how it will look on the page</strong>.', 'pzarchitect'))
              ),
              array(
                  'title'   => __('Components to show', 'pzarchitect'),
                  'id'      => $prefix . 'components-to-show',
                  'type'    => 'button_set',
                  'multi'   => true,
                  'width'   => '100%',
                  'default' => array('title', 'excerpt', 'meta1', 'image'),
                  'options' => array(
                      'title'   => __('Title', 'pzarchitect'),
                      'excerpt' => __('Excerpt', 'pzarchitect'),
                      'content' => __('Content', 'pzarchitect'),
                      'image'   => __('Feature', 'pzarchitect'),
                      'meta1'   => __('Meta1', 'pzarchitect'),
                      'meta2'   => __('Meta2', 'pzarchitect'),
                      'meta3'   => __('Meta3', 'pzarchitect'),
                      'custom1' => __('Custom 1', 'pzarchitect'),
                      'custom2' => __('Custom 2', 'pzarchitect'),
                      'custom3' => __('Custom 3', 'pzarchitect'),
                  ),
                  'hint'    => array('content' => __('Select which base components to include in this panel layout.', 'pzarchitect'))
              ),
              array(
                  'title'   => __('Feature location', 'pzarchitect'),
                  'id'      => $prefix . 'feature-location',
                  'width'   => '100%',
                  'type'    => 'button_set',
                  'default' => 'components',
                  'options' => array(
                      'components'    => __('In Components Group', 'pzarchitect'),
                      'float'         => __('Outside components', 'pzarchitect'),
                      'content-left'  => __('In content left', 'pzarchitect'),
                      'content-right' => __('In content right', 'pzarchitect'),
                      'fill'          => __('Background', 'pzarchitect'),
                  ),
                  'hint'    => array('content' => __('Select the location to display the Feature.', 'pzarchitect'))
              ),
              array(
                  'title'    => __('Feature in', 'pzarchitect'),
                  'id'       => $prefix . 'feature-in',
                  'type'     => 'button_set',
                  'multi'    => true,
                  'default'  => array('excerpt', 'content'),
                  'required' => array(
                      array($prefix . 'feature-location', '!=', 'components'),
                      array($prefix . 'feature-location', '!=', 'float'),
                      array($prefix . 'feature-location', '!=', 'fill'),
                  ),
                  'options'  => array(
                      'excerpt' => __('Excerpt', 'pzarchitect'),
                      'content' => __('Content', 'pzarchitect'),
                  ),
              ),
              array(
                  'title'         => __('Number of custom fields', 'pzarchitect'),
                  'id'            => $prefix . 'custom-fields-count',
                  'type'          => 'spinner',
                  'default'       => 0,
                  'min'           => '0',
                  'max'           => '999',
                  'step'          => '1',
                  'display_value' => 'label',
                  'subtitle'      => __('Each of the three Custom groups can have multiple custom fields. Enter <strong>total</strong> number of custom fields, click Save/Update', 'pzarchitect'),
                  //                  'hint'          => array('content' => __('', 'pzarchitect'))
              ),
              array(
                  'title'         => __('Components area width %', 'pzarchitect'),
                  'id'            => $prefix . 'components-widths',
                  'type'          => 'slider',
                  'default'       => '100',
                  'min'           => '1',
                  'max'           => '100',
                  'step'          => '1',
                  'class'         => ' percent',
                  'display_value' => 'label',
                  'hint'          => array('content' => __('Set the overall width for the components area. Necessary for left or right positioning of sections', 'pzarchitect')),
              ),
              array(
                  'title'   => __('Components area position', 'pzarchitect'),
                  'id'      => $prefix . 'components-position',
                  'type'    => 'button_set',
                  'width'   => '100%',
                  'default' => 'top',
                  'options' => array(
                      'top'    => __('Top', 'pzarchitect'),
                      'bottom' => __('Bottom', 'pzarchitect'),
                      'left'   => __('Left', 'pzarchitect'),
                      'right'  => __('Right', 'pzarchitect'),
                  ),
                  'hint'    => array('content' => __('Position for all the components as a group. </br>NOTE: If feature is set to align, then components will be below the feature, but not at the bottom of the panel. ', 'pzarchitect')),
                  'desc'    => __('Left/right will only take affect when components area width is less than 100%', 'pzarchitect')
              ),
              array(
                  'title'         => __('Nudge components area up/down %', 'pzarchitect'),
                  'id'            => $prefix . 'components-nudge-y',
                  'type'          => 'slider',
                  'default'       => '0',
                  'min'           => '0',
                  'max'           => '100',
                  'step'          => '1',
                  'class'         => ' percent',
                  'required'      => array($prefix . 'feature-location', '=', 'fill'),
                  'display_value' => 'label',
                  'hint'          => array('content' => __('Enter percent to move the components area up/down. </br>NOTE: These measurements are percentage of the panel.', 'pzarchitect'))
              ),
              array(
                  'title'         => __('Nudge components area left/right %', 'pzarchitect'),
                  'id'            => $prefix . 'components-nudge-x',
                  'type'          => 'slider',
                  'default'       => '0',
                  'min'           => '0',
                  'max'           => '100',
                  'step'          => '1',
                  'class'         => ' percent',
                  'required'      => array($prefix . 'feature-location', '=', 'fill'),
                  'display_value' => 'label',
                  'hint'          => array('content' => __('Enter percent to move the components area left/right. </br>NOTE: These measurements are percentage of the panel.', 'pzarchitect'))
              ),
              array(
                  'title'         => __('Thumbnail width %', 'pzarchitect'),
                  'id'            => $prefix . 'thumb-width',
                  'type'          => 'slider',
                  'default'       => '15',
                  'min'           => '5',
                  'max'           => '100',
                  'step'          => '1',
                  'class'         => ' percent',
                  'required'      => array(
                      array($prefix . 'feature-location', '!=', 'fill'),
                      array($prefix . 'feature-location', '!=', 'float'),
                      array($prefix . 'feature-location', '!=', 'components'),
                  ),
                  'display_value' => 'label',
                  'hint'          => array('content' => __('When you have set the featured image to appear in the content/excerpt, this determines its width.', 'pzarchitect'))
              ),
              array(
                  'title'   => __('Make headers and footer', 'pzarchitect'),
                  'id'      => $prefix . 'components-headers-footers',
                  'type'    => 'switch',
                  'on'      => __('Yes', 'pzarchitect'),
                  'off'     => __('No', 'pzarchitect'),
                  'default' => true,
                  'hint'    => array('content' => __('When, Architect will automatically wrap the header and footer components of the panel in header and footer tags to maintain compatibility with current WP layout trends. However, some layouts, such as tabular, are not suited to using the headers and footers.', 'pzarchitect'))
              ),

          )
      );

      /**
       * TITLES
       */

      $sections[ ] = array(
          'title'      => 'Titles',
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-font',
          'fields'     => array(
              array(
                  'title'   => __('Title prefix', 'pzarchitect'),
                  'id'      => $prefix . 'title-prefix',
                  'type'    => 'select',
                  'select2' => array('allowClear' => false),
                  'default' => 'none',
                  'class'   => ' horizontal',
                  'desc'    => __('You must set a left margin on titles for bullets to show.', 'pzarchitect'),
                  'options' => array('none'                 => __('None', 'pzarchitect'),
                                     'disc'                 => __('Disc', 'pzarchitect'),
                                     'circle'               => __('Circle', 'pzarchitect'),
                                     'square'               => __('Square', 'pzarchitect'),
                                     'thumb'                => __('Thumbnail', 'pzarchitect'),
                                     'decimal'              => __('Number', 'pzarchitect'),
                                     'decimal-leading-zero' => __('Number with leading zero', 'pzarchitect'),
                                     'lower-alpha'          => __('Alpha lower', 'pzarchitect'),
                                     'upper-alpha'          => __('Alpha upper', 'pzarchitect'),
                                     'lower-roman'          => __('Roman lower', 'pzarchitect'),
                                     'upper-roman'          => __('Roman upper', 'pzarchitect'),
                                     'lower-greek'          => __('Greek lower', 'pzarchitect'),
                                     'upper-greek'          => __('Greek upper', 'pzarchitect'),
                                     'lower-latin'          => __('Latin lower', 'pzarchitect'),
                                     'upper-latin'          => __('Latin upper', 'pzarchitect'),
                                     'armenian'             => __('Armenian', 'pzarchitect'),
                                     'georgian'             => __('Georgian', 'pzarchitect'),
                  ),
              ),
              array(
                  'title'         => __('Title thumbnail width', 'pzarchitect'),
                  'id'            => $prefix . 'title-thumb-width',
                  'type'          => 'spinner',
                  'default'       => '32',
                  'min'           => '8',
                  'max'           => '1000',
                  'step'          => '1',
                  'display_value' => 'label',
                  'required'      => array('_panels_design_title-prefix', '=', 'thumb'),
                  'hint'          => array('content' => __('', 'pzarchitect')),
              ),
              array(
                  'title'    => __('Prefix separator', 'pzarchitect'),
                  'id'       => $prefix . 'title-bullet-separator',
                  'type'     => 'text',
                  'class'    => 'textbox-small',
                  'default'  => '. ',
                  'required' => array('_panels_design_title-prefix', '!=', 'thumb'),
              ),
              array(
                  'title'   => __('Link titles', 'pzarchitect'),
                  'id'      => $prefix . 'link-titles',
                  //            'cols'    => 4,
                  'type'    => 'switch',
                  'on'      => __('Yes', 'pzarchitect'),
                  'off'     => __('No', 'pzarchitect'),
                  'default' => true

                  /// can't set defaults on checkboxes!
              ),
          )
      );

      /**
       * META
       */
      $sections[ ] = array(
          'title'      => __('Meta', 'pzarchitect'),
          'show_title' => false,
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-calendar',
          'desc'       => __('Available tags are <span class="pzarc-text-highlight">%author%   %email%   %date%   %categories%   %tags   %commentslink%   %editlink%   %id%</span>. For custom taxonomies, prefix with ct:. e.g. To display the Woo Testimonials category, you would use %ct:testimonial-category%.', 'pzarchitect') . '<br><br>' .
              __('Use shortcodes to add custom functions to meta. e.g. [add_to_cart id="%id%"]', 'pzarchitect') . '<br>' .
              __('Note: Enclose any author related text in <span class="pzarc-text-highlight">//</span> to hide it when using excluded authors.', 'pzarchitect') . '<br>' .
              __('Note: The email address will be encoded to prevent automated harvesting by spammers.', 'pzarchitect'),
          'fields'     => array(
            // ======================================
            // META
            // ======================================
            array(
                'title'   => __('Meta1 config', 'pzarchitect'),
                'id'      => $prefix . 'meta1-config',
                'type'    => 'textarea',
                'cols'    => 4,
                'rows'    => 2,
                'default' => '%date% //by// %author%',
            ),
            array(
                'title'   => __('Meta2 config', 'pzarchitect'),
                'id'      => $prefix . 'meta2-config',
                'type'    => 'textarea',
                'rows'    => 2,
                'default' => 'Categories: %categories%   Tags: %tags%',
            ),
            array(
                'title'   => __('Meta3 config', 'pzarchitect'),
                'id'      => $prefix . 'meta3-config',
                'type'    => 'textarea',
                'rows'    => 2,
                'default' => '%commentslink%   %editlink%',
            ),
            array(
                'id'      => $prefix . 'meta-date-format',
                'title'   => __('Date format', 'pzarchitect'),
                'type'    => 'text',
                'default' => 'l, F j, Y g:i a',
                'desc'    => __('See here for information on <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>formatting date and time</a>', 'pzarchitect'),
            ),
            array(
                'title'    => __('Excluded authors', 'pzarchitect'),
                'id'       => $prefix . 'excluded-authors',
                'type'     => 'select',
                'multi'    => true,
                'data'     => 'callback',
                //TODO: Findout how to pass parameters. currently that is doing nothing!
                'args'     => array('pzarc_get_authors', array(false, 0)),
                'default'  => '',
                'subtitle' => __('Select any authors here you want to exclude from showing when the %author% or %email% tag is used.', 'pzarchitect')
            ),
          )
      );

      /**********
       * Content
       *********/
      // EXCERPTS
      $sections[ ] = array(
          'title'      => __('Content/Excerpts', 'pzarchitect'),
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-align-left',
          'fields'     => array(
              array(
                  'title'    => __('Maximize content', 'pzarchitect'),
                  'id'       => $prefix . 'maximize-content',
                  'type'     => 'switch',
                  'on'       => __('Yes', 'pzarchitect'),
                  'off'      => __('No', 'pzarchitect'),
                  'default'  => true,
                  'subtitle' => __('Make excerpt or content 100% width if no featured image.', 'pzarchitect')
              ),
              array(
                  'id'     => $prefix . 'excerpt-heading',
                  'title'  => __('Excerpts', 'pzarchitect'),
                  'type'   => 'section',
                  'indent' => true,
                  'class'  => 'heading',
              ),
              array(
                  'id'      => $prefix . 'excerpts-word-count',
                  'title'   => __('Excerpt length (words)', 'pzarchitect'),
                  'type'    => 'spinner',
                  'default' => 55,
                  'min'     => '1',
                  'max'     => '9999',
                  'step'    => '1',
              ),
              array(
                  'title'   => __('Truncation indicator', 'pzarchitect'),
                  'id'      => $prefix . 'readmore-truncation-indicator',
                  'type'    => 'text',
                  'class'   => 'textbox-small',
                  'default' => '[...]',
              ),
              array(
                  'title'    => __('Display entered excerpts only', 'pzarchitect'),
                  'id'       => $prefix . 'manual-excerpts',
                  'type'     => 'switch',
                  'on'       => __('Yes', 'pzarchitect'),
                  'off'      => __('No', 'pzarchitect'),
                  'default'  => false,
                  'subtitle' => __('Only display excerpts that are actually entered in the Excerpt field of the post editor', 'pzarchitect')
              ),
              array(
                  'title'   => __('Read More', 'pzarchitect'),
                  'id'      => $prefix . 'readmore-text',
                  'type'    => 'text',
                  'class'   => 'textbox-medium',
                  'default' => __('Read more', 'pzarchitect'),
              ),
              array(
                  'id'     => $prefix . 'content-responsive-heading',
                  'title'  => __('Responsive', 'pzarchitect'),
                  'type'   => 'section',
                  'indent' => true,
                  'class'  => 'heading',
              ),
              array(
                  'id'       => $prefix . 'responsive-hide-content',
                  'title'    => __('Hide Content at breakpoint', 'pzarchitect'),
                  'type'     => 'select',
                  'options'  => array(
                      'none' => __('None', 'pzarchitect'),
                      '2'    => __('Small screen ', 'pzarchitect') . $_architect_options[ 'architect_breakpoint_2' ][ 'width' ],
                      '1'    => __('Medium screen ', 'pzarchitect') . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ]
                  ),
                  'default'  => 'none',
                  'subtitle' => __('Breakpoints can be changed in Architect Options', 'pzachitect')
              ),
              array(
                  'title'    => __('Use responsive font sizes', 'pzarchitect'),
                  'id'       => $prefix . 'use-responsive-font-size',
                  'type'     => 'switch',
                  'default'  => false,
                  'subtitle' => __('Enabling this will override all other CSS for content/excerpt text', 'pzarchitect')
              ),
              array(
                  'id'              => $prefix . 'content-font-size-bp1',
                  'title'           => __('Font size - large screen ', 'pzarchitect'),
                  'subtitle'        => $_architect_options[ 'architect_breakpoint_1' ][ 'width' ] . __(' and above', 'pzarchitect'),
                  'required'        => array($prefix . 'use-responsive-font-size', 'equals', true),
                  'type'            => 'typography',
                  'text-decoration' => false,
                  'font-variant'    => false,
                  'text-transform'  => false,
                  'font-family'     => false,
                  'font-size'       => true,
                  'font-weight'     => false,
                  'font-style'      => false,
                  'font-backup'     => false,
                  'google'          => false,
                  'subsets'         => false,
                  'custom_fonts'    => false,
                  'text-align'      => false,
                  //'text-shadow'       => false, // false
                  'color'           => false,
                  'preview'         => false,
                  'line-height'     => true,
                  'word-spacing'    => false,
                  'letter-spacing'  => false,
              ),
              array(
                  'id'              => $prefix . 'content-font-size-bp2',
                  'title'           => __('Font size - medium screen ', 'pzarchitect'),
                  'subtitle'        => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' to ' . $_architect_options[ 'architect_breakpoint_1' ][ 'width' ],
                  'required'        => array($prefix . 'use-responsive-font-size', 'equals', true),
                  'type'            => 'typography',
                  'text-decoration' => false,
                  'font-variant'    => false,
                  'text-transform'  => false,
                  'font-family'     => false,
                  'font-size'       => true,
                  'font-weight'     => false,
                  'font-style'      => false,
                  'font-backup'     => false,
                  'google'          => false,
                  'subsets'         => false,
                  'custom_fonts'    => false,
                  'text-align'      => false,
                  //'text-shadow'       => false, // false
                  'color'           => false,
                  'preview'         => false,
                  'line-height'     => true,
                  'word-spacing'    => false,
                  'letter-spacing'  => false,
              ),
              array(
                  'id'              => $prefix . 'content-font-size-bp3',
                  'title'           => __('Font size - small screen ', 'pzarchitect'),
                  'subtitle'        => $_architect_options[ 'architect_breakpoint_2' ][ 'width' ] . ' and below',
                  'required'        => array($prefix . 'use-responsive-font-size', 'equals', true),
                  'type'            => 'typography',
                  'text-decoration' => false,
                  'font-variant'    => false,
                  'text-transform'  => false,
                  'font-family'     => false,
                  'font-size'       => true,
                  'font-weight'     => false,
                  'font-style'      => false,
                  'font-backup'     => false,
                  'google'          => false,
                  'subsets'         => false,
                  'custom_fonts'    => false,
                  'text-align'      => false,
                  //'text-shadow'       => false, // false
                  'color'           => false,
                  'preview'         => false,
                  'line-height'     => true,
                  'word-spacing'    => false,
                  'letter-spacing'  => false,
              ),
              //            array(
              //                'id'      => $prefix . 'content-font-size-range',
              //                'title'   => __('Font size range', 'pzarchitect'),
              //                'type'    => 'slider',
              //                'min'     => 5,
              //                'max'     => 24,
              //                'step'    => 1,
              //                'handles' => 2,
              //                'default' => array(1=>10,2=>16)
              //            )
          )


      );
      /***************************
       * FEATURE IMAGES
       ***************************/
      $sections[ ] = array(
          'title'      => 'Featured Images/Videos',
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-picture',
          'subtitle'   => __('Left and right margins are included in the image width in the designer. e.g if Feature width is 25% and right margin is 3%, Feature width will be adjusted to 22%', 'pzarchitect'),
          'fields'     => array(
              array(
                  'title'    => __('Feature type', 'pzarchitect'),
                  'id'       => '_panels_settings_feature-type',
                  'type'     => 'button_set',
                  'default'  => 'image',
                  'options'  => array('image' => __('Images', 'pzarchitect'), 'video' => __('Videos', 'pzarchitect')),
                  'subtitle' => __('Choose whether Feature is images or videos.', 'pzarchitect')
              ),
              array(
                  'title'    => __('Image cropping', 'pzarchitect'),
                  'id'       => '_panels_settings_image-focal-point',
                  'type'     => 'select',
                  'default'  => 'respect',
                  'required' => array('_panels_settings_feature-type', '=', 'image'),
                  'options'  => array(
                      'respect' => __('Respect focal point', 'pzarchitect'),
                      'centre'  => __('Centre focal point', 'pzarchitect'),
                      'none'    => __('Crop to centre', 'pzarchitect'),
                      'scale'   => __('Preserve aspect, fit width', 'pzarchitect'),
//                      'shrink'  => __('Shrink', 'pzarchitect')
                  )
              ),
              array(
                  'title'    => __('Use embedded images', 'pzarchitect'),
                  'id'       => '_panels_settings_use-embedded-images',
                  'type'     => 'switch',
                  'on'       => __('Yes', 'pzarchitect'),
                  'off'      => __('No', 'pzarchitect'),
                  'default'  => false,
                  'required' => array('_panels_settings_feature-type', '=', 'image'),
                  'subtitle' => __('Enable this to use the first found attached image in the content if no featured image is set.', 'pzarchitect')
              ),
              array(
                  'title'    => __('Use retina images', 'pzarchitect'),
                  'id'       => '_panels_settings_use-retina-images',
                  'type'     => 'switch',
                  'on'       => __('Yes', 'pzarchitect'),
                  'off'      => __('No', 'pzarchitect'),
                  'default'  => false,
                  'required' => array('_panels_settings_feature-type', '=', 'image'),
                  'subtitle' => __('If enabled, a retina version of the featured image will be created and displayed. Ensure the global setting in Architect Options is on as well. NOTE: This will make your site load slower on retina devices, so you may only want consider which panels you have it enabled on.', 'pzarchitect')
              ),
              // TODO: This will be for proper masonry galleries
//              array(
//                  'id'       => $prefix . 'image-shrinkage',
//                  'title'    => __('Shrink images', 'pzarchitect'),
//                  'type'     => 'slider',
//                  'display_value' => 'label',
//                  'default'       => '100',
//                  'min'           => '0',
//                  'max'           => '100',
//                  'step'          => '5',
//                  'units'         => '%',
//                  'required' => array(
//                      array('_panels_settings_image-focal-point', '=', 'shrink'),
//                      array('_panels_settings_feature-type', '=', 'image')
//                  ),
//              ),
              array(
                  'id'       => $prefix . 'image-max-dimensions',
                  'title'    => __('Maximum dimensions', 'pzarchitect'),
                  'type'     => 'dimensions',
                  'units'    => 'px',
                  'default'  => array('width' => '400', 'height' => '300'),
                  'required' => array(
//                      array('_panels_settings_image-focal-point', '!=', 'shrink'),
                      array('_panels_settings_feature-type', '=', 'image')
                  ),
              ),
              array(
                  'id'             => $prefix . 'image-spacing',
                  'type'           => 'spacing',
                  'mode'           => 'margin',
                  'units'          => '%',
                  'units_extended' => 'false',
                  'title'          => __('Margins', 'pzarchitect'),
                  'default'        => array(
                      'margin-top'    => '0',
                      'margin-right'  => '0',
                      'margin-bottom' => '0',
                      'margin-left'   => '0',
                      'units'         => '%',
                  )
              ),
              array(
                  'title'    => __('Link image', 'pzarchitect'),
                  'id'       => $prefix . 'link-image',
                  'type'     => 'button_set',
                  'options'  => array(
                      'none'     => __('None', 'pzarchitect'),
                      'page'     => __('Post', 'pzarchitect'),
                      'image'    => __('Attachment page', 'pzarchitect'),
                      'original' => __('Lightbox', 'pzarchitect'),
                      'url'      => __('Specific URL', 'pzarchitect')
                  ),
                  'default'  => 'page',
                  'required' => array('_panels_settings_feature-type', '=', 'image'),
                  'subtitle' => __('Makes the image link to the post/page or all images link to a specific URL', 'pzazrchitect')
              ),
              array(
                  'title'    => __('Specific URL', 'pzarchitect'),
                  'id'       => $prefix . 'link-image-url',
                  'type'     => 'text',
                  'required' => array(
                      array($prefix . 'link-image', 'equals', 'url'),
                      array('_panels_settings_feature-type', '=', 'image')
                  ),
                  'validate' => 'url',
                  'subtitle' => __('Enter the URL that all images will link to', 'pzazrchitect')
              ),
              array(
                  'title'    => __('Specific URL tooltip', 'pzarchitect'),
                  'id'       => $prefix . 'link-image-url-tooltip',
                  'type'     => 'text',
                  'required' => array(
                      array($prefix . 'link-image', 'equals', 'url'),
                      array('_panels_settings_feature-type', '=', 'image')
                  ),
                  'validate' => 'url',
                  'subtitle' => __('Enter the text that appears when the user hovers over the link', 'pzazrchitect')
              ),
              array(
                  'title'    => __('Image Captions', 'pzarchitect'),
                  'id'       => $prefix . 'image-captions',
                  'type'     => 'switch',
                  'on'       => __('Yes', 'pzarchitect'),
                  'off'      => __('No', 'pzarchitect'),
                  'default'  => false,
                  'required' => array('_panels_settings_feature-type', '=', 'image'),
              ),
              array(
                  'title'    => __('Centre feature', 'pzarchitect'),
                  'id'       => $prefix . 'centre-image',
                  'type'     => 'switch',
                  'on'       => __('Yes', 'pzarchitect'),
                  'off'      => __('No', 'pzarchitect'),
                  'default'  => false,
                  'required' => array('_panels_settings_feature-type', '=', 'image'),
                  'subtitle' => __('Centres the image horizontally. It is best to display it on its own row, and the content to be 100% wide.', 'pzarchitect')
              ),
              array(
                  'title'    => __('Effect on screen resize', 'pzarchitect'),
                  'id'       => $prefix . 'background-image-resize',
                  'type'     => 'button_set',
                  'required' => array($prefix . 'feature-location', '=', 'fill'),
                  'options'  => array(
                      'trim'  => 'Trim horizontally, retain height',
                      'scale' => __('Scale Vertically & Horizontally', 'pzarchitect')
                  ),
                  'required' => array('_panels_settings_feature-type', '=', 'image'),
                  'default'  => 'trim',
              ),
              array(
                  'id'            => $prefix . 'image-quality',
                  'title'         => __('Image quality', 'pzarchitect'),
                  'type'          => 'slider',
                  'display_value' => 'label',
                  'default'       => '75',
                  'min'           => '20',
                  'max'           => '100',
                  'step'          => '1',
                  'units'         => '%',
                  'hint'          => array('content' => 'Quality to use when processing images')

              ),
          )
      );


      /**
       * CUSTOM FIELDS
       * Why are these here even though they are somewhat content related. They're not choosing the content itself. Yes they do limit the usablity of the panel. Partly this came about because of the way WPdoesn't bind custom fields to specific content types.
       */
      if (!empty($_GET[ 'post' ])) {
        $thispostmeta = get_post_meta($_GET[ 'post' ]);
        $cfcount      = (!empty($thispostmeta[ '_panels_design_custom-fields-count' ][ 0 ]) ? $thispostmeta[ '_panels_design_custom-fields-count' ][ 0 ] : 0);

        for ($i = 1; $i <= $cfcount; $i++) {
          $cfname      = 'Custom field ' . $i . (!empty($thispostmeta[ '_panels_design_cfield-' . $i . '-name' ][ 0 ]) ? ': <br>' . $thispostmeta[ '_panels_design_cfield-' . $i . '-name' ][ 0 ] : '');
          $sections[ ] = array(
              'title'      => $cfname,
              'icon_class' => 'icon-large',
              'icon'       => 'el-icon-wrench',
              'desc'       => __('Note: Only fields with content will show on the Blueprint.', 'pzarchitect'),
              'fields'     => array(
                  array(
                      'title'   => __('Show in custom field group', 'pzarchitect'),
                      'id'      => $prefix . 'cfield-' . $i . '-group',
                      'type'    => 'button_set',
                      'default' => 'custom1',
                      'options' => array('custom1' => __('Custom 1', 'pzarchitect'),
                                         'custom2' => __('Custom 2', 'pzarchitect'),
                                         'custom3' => __('Custom 3', 'pzarchitect'))
                  ),
                  array(
                      'title'    => __('Field name', 'pzarchitect'),
                      'id'       => $prefix . 'cfield-' . $i . '-name',
                      'type'     => 'select',
                      'data'     => 'callback',
                      'args'     => array('pzarc_get_custom_fields'),
                      'subtitle' => __('If a custom field is not shown in the dropdown, it is because it has no data yet.', 'pzarchitect')

                  ),
                  array(
                      'title'   => __('Field type', 'pzarchitect'),
                      'id'      => $prefix . 'cfield-' . $i . '-field-type',
                      'type'    => 'button_set',
                      'default' => 'text',
                      'options' => array('text' => 'Text', 'image' => 'Image', 'date' => 'Date', 'number' => 'Number')

                  ),
                  array(
                      'id'       => $prefix . 'cfield-' . $i . '-date-format',
                      'title'    => __('Date format', 'pzarchitect'),
                      'type'     => 'text',
                      'default'  => 'l, F j, Y g:i a',
                      'desc'     => __('Visit here for information on <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target=_blank>formatting date and time</a>', 'pzarchitect'),
                      'required' => array($prefix . 'cfield-' . $i . '-field-type', '=', 'date'),
                  ),
                  array(
                      'id'            => $prefix . 'cfield-' . $i . '-number-decimals',
                      'title'         => __('Decimals', 'pzarchitect'),
                      'type'          => 'spinner',
                      'default'       => 0,
                      'min'           => '0',
                      'max'           => '100',
                      'step'          => '1',
                      'display_value' => 'label',
                      'subtitle'      => __('Number of decimal places.', 'pzarchitect'),
                      'required'      => array($prefix . 'cfield-' . $i . '-field-type', '=', 'number'),
                  ),
                  array(
                      'id'       => $prefix . 'cfield-' . $i . '-number-decimal-char',
                      'title'    => __('Decimal point character', 'pzarchitect'),
                      'type'     => 'text',
                      'default'  => '.',
                      'required' => array($prefix . 'cfield-' . $i . '-field-type', '=', 'number'),
                  ),
                  array(
                      'id'       => $prefix . 'cfield-' . $i . '-number-thousands-separator',
                      'title'    => __('Thousands separator', 'pzarchitect'),
                      'type'     => 'text',
                      'default'  => ',',
                      'required' => array($prefix . 'cfield-' . $i . '-field-type', '=', 'number'),
                  ),
                  array(
                      'title'    => __('Wrapper tag', 'pzarchitect'),
                      'id'       => $prefix . 'cfield-' . $i . '-wrapper-tag',
                      'type'     => 'select',
                      'default'  => 'p',
                      'options'  => array(
                          'p'    => 'p',
                          'div'  => 'div',
                          'span' => 'span',
                          'h1'   => 'h1',
                          'h2'   => 'h2',
                          'h3'   => 'h3',
                          'h4'   => 'h4',
                          'h5'   => 'h5',
                          'h6'   => 'h6',
                          'none' => __('None', 'pzarchitect')),
                      'subtitle' => __('Select the wrapper element for this custom field', 'pzarchitect')

                  ),
                  array(
                      'id'    => $prefix . 'cfield-' . $i . '-class-name',
                      'title' => __('Add class name', 'pzarchitect'),
                      'type'  => 'text',
                  ),
                  array(
                      'title'    => __('Link field', 'pzarchitect'),
                      'id'       => $prefix . 'cfield-' . $i . '-link-field',
                      'type'     => 'select',
                      'data'     => 'callback',
                      'args'     => array('pzarc_get_custom_fields'),
                      'subtitle' => 'Select a custom field that contains URLs you want to use as the link',
                  ),
                  array(
                      'title' => __('Prefix text', 'pzarchitect'),
                      'id'    => $prefix . 'cfield-' . $i . '-prefix-text',
                      'type'  => 'text',
                  ),
                  array(
                      'title' => __('Prefix image', 'pzarchitect'),
                      'id'    => $prefix . 'cfield-' . $i . '-prefix-image',
                      'type'  => 'media',
                  ),
                  array(
                      'title' => __('Suffix text', 'pzarchitect'),
                      'id'    => $prefix . 'cfield-' . $i . '-suffix-text',
                      'type'  => 'text',
                  ),
                  array(
                      'title' => __('Suffix image', 'pzarchitect'),
                      'id'    => $prefix . 'cfield-' . $i . '-suffix-image',
                      'type'  => 'media',
                  ),
                  array(
                      'title'   => __('Prefix/suffix images width', 'pzarchitect'),
                      'id'      => $prefix . 'cfield-' . $i . '-ps-images-width',
                      'type'    => 'dimensions',
                      'height'  => false,
                      'default' => array('width' => '32px'),
                      'units'   => 'px'
                  ),
                  array(
                      'title'   => __('Prefix/suffix images height', 'pzarchitect'),
                      'id'      => $prefix . 'cfield-' . $i . '-ps-images-height',
                      'type'    => 'dimensions',
                      'width'   => false,
                      'default' => array('height' => '32px'),
                      'units'   => 'px'
                  ),
              )
          );
        }
      }
      $sections[ ] = array(
          'title'      => __('Using panels', 'pzarchitect'),
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-info-sign',
          'fields'     => array(

              array(
                  'title' => __('How to use panels', 'pzarchitect'),
                  'id'    => $prefix . 'help-panels-use',
                  'type'  => 'info',
                  'class' => 'plain',
                  'desc'  => __('<p>To use a Panel, you need to select it to be used by a <strong>Blueprint section</strong></p>
                <img src="' . PZARC_PLUGIN_URL . '/documentation/assets/images/arc-using-panels.jpg" style="max-width:100%;">
                <p>The great thing then is, any Panel can be re-used as often as you like.</p>
                <h2>Associating content</h2>
                <p>Content for the Panel is associated in the Blueprint. This may seem unexpected; however, it enables Panels to be re-usable. For instance, if you had an excerpt layout you wanted to use for three different categories - e.g. News, Sport, Finance - you don\'t want to have to create a Panel for each of those.</p>
            ', 'pzarchitect'))
          )
      );

      $sections[ ] = array(
          'title'      => __('Help', 'pzarchitect'),
          'icon_class' => 'icon-large',
          'icon'       => 'el-icon-question-sign',
          'fields'     => array(
              array(
                  'title'    => __('Panels videos', 'pzarchitect'),
                  'id'       => $prefix . 'help-panels-videos',
                  'subtitle'=> __('Internet connection required'),
                  'type'     => 'raw',
                  'class'    => 'plain',
                  'markdown' => false,
                  'content'=>($defaults_only?'':@file_get_contents('https://s3.amazonaws.com/341public/architect/panels-videos.html')),
              ),
              array(
                  'title'    => __('Online documentation', 'pzarchitect'),
                  'id'       => $prefix . 'help-panels-online-docs',
                  'type'     => 'raw',
                  'markdown' => false,
                  'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>'.__('Architect Online Documentation','pzarchitect').'</a><br>'. __('This is a growing resource. Please check back regularly.', 'pzarchitect')

              ),
          )
      );

      // Create the metaboxes


      $metaboxes[ ] = array(
          'id'         => 'panels-design',
          'title'      => __('Panels Design', 'pzarchitect'),
          'post_types' => array('arc-panels'),
          'sections'   => $sections,
          'position'   => 'normal',
          'priority'   => 'high',
          'sidebar'    => false

      );

      return $metaboxes;

    }


    /**
     * STYLING
     * @param $metaboxes
     * @return array
     */
    function pzarc_panels_styling($metaboxes,$defaults_only=false)
    {

      global $_architect;
      global $_architect_options;
      if (empty($_architect_options)) {
        $_architect_options = get_option('_architect_options');
      }

      if (empty($_architect)) {
        $_architect = get_option('_architect');
      }
      if (!empty($_architect_options[ 'architect_enable_styling' ])) {
        $defaults = get_option('_architect');
        $prefix   = '_panels_styling_';

        $font       = '-font';
        $link       = '-links';
        $padding    = '-padding';
        $margin     = '-margin';
        $background = '-background';
        $border     = '-borders';

        $stylingSections = array();
        $sections        = array();
        $optprefix       = 'architect_config_';
//  $stylingSections[ ] = array(
//          'title'      => 'Styling',
//          'icon_class' => 'icon-large',
//          'icon'       => 'el-icon-brush',
//          'hint' => __('Architect uses standard WordPress class names as much as possible, so your Architect Blueprints will inherit styling from your theme if it uses these. Below you can add your own styling and classes. Enter CSS declarations, such as: background:#123; color:#abc; font-size:1.6em; padding:1%;', 'pzarchitect') . '<br/>' . __('As much as possible, use fluid units (%,em) if you want to ensure maximum responsiveness.', 'pzarchitect') . '<br/>' .
//                  __('The base font size is 10px. So, for example, to get a font size of 14px, use 1.4em. Even better is using relative ems i.e. rem.'),
//
//          'fields'     => array(
        $xsections[ ] = array(
            'title'      => __('Styling', 'pzarchitect'),
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-info-sign',
            'fields'     => array(
                array(
                    'title'    => __('Styling Panels', 'pzarchitect'),
                    'id'       => $prefix . 'styling-panels',
                    'type'     => 'info',
                    'subtitle' => __('To style panels...', 'pzarchitect'),
                )

            )
        );

        /**
         * GENERAL
         */
        $sections[ ] = array(
            'title'      => __('General', 'pzarchitect'),
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-brush',
            'fields'     => pzarc_fields(
//                array(
//                    'title'    => __('Load style'),
//                    'id'       => $prefix . 'panels-load-style',
//                    'type'     => 'select',
//                    'subtitle' => 'Sorry to tease, but this isn\'t implemented yet.',
//                    'options'  => array('none', 'dark', 'light'),
//                    'default'  => 'none'
//                ),
                array(
                    'title'    => __('Panels', 'pzarchitect'),
                    'id'       => $prefix . 'panels-section',
                    'type'     => 'section',
                    'indent'   => true,
                    'class'    => 'heading',
                    'subtitle' => 'Class: .pzarc-panel',
                    'hint'     => array('content' => 'Class: .pzarc-panel'),
                ),
                pzarc_redux_bg($prefix . 'panels' . $background, '', $defaults[ $optprefix . 'panels' . $background ]),
                pzarc_redux_padding($prefix . 'panels' . $padding, '', $defaults[ $optprefix . 'panels' . $padding ]),
                pzarc_redux_borders($prefix . 'panels' . $border, '', $defaults[ $optprefix . 'panels' . $border ]),
                array(
                    'title'    => __('Components group', 'pzarchitect'),
                    'id'       => $prefix . 'components-section',
                    'type'     => 'section',
                    'indent'   => true,
                    'class'    => 'heading',
                    'hint'     => array('content' => 'Class: .pzarc_components'),
                    'subtitle' => 'Class: .pzarc_components',
                ),
                pzarc_redux_bg($prefix . 'components' . $background, array('.pzarc_components'), $defaults[ $optprefix . 'components' . $background ]),
                pzarc_redux_padding($prefix . 'components' . $padding, array('.pzarc_components'), $defaults[ $optprefix . 'components' . $padding ]),
                pzarc_redux_borders($prefix . 'components' . $border, array('.pzarc_components'), $defaults[ $optprefix . 'components' . $border ]),
                array(
                    'title'    => __('Entry', 'pzarchitect'),
                    'id'       => $prefix . 'hentry-section',
                    'type'     => 'section',
                    'indent'   => true,
                    'class'    => 'heading',
                    'hint'     => array('content' => 'Class: .hentry'),
                    'subtitle' => !$this->defaults ? (is_array($_architect[ 'architect_config_hentry-selectors' ]) ? 'Classes: ' . implode(', ', $_architect[ 'architect_config_hentry-selectors' ]) : 'Class: ' . $_architect[ 'architect_config_hentry-selectors' ]) : ''
                ),
                // id,selectors,defaults
                // need to grab selectors from options
                // e.g. $_architect['architect_config_hentry-selectors']
                // Then we need to get them back later
                pzarc_redux_bg($prefix . 'hentry' . $background, !$this->defaults ? $_architect[ 'architect_config_hentry-selectors' ] : '', $defaults[ $optprefix . 'hentry' . $background ]),
                pzarc_redux_padding($prefix . 'hentry' . $padding, !$this->defaults ? $_architect[ 'architect_config_hentry-selectors' ] : '', $defaults[ $optprefix . 'hentry' . $padding ]),
                pzarc_redux_margin($prefix . 'hentry' . $margin, !$this->defaults ? $_architect[ 'architect_config_hentry-selectors' ] : '', $defaults[ $optprefix . 'hentry' . $margin ]),
                pzarc_redux_borders($prefix . 'hentry' . $border, !$this->defaults ? $_architect[ 'architect_config_hentry-selectors' ] : '', $defaults[ $optprefix . 'hentry' . $border ])
            )
        );

        /**
         * TITLES
         */
        $sections[ ] = array(
            'title'      => __('Titles', 'pzarchitect'),
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon' . $font,
            'desc'       => 'Class: .entry-title',
            'fields'     => pzarc_fields(
                pzarc_redux_font($prefix . 'entry-title' . $font, array('.entry-title'), $defaults[ $optprefix . 'entry-title' . $font ]),
                pzarc_redux_bg($prefix . 'entry-title' . $font . $background, array('.entry-title'), $defaults[ $optprefix . 'entry-title' . $font . $background ]),
                pzarc_redux_padding($prefix . 'entry-title' . $font . $padding, array('.entry-title'), $defaults[ $optprefix . 'entry-title' . $font . $padding ]),
                pzarc_redux_margin($prefix . 'entry-title' . $font . $margin, array('.entry-title'), $defaults[ $optprefix . 'entry-title' . $font . $margin ], 'tb'),
                pzarc_redux_borders($prefix . 'entry-title' . $border, array('.entry-title'), $defaults[ $optprefix . 'entry-title' . $border ]),
                pzarc_redux_links($prefix . 'entry-title' . $font . $link, array('.entry-title a'), $defaults[ $optprefix . 'entry-title' . $font . $link ])
            ),
        );

        /**
         * META
         */
        $sections[ ] = array(
            'title'      => __('Meta', 'pzarchitect'),
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-calendar',
            'desc'       => 'Class: .entry_meta',
            'fields'     => pzarc_fields(
                pzarc_redux_font($prefix . 'entry-meta' . $font, array('.entry-meta'), $defaults[ $optprefix . 'entry-meta' . $font ]),
                pzarc_redux_bg($prefix . 'entry-meta' . $font . $background, array('.entry-meta'), $defaults[ $optprefix . 'entry-meta' . $font . $background ]),
                pzarc_redux_padding($prefix . 'entry-meta' . $font . $padding, array('.entry-meta'), $defaults[ $optprefix . 'entry-meta' . $font . $padding ]),
                pzarc_redux_margin($prefix . 'entry-meta' . $font . $margin, array('.entry-meta'), $defaults[ $optprefix . 'entry-meta' . $font . $margin ], 'tb'),
                pzarc_redux_links($prefix . 'entry-meta' . $font . $link, array('.entry-meta a'), $defaults[ $optprefix . 'entry-meta' . $font . $link ])
            )
        );

        /**
         * CONTENT
         */
        $sections[ ] = array(
            'title'      => __('Content', 'pzarchitect'),
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-align-left',
            'fields'     => pzarc_fields(
                array(
                    'title'  => __('Full content', 'pzarc'),
                    'id'     => $prefix . 'entry-content',
                    'type'   => 'section',
                    'indent' => true,
                    'class'  => 'heading',
                ),
                pzarc_redux_font($prefix . 'entry-content' . $font, array('.entry-content'), $defaults[ $optprefix . 'entry-content' . $font ]),
                pzarc_redux_bg($prefix . 'entry-content' . $font . $background, array('.entry-content'), $defaults[ $optprefix . 'entry-content' . $font . $background ]),
                pzarc_redux_padding($prefix . 'entry-content' . $font . $padding, array('.entry-content'), $defaults[ $optprefix . 'entry-content' . $font . $padding ]),
                pzarc_redux_margin($prefix . 'entry-content' . $font . $margin, array('.entry-content'), $defaults[ $optprefix . 'entry-content' . $font . $margin ], 'tb'),
                pzarc_redux_borders($prefix . 'entry-content' . $border, array('.entry-content'), $defaults[ $optprefix . 'entry-content' . $border ]),
                pzarc_redux_links($prefix . 'entry-content' . $font . $link, array('.entry-content a'), $defaults[ $optprefix . 'entry-content' . $font . $link ]),
                array(
                    'title'  => __('Excerpt', 'pzarchitect'),
                    'id'     => $prefix . 'entry-excerpt',
                    'type'   => 'section',
                    'indent' => true,
                    'class'  => 'heading',
                ),
                pzarc_redux_font($prefix . 'entry-excerpt' . $font, array('.entry-excerpt'), $defaults[ $optprefix . 'entry-excerpt' . $font ]),
                pzarc_redux_bg($prefix . 'entry-excerpt' . $font . $background, array('.entry-excerpt'), $defaults[ $optprefix . 'entry-excerpt' . $font . $background ]),
                pzarc_redux_padding($prefix . 'entry-excerpt' . $font . $padding, array('.entry-excerpt'), $defaults[ $optprefix . 'entry-excerpt' . $font . $padding ]),
                pzarc_redux_margin($prefix . 'entry-excerpt' . $font . $margin, array('.entry-excerpt'), $defaults[ $optprefix . 'entry-excerpt' . $font . $margin ]),
                pzarc_redux_borders($prefix . 'entry-excerpt' . $border, array('.entry-excerpt'), $defaults[ $optprefix . 'entry-excerpt' . $border ]),
                pzarc_redux_links($prefix . 'entry-excerpt' . $font . $link, array('.entry-excerpt a'), $defaults[ $optprefix . 'entry-excerpt' . $font . $link ]),
                array(
                    'title'  => __('Read more', 'pzarchitect'),
                    'id'     => $prefix . 'entry-readmore',
                    'type'   => 'section',
                    'indent' => true,
                    'class'  => 'heading',
                    'hint'   => array('content' => 'Class: a.pzarc_readmore'),
                ),
                pzarc_redux_font($prefix . 'entry-readmore' . $font, array('.readmore'), $defaults[ $optprefix . 'entry-readmore' . $font ]),
                pzarc_redux_bg($prefix . 'entry-readmore' . $font . $background, array('.readmore'), $defaults[ $optprefix . 'entry-readmore' . $font . $background ]),
                pzarc_redux_padding($prefix . 'entry-readmore' . $font . $padding, array('.readmore'), $defaults[ $optprefix . 'entry-readmore' . $font . $padding ]),
                pzarc_redux_links($prefix . 'entry-readmore' . $font . $link, array('a.readmore'), $defaults[ $optprefix . 'entry-readmore' . $font . $link ])
            )
        );
        /**
         * FEATURED IMAGE
         */
        $sections[ ] = array(
            'title'      => __('Entry Featured image', 'pzarchitect'),
            'show_title' => false,
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-picture',
            'fields'     => pzarc_fields(
                array(
                    'title'  => __('Image', 'pzarchitect'),
                    'id'     => $prefix . 'entry-image',
                    'type'   => 'section',
                    'indent' => true,
                    'class'  => 'heading',
                    'hint'   => array('content' => 'Class: figure.entry-thumbnail'),
                    //     'hint'    => __('Format the entry featured image', 'pzarchitect')
                ),
                array(
                    'title'                 => __('Background', 'pzarchitect'),
                    'id'                    => $prefix . 'entry-image' . $background,
                    'type'                  => 'spectrum',
                    'background-image'      => false,
                    'background-repeat'     => false,
                    'background-size'       => false,
                    'background-attachment' => false,
                    'background-position'   => false,
                    'preview'               => false,
                    'output'                => array('.pzarc_entry_featured_image'),
                    'default'               => $defaults[ $optprefix . 'entry-image' . $background ]
                ),
                array(
                    'title'   => __('Border', 'pzarchitect'),
                    'id'      => $prefix . 'entry-image' . $border,
                    'type'    => 'border',
                    'all'     => false,
                    'output'  => array('.pzarc_entry_featured_image'),
                    'default' => $defaults[ $optprefix . 'entry-image' . $border ]
                ),
                array(
                    'title'   => __('Padding', 'pzarchitect'),
                    'id'      => $prefix . 'entry-image' . $padding,
                    'mode'    => 'padding',
                    'type'    => 'spacing',
                    'units'   => array('px', '%'),
                    'default' => $defaults[ $optprefix . 'entry-image' . $padding ]
                ),
                array(
                    'title'   => __('Margin', 'pzarchitect'),
                    'id'      => $prefix . 'entry-image' . $margin,
                    'mode'    => 'margin',
                    'type'    => 'spacing',
                    'units'   => array('px', '%'),
                    'default' => $defaults[ $optprefix . 'entry-image' . $margin ],
                    'top'     => true,
                    'bottom'  => true,
                    'left'    => false,
                    'right'   => false
                ),
                array(
                    'title'  => __('Caption', 'pzarchitect'),
                    'id'     => $prefix . 'entry-image-caption',
                    'type'   => 'section',
                    'indent' => true,
                    'class'  => 'heading',
                ),
                pzarc_redux_font($prefix . 'entry-image-caption' . $font, array('figure.entry-thumbnail figcaption.caption'), $defaults[ $optprefix . 'entry-image-caption' . $font ]),
                pzarc_redux_bg($prefix . 'entry-image-caption' . $font . $background, array('figure.entry-thumbnail figcaption.caption'), $defaults[ $optprefix . 'entry-image-caption' . $font . $background ]),
                pzarc_redux_padding($prefix . 'entry-image-caption' . $font . $padding, array('figure.entry-thumbnail figcaption.caption'), $defaults[ $optprefix . 'entry-image-caption' . $font . $padding ])
            )
        );

        /**
         * CUSTOM FIELDS
         */
        if (!empty($_GET[ 'post' ])) {
          $thispostmeta = get_post_meta($_GET[ 'post' ]);
          $cfcount      = (!empty($thispostmeta[ '_panels_design_custom-fields-count' ][ 0 ]) ? $thispostmeta[ '_panels_design_custom-fields-count' ][ 0 ] : 0);
          for ($i = 1; $i <= $cfcount; $i++) {
            $cfname      = 'Custom field ' . $i . (!empty($thispostmeta[ '_panels_design_cfield-' . $i . '-name' ][ 0 ]) ? ': <br>' . $thispostmeta[ '_panels_design_cfield-' . $i . '-name' ][ 0 ] : '');
            $sections[ ] = array(
                'title'      => $cfname,
                'show_title' => false,
                'icon_class' => 'icon-large',
                'icon'       => 'el-icon' . $font,
                'desc'       => 'Class: .entry-customfield-' . $i,
                'fields'     => pzarc_fields(
                    pzarc_redux_font($prefix . 'entry-customfield-' . $i . '' . $font, array('.entry-customfield-' . $i . '')),
                    pzarc_redux_bg($prefix . 'entry-customfield-' . $i . '' . $font . $background, array('.entry-customfield-' . $i . '')),
                    pzarc_redux_padding($prefix . 'entry-customfield-' . $i . '' . $font . $padding, array('.entry-customfield-' . $i . '')),
                    pzarc_redux_margin($prefix . 'entry-customfield-' . $i . '' . $font . $margin, array('.entry-customfield-' . $i . '')),
                    pzarc_redux_borders($prefix . 'entry-customfield-' . $i . '' . $border, array('.entry-customfield-' . $i . '')),
                    pzarc_redux_links($prefix . 'entry-customfield-' . $i . '' . $font . $link, array('.entry-customfield-' . $i . ' a'))
                ),
            );
          }
        }
        /**
         * CUSTOM CSS
         */
        $sections[ ] = array(
            'id'         => 'custom-css',
            'title'      => __('Custom CSS', 'pzarchitect'),
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-wrench',
            'fields'     => array(
                array(
                    'id'       => $prefix . 'custom-css',
                    'type'     => 'ace_editor',
                    'title'    => __('Custom CSS', 'pzarchitect'),
                    'mode'     => 'css',
                    'default'  => $defaults[ $optprefix . 'custom-css' ],
                    'subtitle' => __('To apply to this panel design specifically, prepend with class .pzarc-panel_shortname where shortname is the shortname of this panel. Note: underscore before shortname.', 'pzarchitect')
                ),
            )
        );

        /**
         * HELP
         */
        $sections[ ]  = array(
            'id'         => 'styling-help',
            'title'      => __('Help', 'pzarchitect'),
            'icon_class' => 'icon-large',
            'icon'       => 'el-icon-question-sign',
            'fields'     => array(
                array(
                    'title'    => __('Panels videos', 'pzarchitect'),
                    'id'       => $prefix . 'help-styling-videos',
                    'subtitle'=> __('Internet connection required'),
                    'type'     => 'raw',
                    'class'    => 'plain',
                    'markdown' => false,
                    'content'=>($defaults_only?'':@file_get_contents('https://s3.amazonaws.com/341public/architect/panels-videos.html')),
                ),
                array(
                    'title'    => __('Online documentation', 'pzarchitect'),
                    'id'       => $prefix . 'help-styling-online-docs',
                    'type'     => 'raw',
                    'markdown' => false,
                    'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>'.__('Architect Online Documentation','pzarchitect').'</a><br>'. __('This is a growing resource. Please check back regularly.', 'pzarchitect')

                ),

            )
        );
        $metaboxes[ ] = array(
            'id'         => 'panels-styling',
            'title'      => 'Panels Styling',
            'post_types' => array('arc-panels'),
            'sections'   => $sections,
            'position'   => 'normal',
            'priority'   => 'default',
            'sidebar'    => false

        );

        //pzdebug($metaboxes);


      }

      return $metaboxes;
    }
  }

  // EOC

  /**
   * [draw_panel_layout description]
   * @return [type] [description]
   */
  function draw_panel_layout()
  {
    $return_html = '';

    // Put in a hidden field with the plugin url for use in js
    $return_html
        = '
  <div id="pzarc-custom-pzarc_layout" class="pzarc-custom">
    <div id="pzarc-dropzone-pzarc_layout" class="pzarc-dropzone">
      <div class="pzgp-cell-image-behind"></div>
      <div class="pzarc-content-area sortable">
        <span class="pzarc-draggable pzarc-draggable-title" title="Post title" data-idcode=title ><span>This is the title</span></span>
        <span class="pzarc-draggable pzarc-draggable-meta1 pzarc-draggable-meta" title="Meta info 1" data-idcode=meta1 ><span>Jan 1 2013</span></span>
        <span class="pzarc-draggable pzarc-draggable-excerpt" title="Excerpt with featured image" data-idcode=excerpt ><span><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/sample-image.jpg" style="max-width:20%;padding:2px;" class="pzarc-align none">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. Cras semper sem hendre...[more]</span></span>
        <span class="pzarc-draggable pzarc-draggable-content" title="Full post content" data-idcode=content ><span><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/sample-image.jpg" style="max-width:20%;padding:2px;" class="pzarc-align none"><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/sample-in-content.jpg" style="max-width:30%;float:left;padding:5px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis justo erat. <ul><li>&nbsp;•&nbsp;Cras semper sem hendrerit</li><li>&nbsp;•&nbsp;Tortor porta at auctor</li></ul></span></span>
        <span class="pzarc-draggable pzarc-draggable-image" title="Featured image" data-idcode=image style="max-height: 100px; overflow: hidden;"><span><img src="' . PZARC_PLUGIN_APP_URL . '/shared/assets/images/sample-image.jpg" style="max-width:100%;"></span></span>
        <span class="pzarc-draggable pzarc-draggable-meta2 pzarc-draggable-meta" title="Meta info 2" data-idcode=meta2 ><span>Categories - News, Sport</span></span>
        <span class="pzarc-draggable pzarc-draggable-meta3 pzarc-draggable-meta" title="Meta info 3" data-idcode=meta3 ><span>Comments: 27</span></span>
        <span class="pzarc-draggable pzarc-draggable-custom1 pzarc-draggable-meta" title="Custom field 1" data-idcode=custom1 ><span>Custom content group 1</span></span>
        <span class="pzarc-draggable pzarc-draggable-custom2 pzarc-draggable-meta" title="Custom field 2" data-idcode=custom2 ><span>Custom content group 2</span></span>
        <span class="pzarc-draggable pzarc-draggable-custom3 pzarc-draggable-meta" title="Custom field 3" data-idcode=custom3 ><span>Custom content group 3</span></span>
      </div>
    </div>
    <p class="pzarc-states ">Loading</p>
    <p class="howto "><strong style="color:#d00;">' . __('This is an example only and thus only a <span style="border-bottom: 3px double;">general guide</span> to how the panels will look.', 'pzarchitect') . '</strong></p>
  </div>
  <div class="plugin_url" style="display:none;">' . PZARC_PLUGIN_APP_URL . '</div>
  ';

    return $return_html;
  }


