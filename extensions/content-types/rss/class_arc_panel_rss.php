<?php

  /**
   * Project pizazzwp-architect.
   * File: class_arc_panel_rss.php
   * User: chrishoward
   * Date: 19/10/14
   * Time: 11:03 PM
   */
  class arc_Panel_RSS extends arc_Panel_Generic {

    // This one will need a lot of stuff!

    function get_title(&$post) {
      $this->data['title']['title'] = $post['title'];
    }

    public function get_content(&$post) {
      /** CONTENT */
      $this->data['content'] = $post['content'];
    }

    public function get_excerpt(&$post) {
      $this->data['excerpt'] = wp_html_excerpt($post['content'], 50, '...');
    }

    public function get_meta(&$post) {
      $meta_string = $this->toshow['meta1']['show'] ? $this->section['_panels_design_meta1-config'] : '';
      $meta_string .= $this->toshow['meta2']['show'] ? $this->section['_panels_design_meta2-config'] : '';
      $meta_string .= $this->toshow['meta3']['show'] ? $this->section['_panels_design_meta3-config'] : '';

      /** META */
      if (strpos($meta_string, '%id%') !== FALSE) {
        $this->data['meta']['id'] = $post['id'];
        $this->data['meta']['id'] .= ' blueprint=' . $this->build->blueprint['blueprint-id'];
      }
      if (strpos($meta_string, '%date%') !== FALSE) {
        $this->data['meta']['datetime'] = $post['date'];
//        $this->data[ 'meta' ][ 'fdatetime' ] = date_i18n(strip_tags($this->section[ '_panels_design_meta-date-format' ]), str_replace(',', ' ', strtotime(get_the_date())));
        $this->data['meta']['fdatetime'] = date_i18n(strip_tags($this->section['_panels_design_meta-date-format']), strtotime(str_replace(',', ' ', get_the_date())));
      }
//      if (strpos($meta_string, '%categories%') !== FALSE) {
////      $this->data['meta']['categorieslinks'] = get_the_category_list(', ');
//        $this->data['meta']['categories'] = pzarc_tax_string_list($post['categories'], 'category-', '', ' ');
//      }
    if (strpos($meta_string, '%tags%') !== false) {
//      $this->data['meta']['tagslinks'] = get_the_tag_list(null, ', ');
      $this->data['meta']['tags']      = pzarc_tax_string_list($post['tags'], 'tag-', '', ' ');
    }
//    if (strpos($meta_string, '%author%') !== false) {
//
//      $this->data['meta']['authorlink'] = get_author_posts_url(get_the_author_meta('ID'));
//      $this->data['meta']['authorname'] = sanitize_text_field(get_the_author_meta('display_name'));
//      $rawemail                         = sanitize_email(get_the_author_meta('user_email'));
//      $encodedmail                      = '';
//      for ($i = 0; $i < strlen($rawemail); $i++) {
//        $encodedmail .= "&#" . ord($rawemail[$i]) . ';';
//      }
//      $this->data['meta']['authoremail'] = $encodedmail;
//    }
//    if (!empty($this->section['_panels_design_avatar']) && $this->section['_panels_design_avatar'] !== 'none') {
//      if ($this->section['_panels_design_avatar'] === 'before') {
//        $this->data['meta']['avatarb'] = get_avatar(get_the_author_meta('ID'), (!empty($this->section['_panels_design_avatar-size']) ? $this->section['_panels_design_avatar-size'] : 96));
//      }
//      else {
//        $this->data['meta']['avatara'] = get_avatar(get_the_author_meta('ID'), (!empty($this->section['_panels_design_avatar-size']) ? $this->section['_panels_design_avatar-size'] : 96));
//      }
//    }
//    $this->data['meta']['comments-count'] = get_comments_number();
//
//    // Extract and find any custom taxonomies - i.e. preceded with ct:
//    if (strpos($meta_string, 'ct:') !== false) {
//      $this->data['meta']['custom'][1] = $this->toshow['meta1']['show'] ? pzarc_get_post_terms(get_the_id(), $this->section['_panels_design_meta1-config']) : '';
//      $this->data['meta']['custom'][2] = $this->toshow['meta2']['show'] ? pzarc_get_post_terms(get_the_id(), $this->section['_panels_design_meta2-config']) : '';
//      $this->data['meta']['custom'][3] = $this->toshow['meta3']['show'] ? pzarc_get_post_terms(get_the_id(), $this->section['_panels_design_meta3-config']) : '';
//    }
//  }
    }
	  function get_image(&$post)
	  {
		  $width  = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
		  $height = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ]);
		  $max_wh = $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_design_image-max-dimensions'];
		  switch ($this->build->blueprint['section_object'][1]->section['section-panel-settings'][ '_panels_settings_image-focal-point' ]) {
			  case 'scale':
				  $wh = 'width="' . $max_wh[ 'width' ] . '"';
				  break;
			  case 'scale_height':
				  $wh = 'height="' . $max_wh[ 'height' ] . '"';
				  break;
			  default:
			  case 'respect':
			  case 'centre':
			  case 'none':
				  $wh = 'width="' . $max_wh[ 'width' ] . '"';
				  $wh .= ' height="' . $max_wh[ 'height' ] . '"';
				  break;
		  }
		  $this->data[ 'image' ][ 'image' ]         = '<img '.$wh.' src="' . bfi_thumb($post[ 'image' ], array('width'  => $width,
		                                                                                                   'height' => $height)) . '" style="max-height:'.$max_wh['height'].'";>';
		  $this->data[ 'image' ][ 'original' ][ 0 ] = $post[ 'image' ];

	  }

	  function get_bgimage(&$post)
	  {
		  $width  = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'width' ]);
		  $height = (int)str_replace('px', '', $this->section[ '_panels_design_image-max-dimensions' ][ 'height' ]);
		  $max_wh = $this->build->blueprint['section_object'][1]->section['section-panel-settings']['_panels_design_image-max-dimensions'];
		  switch ($this->build->blueprint['section_object'][1]->section['section-panel-settings'][ '_panels_settings_image-focal-point' ]) {
			  case 'scale':
				  $wh = 'width="' . $max_wh[ 'width' ] . '"';
				  break;
			  case 'scale_height':
				  $wh = 'height="' . $max_wh[ 'height' ] . '"';
				  break;
			  default:
			  case 'respect':
			  case 'centre':
			  case 'none':
				  $wh = 'width="' . $max_wh[ 'width' ] . '"';
				  $wh .= ' height="' . $max_wh[ 'height' ] . '"';
				  break;
		  }
		  $this->data[ 'bgimage' ][ 'thumb' ]       = '<img ' . $wh . ' src="' . bfi_thumb($post[ 'image' ], array('width'  => $width,
		                                                                                                               'height' => $height,
		                                                                                                               'crop' =>'50x50x'.$this->build->blueprint['section_object'][1]->section['section-panel-settings'][ '_panels_settings_image-focal-point' ]
			  )) . '">';
		  $this->data[ 'image' ][ 'original' ][ 0 ] = $post[ 'image' ];
	  }

    public function get_miscellanary(&$post) {
      global $_architect_options;
      $this->data['inherit-hw-block-type'] = (!empty($_architect_options['architect_hw-content-class']) ? 'block-type-content ' : '');
      $this->data['postid']                = $post['id'];
//      $this->data['poststatus']            = get_post_status();
//      $this->data[ 'posttype' ]    = get_post_type();
      $this->data['posttype']    = 'rssfeed';
      $this->data['permalink']   = $post['permalink'];
//      $post_format               = get_post_format();
//      $this->data ['postformat'] = (empty($post_format) ? 'standard' : $post_format);
    }


    public function loop($section_no, &$architect, &$panel_class, $class) {
      parent::loop_from_array($section_no, $architect, $panel_class, $class);
    }

    public function get_nav_items($blueprints_navigator, &$arc_query, $nav_labels, $nav_title_len = 0) {
      return parent::get_nav_items_from_array($blueprints_navigator, $arc_query, $nav_labels, $nav_title_len);
    }

  }
