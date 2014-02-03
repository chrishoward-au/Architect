<?php

global $wpsf_settings;

// General Settings section
//$wpsf_settings[] = array(
//    'section_id' => 'general',
//    'section_title' => 'General Settings',
//    'section_description' => 'Some intro description about this section.',
//    'section_order' => 5,
//    'fields' => array(
//        array(
//            'id' => 'text',
//            'title' => 'Text',
//            'desc' => 'This is a description.',
//            'type' => 'text',
//            'std' => 'This is std'
//        ),
//        array(
//            'id' => 'textarea',
//            'title' => 'Textarea',
//            'desc' => 'This is a description.',
//            'type' => 'textarea',
//            'std' => 'This is std'
//        ),
//        array(
//            'id' => 'select',
//            'title' => 'Select',
//            'desc' => 'This is a description.',
//            'type' => 'select',
//            'std' => 'green',
//            'choices' => array(
//                'red' => 'Red',
//                'green' => 'Green',
//                'blue' => 'Blue'
//            )
//        ),
//        array(
//            'id' => 'radio',
//            'title' => 'Radio',
//            'desc' => 'This is a description.',
//            'type' => 'radio',
//            'std' => 'green',
//            'choices' => array(
//                'red' => 'Red',
//                'green' => 'Green',
//                'blue' => 'Blue'
//            )
//        ),
//        array(
//            'id' => 'checkbox',
//            'title' => 'Checkbox',
//            'desc' => 'This is a description.',
//            'type' => 'checkbox',
//            'std' => 1
//        ),
//        array(
//            'id' => 'checkboxes',
//            'title' => 'Checkboxes',
//            'desc' => 'This is a description.',
//            'type' => 'checkboxes',
//            'std' => array(
//                'red',
//                'blue'
//            ),
//            'choices' => array(
//                'red' => 'Red',
//                'green' => 'Green',
//                'blue' => 'Blue'
//            )
//        ),
//        array(
//            'id' => 'color',
//            'title' => 'Color',
//            'desc' => 'This is a description.',
//            'type' => 'color',
//            'std' => '#ffffff'
//        ),
//        array(
//            'id' => 'file',
//            'title' => 'File',
//            'desc' => 'This is a description.',
//            'type' => 'file',
//            'std' => ''
//        ),
//        array(
//            'id' => 'editor',
//            'title' => 'Editor',
//            'desc' => 'This is a description.',
//            'type' => 'editor',
//            'std' => ''
//        )
//    )
//);

