<?php

$redux_opt_name = "redux_demo";


add_action('load_textdomain', 'create_post_type');
function create_post_type()
{
  register_post_type('acme_product',
                     array(
                             'labels'      => array(
                                     'name'          => __('Products'),
                                     'singular_name' => __('Product')
                             ),
                             'public'      => true,
                             'has_archive' => true,
                     )
  );
}


if (!function_exists("redux_add_metaboxes")):
  function redux_add_metaboxes($metaboxes)
  {


    $boxSections[ ] = array(
            'title'  => __('Home Settings', 'redux-framework-demo'),
            //'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'redux-framework-demo'),
            'icon'   => 'el-icon-home',
            'fields' => array(
                    array(
                            'id'       => 'webFonts',
                            'type'     => 'media',
                            'title'    => __('Web Fonts', 'redux-framework-demo'),
                            'compiler' => 'true',
                            'mode'     => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                            'desc'     => __('Basic media uploader with disabled URL input field.', 'redux-framework-demo'),
                            'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                    ),
                    array(
                            'id'       => 'section-media-start',
                            'type'     => 'section',
                            'title'    => __('Media Options', 'redux-framework-demo'),
                            'subtitle' => __('With the "section" field you can create indent option sections.'),
                            'indent'   => true // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                            'id'       => 'media',
                            'type'     => 'media',
                            'url'      => true,
                            'title'    => __('Media w/ URL', 'redux-framework-demo'),
                            'compiler' => 'true',
                            //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                            'desc'     => __('Basic media uploader with disabled URL input field.', 'redux-framework-demo'),
                            'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                            'default'  => array('url' => 'http://s.wordpress.org/style/images/codeispoetry.png'),
                    ),
                    array(
                            'id'     => 'section-media-end',
                            'type'   => 'section',
                            'indent' => false // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                            'id'       => 'media-nourl',
                            'type'     => 'media',
                            'title'    => __('Media w/o URL', 'redux-framework-demo'),
                            'desc'     => __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'redux-framework-demo'),
                            'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                    ),
                    array(
                            'id'       => 'media-nopreview',
                            'type'     => 'media',
                            'preview'  => false,
                            'title'    => __('Media No Preview', 'redux-framework-demo'),
                            'desc'     => __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'redux-framework-demo'),
                            'subtitle' => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                    ),
                    array(
                            'id'       => 'gallery',
                            'type'     => 'gallery',
                            'title'    => __('Add/Edit Gallery', 'so-panels'),
                            'subtitle' => __('Create a new Gallery by selecting existing or uploading new images using the WordPress native uploader', 'so-panels'),
                            'desc'     => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                            'id'      => 'slider1b',
                            'type'    => 'slider',
                            'title'   => __('JQuery UI Slider Example 1', 'redux-framework-demo'),
                            'desc'    => __('JQuery UI slider description. Min: 1, max: 500, step: 3, default value: 45', 'redux-framework-demo'),
                            "default" => "45",
                            "min"     => "1",
                            "step"    => "3",
                            "max"     => "500",
                    ),

                    array(
                            'id'      => 'slider2',
                            'type'    => 'slider',
                            'title'   => __('JQuery UI Slider Example 2 w/ Steps (5)', 'redux-framework-demo'),
                            'desc'    => __('JQuery UI slider description. Min: 0, max: 300, step: 5, default value: 75', 'redux-framework-demo'),
                            "default" => "75",
                            "min"     => "0",
                            "step"    => "5",
                            "max"     => "300",
                    ),
                    array(
                            'id'      => 'spinner1b',
                            'type'    => 'spinner',
                            'title'   => __('JQuery UI Spinner Example 1', 'redux-framework-demo'),
                            'desc'    => __('JQuery UI spinner description. Min:20, max: 100, step:20, default value: 40', 'redux-framework-demo'),
                            "default" => "40",
                            "min"     => "20",
                            "step"    => "20",
                            "max"     => "100",
                    ),

                    array(
                            'id'       => 'switch-on',
                            'type'     => 'switch',
                            'title'    => __('Switch On', 'redux-framework-demo'),
                            'subtitle' => __('Look, it\'s on!', 'redux-framework-demo'),
                            "default"  => 1,
                    ),

                    array(
                            'id'       => 'switch-off',
                            'type'     => 'switch',
                            'title'    => __('Switch Off', 'redux-framework-demo'),
                            'subtitle' => __('Look, it\'s on!', 'redux-framework-demo'),
                            "default"  => 0,
                    ),


            )
    );
//*
    $boxSections[ ] = array(
            'title'  => __('Home Layout', 'redux-framework-demo'),
            'desc'   => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'redux-framework-demo'),
            'icon'   => 'el-icon-home',
            'fields' => array(
                    array(
                            "id"       => "homepage_blocks",
                            "type"     => "sorter",
                            "title"    => "Homepage Layout Manager",
                            "desc"     => "Organize how you want the layout to appear on the homepage",
                            "compiler" => 'true',
                            'required' => array('layout', '=', '1'),
                            'options'  => array(
                                    "enabled"  => array(
                                            "placebo"    => "placebo", //REQUIRED!
                                            "highlights" => "Highlights",
                                            "slider"     => "Slider",
                                            "staticpage" => "Static Page",
                                            "services"   => "Services"
                                    ),
                                    "disabled" => array(
                                            "placebo" => "placebo", //REQUIRED!
                                    ),
                            ),
                    ),
                    array(
                            'id'       => 'slides',
                            'type'     => 'slides',
                            'title'    => __('Slides Options', 'redux-framework-demo'),
                            'subtitle' => __('Unlimited slides with drag and drop sortings.', 'redux-framework-demo'),
                            'desc'     => __('This field will store all slides values into a multidimensional array to use into a foreach loop.', 'redux-framework-demo')
                    ),
                    array(
                            'id'          => 'slides',
                            'type'        => 'slides',
                            'title'       => __('Slides Options', 'redux-framework-demo'),
                            'subtitle'    => __('Unlimited slides with drag and drop sortings.', 'redux-framework-demo'),
                            'options'     => array(
                                    'flash'              => 'flash',
                                    'bounce'             => 'bounce',
                                    'shake'              => 'shake',
                                    'tada'               => 'tada',
                                    'wobble'             => 'wobble',
                                    'pulse'              => 'pulse',
                                    'flip'               => 'flip',
                                    'flipInX'            => 'flipInX',
                                    'flipOutX'           => 'flipOutX',
                                    'flipInY'            => 'flipInY',
                                    'flipOutY'           => 'flipOutY',
                                    'fadeIn'             => 'fadeIn',
                                    'fadeInUp'           => 'fadeInUp',
                                    'fadeInDown'         => 'fadeInDown',
                                    'fadeInLeft'         => 'fadeInLeft',
                                    'fadeInRight'        => 'fadeInRight',
                                    'fadeInUpBig'        => 'fadeInUpBig',
                                    'fadeInDownBig'      => 'fadeInDownBig',
                                    'fadeInLeftBig'      => 'fadeInLeftBig',
                                    'fadeInRightBig'     => 'fadeInRightBig',
                                    'fadeOut'            => 'fadeOut',
                                    'fadeOutUp'          => 'fadeOutUp',
                                    'fadeOutDown'        => 'fadeOutDown',
                                    'fadeOutLeft'        => 'fadeOutLeft',
                                    'fadeOutRight'       => 'fadeOutRight',
                                    'fadeOutUpBig'       => 'fadeOutUpBig',
                                    'fadeOutDownBig'     => 'fadeOutDownBig',
                                    'fadeOutLeftBig'     => 'fadeOutLeftBig',
                                    'fadeOutRightBig'    => 'fadeOutRightBig',
                                    'slideInDown'        => 'slideInDown',
                                    'slideInLeft'        => 'slideInLeft',
                                    'slideInRight'       => 'slideInRight',
                                    'slideOutUp'         => 'slideOutUp',
                                    'slideOutLeft'       => 'slideOutLeft',
                                    'slideOutRight'      => 'slideOutRight',
                                    'bounceIn'           => 'bounceIn',
                                    'bounceInDown'       => 'bounceInDown',
                                    'bounceInUp'         => 'bounceInUp',
                                    'bounceInLeft'       => 'bounceInLeft',
                                    'bounceInRight'      => 'bounceInRight',
                                    'bounceOut'          => 'bounceOut',
                                    'bounceOutDown'      => 'bounceOutDown',
                                    'bounceOutUp'        => 'bounceOutUp',
                                    'bounceOutLeft'      => 'bounceOutLeft',
                                    'bounceOutRight'     => 'bounceOutRight',
                                    'rotateIn'           => 'rotateIn',
                                    'rotateInDownLeft'   => 'rotateInDownLeft',
                                    'rotateInDownRight'  => 'rotateInDownRight',
                                    'rotateInUpLeft'     => 'rotateInUpLeft',
                                    'rotateInUpRight'    => 'rotateInUpRight',
                                    'rotateOut'          => 'rotateOut',
                                    'rotateOutDownLeft'  => 'rotateOutDownLeft',
                                    'rotateOutDownRight' => 'rotateOutDownRight',
                                    'rotateOutUpLeft'    => 'rotateOutUpLeft',
                                    'rotateOutUpRight'   => 'rotateOutUpRight',
                                    'lightSpeedIn'       => 'lightSpeedIn',
                                    'lightSpeedOut'      => 'lightSpeedOut',
                                    'hinge'              => 'hinge',
                                    'rollIn'             => 'rollIn',
                                    'rollOut'            => 'rollOut'
                            ),
                            'placeholder' => array(
                                    'title'       => "This is the title",
                                    'description' => "Description here",
                                    'url'         => "Link",
                                    'select'      => "Select an Animation",
                            ),
                            // 'select2' => array() // Select 2 options
                            'desc'        => __('This field will store all slides values into a multidimensional array to use into a foreach loop.', 'redux-framework-demo')
                    ),
                    array(
                            'id'       => 'presets',
                            'type'     => 'image_select',
                            'presets'  => true,
                            'title'    => __('Preset', 'redux-framework-demo'),
                            'subtitle' => __('This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework-demo'),
                            'default'  => 0,
                            'desc'     => __('This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework-demo'),
                            'options'  => array(
                                    '1' => array('alt' => 'Preset 1', 'img' => ReduxFramework::$_url . '../sample/presets/preset1.png', 'presets' => array('switch-on' => 1, 'switch-off' => 1, 'switch-custom' => 1)),
                                    '2' => array('alt' => 'Preset 2', 'img' => ReduxFramework::$_url . '../sample/presets/preset2.png', 'presets' => '{"slider1":"1", "slider2":"0", "switch-on":"0"}'),
                            ),
                    ),
                    array(
                            'id'          => 'typography6',
                            'type'        => 'typography',
                            'title'       => __('Typography', 'redux-framework-demo'),
                            //'compiler'=>true, // Use if you want to hook in your own CSS compiler
                            'google'      => true, // Disable google fonts. Won't work if you haven't defined your google api key
                            'font-backup' => true, // Select a backup non-google font in addition to a google font
                            //'font-style'=>false, // Includes font-style and weight. Can use font-style or font-weight to declare
                            //'subsets'=>false, // Only appears if google is true and subsets not set to false
                            //'font-size'=>false,
                            //'line-height'=>false,
                            //'word-spacing'=>true, // Defaults to false
                            //'letter-spacing'=>true, // Defaults to false
                            //'color'=>false,
                            //'preview'=>false, // Disable the previewer
                            'all_styles'  => true, // Enable all Google Font style/weight variations to be added to the page
                            'output'      => array('h2.site-description'), // An array of CSS selectors to apply this font style to dynamically
                            'compiler'    => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                            'units'       => 'px', // Defaults to px
                            'subtitle'    => __('Typography option with each property can be called individually.', 'redux-framework-demo'),
                            'default'     => array(
                                    'color'       => "#333",
                                    'font-style'  => '700',
                                    'font-family' => 'Abel',
                                    'google'      => true,
                                    'font-size'   => '33px',
                                    'line-height' => '40px'),
                    ),
                    array(
                            'id'          => 'dovy_Font',
                            'type'        => 'typography',
                            'title'       => __('Typography', 'redux-framework-demo'),
                            //'compiler'=>true, // Use if you want to hook in your own CSS compiler
                            'google'      => true, // Disable google fonts. Won't work if you haven't defined your google api key
                            'font-backup' => true, // Select a backup non-google font in addition to a google font
                            //'font-style'=>false, // Includes font-style and weight. Can use font-style or font-weight to declare
                            //'subsets'=>false, // Only appears if google is true and subsets not set to false
                            //'font-size'=>false,
                            //'line-height'=>false,
                            //'word-spacing'=>true, // Defaults to false
                            //'letter-spacing'=>true, // Defaults to false
                            //'color'=>false,
                            //'preview'=>false, // Disable the previewer
                            'all_styles'  => true, // Enable all Google Font style/weight variations to be added to the page
                            'output'      => array('#reply-title'), // An array of CSS selectors to apply this font style to dynamically
                            'compiler'    => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                            'units'       => 'px', // Defaults to px
                            'subtitle'    => __('Typography option with each property can be called individually.', 'redux-framework-demo'),
                            'default'     => array(
                                    'color'       => "#333",
                                    'font-style'  => '700',
                                    'font-family' => 'Abel',
                                    'google'      => true,
                                    'font-size'   => '33px',
                                    'line-height' => '40px'),
                    ),
            ),
    );
