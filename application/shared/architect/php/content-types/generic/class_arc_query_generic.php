<?php
/**
 * Project pizazzwp-architect.
 * File: class_arc_query_generic.php
 * User: chrishoward
 * Date: 20/10/14
 * Time: 2:12 AM
 */
//var_dump('MAKE NAV BIT');
  class arc_query_generic {

    public $query_options = array();
    public $build = array();
    public $criteria = array();

    function __construct($build,$criteria) {
      $this->build =$build;
      $this->criteria = $criteria;
    }

    public function build_custom_query_options($overrides)
    {

      // TODO: need to scrape this down to just a generic one for built in post types
      // Probably should make this extensible

      //build the new query
      $source = $this->build->blueprint[ '_blueprints_content-source' ];

      global $paged;

      //Paging parameters
      if ($this->build->blueprint[ '_blueprints_navigation' ] == 'pagination') {

        // This is meant ot be the magic tonic to make pagination work on static front page. Bah!! Didnt' for me - ever
        // Ah! It only doesn't work with Headway!
        if (get_query_var('paged')) {

          $paged = get_query_var('paged');

        } elseif (get_query_var('page')) {

          $paged = get_query_var('page');

        } else {

          $paged = 1;

        }

// TODO: WTF IS THIS?! Surely just some debugging left behind!
//        query_posts('posts_per_page=3&paged=' . $paged);

//        $this->query_options[ 'nopaging' ]       = false;
        $this->query_options[ 'posts_per_page' ] = $$this->criteria[ 'panels_to_show' ];
        $this->query_options[ 'pagination' ]     = true;
        $this->query_options[ 'paged' ]          = $paged;

      } else {

        $query[ 'nopaging' ]               = $this->criteria[ 'nopaging' ];
        $this->query_options[ 'posts_per_page' ] = $this->criteria[ 'panels_to_show' ];

        $this->query_options[ 'offset' ] = $this->criteria[ 'offset' ];
      }

      // these two break paging yes?
      $this->query_options[ 'ignore_sticky_posts' ] = $this->criteria[ 'ignore_sticky_posts' ];

      //Lot of work!
      //     pzdebug($criteria);

      // TODO: We're going to have to make this pluggable too! :P Probably with a loop?

      /** General content filters */
      $cat_ids = $this->criteria[ 'category__in' ];
//      var_dump(get_the_category(),is_category());

      // TODO: This doesn't work right yet
//      if ($this->build->blueprint[ '_content_general_sub-cats' ]  && is_category()) {
//        $current_cat = get_the_category();
//        $archive_cat = $current_cat->cat_ID;
//        $cat_kids = get_categories(array('child_of' => $archive_cat));
//
//        foreach ($cat_kids as $kid) {
//          $cat_ids[] = $kid->cat_ID;
//        }
//
//      }

      $this->query_options[ 'category__in' ]     = (!empty($this->criteria[ 'category__in' ]) ? $cat_ids : null);
      $this->query_options[ 'category__not_in' ] = (!empty($this->criteria[ 'category__in' ]) ? $this->criteria[ 'category__not_in' ] : null);

      $this->query_options[ 'tag__in' ]     = (!empty($this->criteria[ 'tag__in' ]) ? $this->criteria[ 'tag__in' ] : null);
      $this->query_options[ 'tag__not_in' ] = (!empty($this->criteria[ 'tag__in' ]) ? $this->criteria[ 'tag__not_in' ] : null);


      /** Custom taxonomies  */
      if (!empty($this->build->blueprint[ '_content_general_other-tax-tags' ])) {
        $this->query_options[ 'tax_query' ] = array(
            array(
                'taxonomy' => $this->build->blueprint[ '_content_general_other-tax' ],
                'field'    => 'slug',
                'terms'    => explode(',', $this->build->blueprint[ '_content_general_other-tax-tags' ]),
                'operator' => $this->build->blueprint[ '_content_general_tax-op' ]
            ),
        );
      }

      /** Specific content filters */

      $this->content_filters($source,$overrides);

      // Order. This is always set
      $this->query_options[ 'orderby' ] = $this->criteria[ 'orderby' ];
      $this->query_options[ 'order' ]   = $this->criteria[ 'order' ];


      // OVERRIDES
      // currently this is the only bit that really does anything
      if ($overrides) {

        $this->query_options[ 'post__in' ]       = explode(',', $overrides);
        $this->query_options[ 'posts_per_page' ] = count($this->query_options[ 'post__in' ]);
      }


    }

    /**
     *
     * Replace this in your own extensions if necessary
     *
     * @return WP_Query
     *
     */
    public function get_custom_query() {
// Get any existing copy of our transient data
      if ( !current_user_can( 'manage_options' ) && false === ( $custom_query = get_transient( 'pzarc_custom_query_'.$this->build->blueprint['_blueprints_short-name'] ) ) ) {
        // It wasn't there, so regenerate the data and save the transient
        $custom_query = new WP_Query($this->query_options);

        set_transient( 'pzarc_custom_query_'.$this->build->blueprint['_blueprints_short-name'], $custom_query, PZARC_TRANSIENTS_KEEP );
      } elseif (current_user_can( 'manage_options' )) {
        $custom_query = new WP_Query($this->query_options);
      }

      return $custom_query;
    }

    protected function content_filters($source,$overrides) {

      switch ($source) {

        case 'cpt':
          $this->query_options[ 'post_type' ] = $this->build->blueprint[ '_content_cpt_custom-post-type' ];
          break;



      }


    }

  }