// Architect Defaults
$wpsf_settings[] = array(
    'section_id' => 'architect_class_defaults',
    'section_title' => 'Class Defaults',
    'section_order' => 10,
    'fields' => array(

      // TITLES
      array(
              'id' => 'entry-title-title',
              'title' => 'Titles',
              'type' => 'separator',
      ),
      array(
              'id' => 'entry-title-defaults',
              'title' => 'Entry title defaults',
              'type' => 'textarea',
              'std' => 'line-height:1.2;text-decoration:none;'
      ),
      array(
              'id' => 'entry-title-class',
              'title' => 'Classes',
              'type' => 'text',
              'std' => '.entry-title, .entry-title a'
      ),

      // TITLES HOVER
      array(
              'id' => 'entry-title-hover-defaults',
              'title' => 'Linked entry title hover defaults',
              'type' => 'textarea',
              'std' => 'text-decoration:underline;'
      ),
      array(
              'id' => 'entry-title-hover-class',
              'title' => 'Classes',
              'type' => 'text',
              'std' => '.entry-title a:hover'
      ),
      // Entry
      array(
              'id' => 'content-title',
              'title' => 'Content',
              'type' => 'separator',
      ),
      array(
              'id' => 'entry-defaults',
              'title' => 'Entry defaults',
              'type' => 'textarea',
              'std' => ''
      ),
      array(
              'id' => 'entry-class',
              'title' => 'Classes',
              'type' => 'text',
              'std' => '.hentry'
      ),
          // EXCERPTS
          array(
                  'id' => 'entry-excerpt-defaults',
                  'title' => 'Entry excerpt defaults',
                  'type' => 'textarea',
                  'std' => ''
          ),
          array(
                  'id' => 'entry-excerpt-class',
                  'title' => 'Classes',
                  'type' => 'text',
                  'std' => '.entry-excerpt'
          ),

          // CONTENT
          array(
                  'id' => 'entry-content-defaults',
                  'title' => 'Entry content defaults',
                  'type' => 'textarea',
                  'std' => ''
          ),
          array(
                  'id' => 'entry-content-class',
                  'title' => 'Classes',
                  'type' => 'text',
                  'std' => '.entry-content'
          ),

          // CONTENT LINKS
          array(
                  'id' => 'entry-content-links-defaults',
                  'title' => 'Entry content links defaults',
                  'type' => 'textarea',
                  'std' => 'text-decoration:none;'
          ),
          array(
                  'id' => 'entry-content-links-class',
                  'title' => 'Classes',
                  'type' => 'text',
                  'std' => '.entry-content a, .entry-excerpt a'
          ),

          // CONTENT LINKS HOVER
          array(
                  'id' => 'entry-content-links-hover-defaults',
                  'title' => 'Entry content links hover defaults',
                  'type' => 'textarea',
                  'std' => 'text-decoration:underline;'
          ),
          array(
                  'id' => 'entry-content-links-hover-class',
                  'title' => 'Classes',
                  'type' => 'text',
                  'std' => '.entry-content a:hover, .entry-excerpt a:hover'
          ),
          // READ MORE
          array(
                  'id' => 'entry-readmore-defaults',
                  'title' => 'Entry read more defaults',
                  'type' => 'textarea',
                  'std' => 'text-decoration:none;'
          ),
          array(
                  'id' => 'entry-readmore-class',
                  'title' => 'Classes',
                  'type' => 'text',
                  'std' => 'a.more-link'
          ),

          // READ MORE HOVER
          array(
                  'id' => 'entry-readmore-hover-defaults',
                  'title' => 'Entry read more hover defaults',
                  'type' => 'textarea',
                  'std' => 'text-decoration:underline;'
          ),
          array(
                  'id' => 'entry-readmore-hover-class',
                  'title' => 'Classes',
                  'type' => 'text',
                  'std' => 'a.more-link-hover'
          ),

          // IMAGE
          array(
                  'id' => 'image-title',
                  'title' => 'Images',
                  'type' => 'separator',
          ),
          array(
                  'id' => 'entry-image-defaults',
                  'title' => 'Entry image defaults',
                  'type' => 'textarea',
                  'std' => ''
          ),
          array(
                  'id' => 'entry-image-class',
                  'title' => 'Classes',
                  'type' => 'text',
                  'std' => '.entry-thumbnail .attachment-post-thumbnail, .entry-thumbnail .wp-post-image'
          ),
          // IMAGE CAPTIONS
          array(
                  'id' => 'thumb-image-caption-defaults',
                  'title' => 'Entry image caption defaults',
                  'type' => 'textarea',
                  'std' => 'font-size:11px;line-height:1.1'
          ),
          array(
                  'id' => 'thumb-image-caption-class',
                  'title' => 'Classes',
                  'type' => 'text',
                  'std' => '.entry-thumbnail figcaption.caption, figcaption.caption'
          ),

          // Meta
          array(
                  'id' => 'meta-title',
                  'title' => 'Meta',
                  'type' => 'separator',
          ),
          array(
                  'id' => 'entry-meta-defaults',
                  'title' => 'Entry meta defaults',
                  'type' => 'textarea',
                  'std' => ''
          ),
          array(
                  'id' => 'entry-meta-class',
                  'title' => 'Classes',
                  'type' => 'text',
                  'std' => '.entry-meta'
          ),
          // Meta links
          array(
                  'id' => 'entry-meta-links-defaults',
                  'title' => 'Entry meta links defaults',
                  'type' => 'textarea',
                  'std' => 'text-decoration:none;'
          ),
          array(
                  'id' => 'entry-meta-links-class',
                  'title' => 'Classes',
                  'type' => 'text',
                  'std' => '.entry-meta a'
          ),
          // Meta links hover
          array(
                  'id' => 'entry-meta-links-hover-defaults',
                  'title' => 'Entry meta links hover defaults',
                  'type' => 'textarea',
                  'std' => 'text-decoration:underline;'
          ),
          array(
                  'id' => 'entry-meta-links-hover-class',
                  'title' => 'Classes',
                  'type' => 'text',
                  'std' => '.entry-meta a:hover'
          ),
          // OTHER
          array(
                  'id' => 'other-title',
                  'title' => 'Others',
                  'type' => 'separator',
          ),
          // Panels
          array(
                  'id' => 'panels-defaults',
                  'title' => 'Panels defaults',
                  'type' => 'textarea',
                  'std' => ''
          ),
          array(
                  'id' => 'panels-class',
                  'title' => 'Classes',
                  'type' => 'readonly',
                  'std' => '.pzarc-panels'
          ),
          // Components
          array(
                  'id' => 'components-group-defaults',
                  'title' => 'Components group defaults',
                  'type' => 'textarea',
                  'std' => ''
          ),
          array(
                  'id' => 'components-group-class',
                  'title' => 'Classes',
                  'type' => 'readonly',
                  'std' => '.pzarc-components'
          ),

        )
);
?>
