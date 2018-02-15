<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 13/2/18
   * Time: 8:57 PM
   */

  define('_amb_sources',3100);
  define('_amb_sources_settings',3110);
  define('_amb_sources_filters',3120);
  define('_amb_sources_custom_sorting',3130);
  define('_amb_sources_help',3199);

  /**
   *
   * CONTENT
   *
   * @param array $metaboxes
   *
   * @return array
   */
  class arc_mbSource  extends arc_Blueprints_Designer {
    function __construct( $defaults = FALSE ) {
      parent::__construct( $defaults );
      add_action( "redux/metaboxes/$this->redux_opt_name/boxes", array( $this, 'mb_sources', ), 10, 1 );
    }

    function mb_sources( $metaboxes, $defaults_only = FALSE ) {
    pzdb( __FUNCTION__ );

    // TODO: Setup a loop that reads the object containing content type info as appened by the content type classes. Will need a means of letting js know tho.
    $prefix   = '_content_general_'; // declare prefix
    $sections = array();
    /** DISPLAY ALL THE CONTENT TYPES FORMS */
    $registry = arc_Registry::getInstance();

    $content_types      = array();
    $content_post_types = (array) $registry->get( 'post_types' );
    foreach ( $content_post_types as $key => $value ) {
      if ( isset( $value['blueprint-content'] ) ) {
        $content_types[ $value['blueprint-content']['type'] ] = $value['blueprint-content']['name'];
      }
    }
    /** GENERAL  Settings*/
    $blueprint_content_common = $registry->get( 'blueprint-content-common' );

    // If you add/remove a content type, you have to add/remove it's side tab too
    $prefix               = '_content_general_'; // declare prefix
    $sections[_amb_sources] = array(
        'title'      => 'Source',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-file',
        'fields'     => array(
//					array( 'title'=>__('Retrieve from','pzarchitect'),
//					       'id'=>'_blueprints_source-retrieve-from',
//					       'type'=>'button_set',
//					       'options'=> array(
//					       	'local'=>__('Local','pzarchitect'),
//					       	'remotewp'=>__('Remote WP','pzarchitect')
//					       	'remote'=>__('Remote','pzarchitect')
//					       ),
//					       'subtitle'=>__('This is just here to tease you at the moment! It does nothing. Yet... This will require api keys','pzarchitect'),
//					       'default'=>'local'
//					),


array(
    'title'    => __( 'Content source', 'pzarchitect' ),
    'id'       => '_blueprints_content-source',
    'type'     => 'radio',
    //                  'select2'  => array('allowClear' => false),
    'default'  => 'defaults',
    'options'  => $content_types,
    'desc'     => __( 'If you want a Blueprint to display the content of the specific post/page/cpt it is to be displayed on, use the Default content source.<br><strong>Select <em>Defaults</em> if this Blueprint will display blog posts on the blog page</strong>', 'pzarchitect' ),
    'subtitle' => ( array_key_exists( 'snippets', $content_types ) ? '' : 'Several more content sources supported in Architect Pro version, including Pages, Snippets, Galleries, Custom Post Types and a special Dummy option to display dummy content' ),
),
        ),
    );


    /** DISPLAY ALL THE CONTENT TYPES FORMS */
    foreach ( $content_post_types as $key => $value ) {
      if ( isset( $value['blueprint-content'] ) ) {
        foreach ( $value['blueprint-content']['sections']['fields'] as $k => $v ) {
          $v['required'][]                  = array(
              '_blueprints_content-source',
              'equals',
              $value['blueprint-content']['type'],
          );
          $sections[_amb_sources]['fields'][] = $v;
        }
      }
    }
    /** FILTERS */
    $sections[_amb_sources_settings] = $blueprint_content_common[0]['settings']['sections'];
    $sections[_amb_sources_filters]  = $blueprint_content_common[0]['filters']['sections'];

    $sections[_amb_sources_settings]['fields'][] = array(
        'title'    => __( 'Custom field sort', 'pzarchitect' ),
        'id'       => $prefix . 'sort-section',
        'type'     => 'section',
        'indent'   => TRUE,
        'required' => array(
            '_content_general_orderby',
            'equals',
            'custom',
        ),
    );
    $sections[_amb_sources_settings]['fields'][] = array(
        'type'    => 'select',
        'title'   => 'Custom sort field',
        'default' => '',
        'options' => $this->custom_fields,
        'id'      => $prefix . 'custom-sort-key',
    );
    $sections[_amb_sources_settings]['fields'][] = array(
        'type'    => 'select',
        'title'   => 'Custom field type',
        'default' => 'CHAR',
        'options' => array(
            'NUMERIC'     => 'Number',
            'BINARY'      => 'True/false',
            'CHAR'        => 'Text',
            'NUMERICDATE' => 'Numerical date',
            'DATE'        => 'Date',
            'DATETIME'    => 'Date time',
            'TIME'        => 'Time',
        ),
        'id'      => $prefix . 'custom-sort-key-type',
        'desc'    => __( 'Most plugins, such as WooCommerce, will store dates numerically, even though it will appear as a normal date.', 'pzarchitect' ),
    );

    // Custom fields filtering and sorting
    $prefix = '_content_customfields_'; // declare prefix


    $sections[_amb_sources_custom_sorting] = array(
        'title'      => 'Custom field filtering',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-wrench',
        'fields'     => array(
            array(
                'title' => 'Developer information',
                'id'    => '_blueprints_content-fields-filter-info',
                'type'  => 'info',
                'desc'  => 'A WP install can have dozens and dozens of custom fields, so it\'s up to you to know the custom field name you require.<br><strong style="color:tomato;">It\'s up to you to ensure the chosen field is available to the post type being displayed</strong>.<br> See <a href="https://codex.wordpress.org/Class_Reference/WP_Query#Custom_Field_Parameters" target=_blank>WordPress Codex, Class Reference WP_Query, Custom Field Parameters</a> for detailed usage information. <br>Note: You can do not need to set all three fields. You may use just one, two or all three.',
            ),
            array(
                'title'   => __( 'Filter match requirement', 'pzarchitect' ),
                'id'      => '_blueprints_content-fields-filter-jointype',
                'type'    => 'button_set',
                'default' => 'AND',
                'options' => array(
                    'AND' => 'Must match ALL',
                    'OR'  => 'May match ANY',
                ),
                'desc'    => __( 'Each filter is optional. Leave the field empty to ignore it.', 'pzarchitect' ),
            ),
        ),
    );
    for ( $cfi = 1; $cfi <= 3; $cfi ++ ) {
      $sections[_amb_sources_custom_sorting]['fields'][] = array(
          'title'  => __( 'Filter field ' . $cfi, 'pzarchitect' ),
          'id'     => '_blueprints_content-fields-filter-section' . $cfi,
          'type'   => 'section',
          'indent' => TRUE,
      );
      $sections[_amb_sources_custom_sorting]['fields'][] = array(
          'type'     => 'select',
          'title'    => 'Field',
          'subtitle' => __( 'For some plugins, like WooCommerce, the field you need to use is the one beginning with an underscore.', 'pzarchitect' ),
          'default'  => '',
          //            'options'  => array_merge( array( 'title' => 'TITLE', 'date' => 'DATE' ), $this->custom_fields ),
          'options'  => $this->custom_fields,
          'id'       => '_blueprints_content-fields-filter-key' . $cfi,
      );
      $sections[_amb_sources_custom_sorting]['fields'][] =

          array(
              'type'     => 'select',
              'title'    => 'Field type',
              'subtitle' => 'You need to know how the data is stored. For example, the Types plugin stores dates in the numeric Unix timestamp format. Therefore, you would select Numeric here, and Timestamp for the field value format.',
              'default'  => 'CHAR',
              'required' => array(
                  '_blueprints_content-fields-filter-key' . $cfi,
                  '!=',
                  '',
              ),
              'options'  => array(
                  'NUMERIC'  => 'NUMERIC',
                  'BINARY'   => 'BINARY',
                  'CHAR'     => 'CHAR',
                  'DATE'     => 'DATE',
                  'DATETIME' => 'DATETIME',
                  'DECIMAL'  => 'DECIMAL',
                  'SIGNED'   => 'SIGNED',
                  'TIME'     => 'TIME',
                  'UNSIGNED' => 'UNSIGNED',
              ),
              'id'       => '_blueprints_content-fields-filter-type' . $cfi,
          );
      $sections[_amb_sources_custom_sorting]['fields'][] =

          array(
              'type'     => 'text',
              'title'    => 'Filter value',
              'default'  => '',
              'id'       => '_blueprints_content-fields-filter-value' . $cfi,
              'required' => array(
                  '_blueprints_content-fields-filter-key' . $cfi,
                  '!=',
                  '',
              ),
          );
      $sections[_amb_sources_custom_sorting]['fields'][] =

          array(
              'type'     => 'select',
              'title'    => 'Filter value type',
              'subtitle' => 'Set this to ensure correct matching with the field data type. E.g. For Types the plugin date fields set this to Timestamp, which will convert your Filter value to a timestamp value.',
              'default'  => 'string',
              'required' => array(
                  '_blueprints_content-fields-filter-key' . $cfi,
                  '!=',
                  '',
              ),
              'options'  => array(
                  'numeric'   => 'Numeric',
                  'binary'    => 'True/False',
                  'string'    => 'String',
                  'date'      => 'Date',
                  'datetime'  => 'DateTime',
                  'time'      => 'Time',
                  'timestamp' => 'Timestamp',
              ),
              'id'       => '_blueprints_content-fields-filter-value-type' . $cfi,
          );
      $sections[_amb_sources_custom_sorting]['fields'][] =

          array(
              'type'     => 'select',
              'title'    => 'Field compare',
              'default'  => '=',
              'options'  => array(
                  '='           => '=',
                  '!='          => '!=',
                  '>'           => '>',
                  '>='          => '>=',
                  '<'           => '<',
                  '<='          => '<=',
                  'LIKE'        => 'LIKE',
                  'NOT LIKE'    => 'NOT LIKE',
                  'IN'          => 'IN',
                  'NOT IN'      => 'NOT IN',
                  'BETWEEN'     => 'BETWEEN',
                  'NOT BETWEEN' => 'NOT BETWEEN',
                  'EXISTS'      => 'EXISTS',
                  'NOT EXISTS'  => 'NOT EXISTS',
              ),
              'required' => array(
                  '_blueprints_content-fields-filter-key' . $cfi,
                  '!=',
                  '',
              ),
              'id'       => '_blueprints_content-fields-filter-compare' . $cfi,
          );
      $sections[_amb_sources_custom_sorting]['fields'][] =

          array(
              'id'     => '_blueprints_content-fields-filter-section-end' . $cfi,
              'type'   => 'section',
              'indent' => FALSE,
          );
    }

    // Help
    $prefix                    = '_content_help_'; // declare prefix
    $sections[_amb_sources_help] = array(
        'title'      => 'Help',
        'icon_class' => 'icon-large',
        'icon'       => 'el-icon-question-sign',
        'fields'     => array(

            array(
                'title'    => __( 'Snippets', 'pzarchitect' ),
                'id'       => $prefix . 'help-content-selection',
                'type'     => 'raw',
                'markdown' => TRUE,
                'content'  => __( 'With Architect Pro you can enable an extra content type called *Snippets*.
  These give you a third method of creating content that doesn\'t fit into the post or page types.
It came about with my own need to create grids of product features. I didn\'t want to fill up pages or posts, so created Snippets for these small content bites.
You can use them however you like though, e.g Testimonials, FAQs, Features, Contacts, etc.
                ', 'pzarchitect' ),

            ),
            array(
                'title'    => __( 'Online documentation', 'pzarchitect' ),
                'id'       => $prefix . 'help-content-online-docs',
                'type'     => 'raw',
                'markdown' => FALSE,
                'content'  => '<a href="http://architect4wp.com/codex-listings/" target=_blank>' . __( 'Architect Online Documentation', 'pzarchitect' ) . '</a><br>' . __( 'This is a growing resource. Please check back regularly.', 'pzarchitect' ),

            ),

        ),
    );

    $metaboxes[] = array(
        'id'         => 'content-selections',
        'title'      => 'Content Source selection: Choose which posts, pages or other content to display',
        'post_types' => array( 'arc-blueprints' ),
        'sections'   => $sections,
        'position'   => 'normal',
        'priority'   => 'low',
        'sidebar'    => FALSE,

    );

    return $metaboxes;

  }
  }
  new arc_mbSource();