//*/

    $metaboxes = array();

    $metaboxes[ ]   = array(
            'id'         => 'demo-layout',
            'title'      => __('Cool Options', 'redux-framework-demo'),
            'post_types' => array('page', 'post'),
            'position'   => 'normal', // normal, advanced, side
            'priority'   => 'high', // high, core, default, low
            //'sidebar' => false, // enable/disable the sidebar in the normal/advanced positions
            'sections'   => $boxSections
    );
    $boxSections    = array();
    $boxSections[ ] = array(
      //'title' => __('Home Settings', 'redux-framework-demo'),
      //'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'redux-framework-demo'),
      'icon_class' => 'icon-large',
      'icon'       => 'el-icon-home',
      'fields'     => array(
              array(
                      'title'      => __('Layout', 'redux-framework-demo'),
                      'desc'       => __('Select main content and sidebar arrangement. Choose between 1, 2 or 3 column layout.', 'redux-framework-demo'),
                      'id'         => 'layout',
                      'default'    => 1,
                      'type'       => 'image_select',
                      'customizer' => array(),
                      'options'    => array(
                              0 => ReduxFramework::$_url . 'assets/img/1c.png',
                              1 => ReduxFramework::$_url . 'assets/img/2cr.png',
                              2 => ReduxFramework::$_url . 'assets/img/2cl.png',
                              3 => ReduxFramework::$_url . 'assets/img/3cl.png',
                              4 => ReduxFramework::$_url . 'assets/img/3cr.png',
                              5 => ReduxFramework::$_url . 'assets/img/3cm.png',
                      )
              ),
              array(
                      'id'      => 'sidebar',
                      'title'   => __('Sidebar', 'fusion-framework'),
                      'desc'    => 'Please select the sidebar you would like to display on this page. Note: You must first create the sidebar under Appearance > Widgets.',
                      'type'    => 'select',
                      'data'    => 'sidebars',
                      'default' => 'None',
              ),
      )
    );

    $metaboxes[ ] = array(
            'id'         => 'demo-layout2',
            //'title' => __('Cool Options', 'redux-framework-demo'),
            'post_types' => array('page', 'post', 'acme_product'),
            'position'   => 'side', // normal, advanced, side
            'priority'   => 'high', // high, core, default, low
            'sections'   => $boxSections
    );


    $page_options    = array();
    $page_options[ ] = array(
      //'title'         => __('General Settings', 'redux-framework-demo'),
      'icon_class' => 'icon-large',
      'icon'       => 'el-icon-home',
      'fields'     => array(
              array(
                      'id'      => 'sidebar',
                      'title'   => __('Sidebar', 'fusion-framework'),
                      'desc'    => 'Please select the sidebar you would like to display on this page. Note: You must first create the sidebar under Appearance > Widgets.',
                      'type'    => 'select',
                      'data'    => 'sidebars',
                      'default' => 'None',
              ),
      ),
    );

    $metaboxes[ ] = array(
            'id'         => 'page-options',
            'title'      => __('Page Options', 'fusion-framework'),
            'post_types' => array('page', 'post', 'acme_product'),
            'position'   => 'side', // normal, advanced, side
            'priority'   => 'high', // high, core, default, low
            'sidebar'    => false, // enable/disable the sidebar in the normal/advanced positions
            'sections'   => $page_options,
    );


    // Kind of overkill, but ahh well.  ;)
    //$metaboxes = apply_filters( 'your_custom_redux_metabox_filter_here', $metaboxes );

    return $metaboxes;
  }

  add_action('redux/metaboxes/' . $redux_opt_name . '/boxes', 'redux_add_metaboxes');
endif;


// The loader will load all of the extensions automatically.
// Alternatively you can run the include/init statements below.
require_once(dirname(__FILE__) . '/loader.php');