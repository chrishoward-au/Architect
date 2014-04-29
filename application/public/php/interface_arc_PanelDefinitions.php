<?php

  /**
   * Interface arc_PanelDefinitions
   */
  interface arc_PanelDefinitions

  {
    static function panel_def();
  }

  // We put these in an array because the cell layout is user defined, so we can't order them until we have it later in processing.
  //Somewhere we will need to define some basic build formulas, yes? Or we jsut going to make assumptions on what goes in innards?

  // should we add filters? e.g apply_filters('arctitle','{{title}}')

  /**
   * Class arc_PanelDef_post
   */
  class arc_PanelDef_post implements arc_PanelDefinitions
  {

    static function panel_def()
    {
      $panel_def[ 'panel-open' ]    = '{{bgimagetl}}<article id="post-{{postid}}" class="block-type-content post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{category-categories}} {{tag-tags}} {{pzclasses}}">';
      $panel_def[ 'panel-close' ]   = '</article>{{bgimagebr}}';
      $panel_def[ 'postlink' ]      = '<a href="{{permalink}}" title="{{title}}">';
      $panel_def[ 'header' ]        = '<header class="entry-header">{{headerinnards}}</header><!-- .entry-header -->';
      $panel_def[ 'title' ]         = '<h1 class="entry-title">{{postlink}}{{title}}{{closepostlink}}</h1>';
      $panel_def[ 'meta1' ]         = '<div class="entry-meta entry-meta1">{{meta1innards}}</div><!-- .entry-meta 1 -->';
      $panel_def[ 'meta2' ]         = '<div class="entry-meta entry-meta2">{{meta2innards}}</div><!-- .entry-meta 2 -->';
      $panel_def[ 'meta3' ]         = '<div class="entry-meta entry-meta3">{{meta3innards}}</div><!-- .entry-meta 3 -->';
      $panel_def[ 'datetime' ]      = '<span class="date"><a href="{{permalink}}" title="{{title}}" rel="bookmark"><time class="entry-date" datetime="{{datetime}">{{fdatetime}}</time></a></span>';
      $panel_def[ 'categories' ]    = '<span class="categories-links">{{categories}}</span>';
      $panel_def[ 'categorylinks' ] = '<a href="{{categorylink}}" title="View all posts in {{categoryname}}" rel="category tag">{{categoryname}}</a>';
      $panel_def[ 'tags' ]          = '<span class="tags-links">{{tags}}</span>';
      $panel_def[ 'taglinks' ]      = '<a href="{{taglink}}" rel="tag">{{tag}}</a>';
      $panel_def[ 'author' ]        = '<span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span>';
      $panel_def[ 'edit' ]          = '<span class="edit-link"><a class="post-edit-link" href="' . site_url() . '/wp-admin/post.php?post={{postid}}&amp;action=edit">Edit</a></span>';
//oops should be using this for featured image
      $panel_def[ 'image' ]   = '<figure class="entry-thumbnail {{incontent}}">{{postlink}}<img width="{{width}}" src="{{image}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}">{{closepostlink}}{{captioncode}}</figure>';
      $panel_def[ 'caption' ] = '<figcaption class="caption">{{caption}}</figcaption>';
      $panel_def[ 'content' ] = ' <div class="entry-content {{nothumb}}">{{image-in-content}}{{content}}</div><!-- .entry-content -->';
      $panel_def[ 'custom1' ] = '<div class="entry-customfield entry-customfield-1">{{custom1innards}}</div><!-- .entry-custom 1 -->';
      $panel_def[ 'custom2' ] = '<div class="entry-customfield entry-customfield-2">custom2innards}}</div><!-- .entry-custom 2 -->';
      $panel_def[ 'custom3' ] = '<div class="entry-customfield entry-customfield-3">{{custom3innards}}</div><!-- .entry-custom 3 -->';
      $panel_def[ 'footer' ]  = '<footer class="entry-meta">{{footerinnards}}</footer><!-- .entry-meta -->';
//    $panel_def[ 'post' ]['image'] = '<img class="entry-image" src="{{image}}">';
      $panel_def[ 'excerpt' ] = ' <div class="entry-excerpt {{nothumb}}">{{image-in-content}}{{excerpt}}</div><!-- .entry-excerpt -->';

      return $panel_def;
    }
  }

  /**
   * Class arc_PanelDef_page
   */
  class arc_PanelDef_page implements arc_PanelDefinitions
  {

    static function panel_def()
    {
      $panel_def = arc_PanelDef_post::panel_def();

      return $panel_def;
    }
  }

  /**
   * Class arc_PanelDef_excerpt
   */
  class arc_PanelDef_excerpt implements arc_PanelDefinitions
  {

    static function panel_def()
    {
      $panel_def[ 'wrapper' ]       = '<div id="post-{{postid}}" class="pzarc-postexcerpt post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{category-categories}} {{tag-tags}}">{{wrapperinnards}}</div>';
      $panel_def[ 'header' ]        = '<header class="entry-header">{{headerinnards}}</header><!-- .entry-header -->';
      $panel_def[ 'meta1' ]         = '<div class="entry-meta entry-meta-1">{{meta1innards}}</div><!-- .entry-meta 1 -->';
      $panel_def[ 'meta2' ]         = '<div class="entry-meta entry-meta-2">{{meta2innards}}</div><!-- .entry-meta 2 -->';
      $panel_def[ 'meta3' ]         = '<div class="entry-meta entry-meta-3">{{meta3innards}}</div><!-- .entry-meta 3 -->';
      $panel_def[ 'datetime' ]      = '<span class="date"><a href="http://localhost/wp-mba.dev/hello-world/" title="Permalink to Hello world!" rel="bookmark"><time class="entry-date" datetime="2013-10-08T15:04:20+00:00">October 8, 2013</time></a></span>';
      $panel_def[ 'categories' ]    = '<span class="categories-links">{{categories}}</span>';
      $panel_def[ 'categorylinks' ] = '<a href="{{categorylink}}" title="View all posts in {{categorynam}}" rel="category tag">{{categoryname}}</a>';
      $panel_def[ 'tags' ]          = '<span class="tags-links">{{tags}}</span>';
      $panel_def[ 'taglinks' ]      = '<a href="{{taglink}}" rel="tag">{{tag}}</a>';
      $panel_def[ 'author' ]        = '<span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span>';
      $panel_def[ 'edit' ]          = '<span class="edit-link"><a class="post-edit-link" href="http://localhost/wp-mba.dev/wp-admin/post.php?post={{postid}}&amp;action=edit">Edit</a></span>';
      $panel_def[ 'featuredimage' ] = '<div class="entry-thumbnail"><img width="100%" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}">{{caption}}</div>';
      $panel_def[ 'title' ]         = '<h2 class="entry-title">{{title}}</h2>';
      $panel_def[ 'excerpt' ]       = ' <div class="entry-excerpt">{{excerpt}}</div><!-- .entry-excerpt -->';
      $panel_def[ 'custom1' ]       = '<div class="entry-customfield entry-customfield-1">{{custom1innards}}</div><!-- .entry-custom 1 -->';
      $panel_def[ 'custom2' ]       = '<div class="entry-customfield entry-customfield-2">custom2innards}}</div><!-- .entry-custom 2 -->';
      $panel_def[ 'custom3' ]       = '<div class="entry-customfield entry-customfield-3">{{custom3innards}}</div><!-- .entry-custom 3 -->';
      $panel_def[ 'footer' ]        = '<footer class="entry-meta">{{footerinnards}}</footer><!-- .entry-meta -->';

      return $panel_def;
    }
  }

  /**
   * Class arc_PanelDef_feature
   */
  class arc_PanelDef_feature implements arc_PanelDefinitions
  {

    static function panel_def()
    {

      $panel_def[ 'wrapper' ]       = '<div id="post-{{postid}}" class="pzarc-feature post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{category-categories}} {{tag-tags}}">{{wrapperinnards}}</div>';
      $panel_def[ 'header' ]        = '<header class="entry-header">{{headerinnards}}</header><!-- .entry-header -->';
      $panel_def[ 'meta1' ]         = '<div class="entry-meta entry-meta-1">{{meta1innards}}</div><!-- .entry-meta 1 -->';
      $panel_def[ 'meta2' ]         = '<div class="entry-meta entry-meta-2">{{meta2innards}}</div><!-- .entry-meta 2 -->';
      $panel_def[ 'meta3' ]         = '<div class="entry-meta entry-meta-3">{{meta3innards}}</div><!-- .entry-meta 3 -->';
      $panel_def[ 'datetime' ]      = '<span class="date"><a href="http://localhost/wp-mba.dev/hello-world/" title="Permalink to Hello world!" rel="bookmark"><time class="entry-date" datetime="2013-10-08T15:04:20+00:00">October 8, 2013</time></a></span>';
      $panel_def[ 'categories' ]    = '<span class="categories-links">{{categories}}</span>';
      $panel_def[ 'categorylinks' ] = '<a href="{{categorylink}}" title="View all posts in {{categorynam}}" rel="category tag">{{categoryname}}</a>';
      $panel_def[ 'tags' ]          = '<span class="tags-links">{{tags}}</span>';
      $panel_def[ 'taglinks' ]      = '<a href="{{taglink}}" rel="tag">{{tag}}</a>';
      $panel_def[ 'author' ]        = '<span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span>';
      $panel_def[ 'featuredimage' ] = '<div class="entry-thumbnail"><img width="{{width}}" height="{{height}}" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}"></div>';
      $panel_def[ 'title' ]         = '<h2 class="entry-title">{{title}}</h2>';
      $panel_def[ 'excerpt' ]       = ' <div class="entry-excerpt">{{excerpt}}</div><!-- .entry-excerpt -->';
      $panel_def[ 'body' ]          = ' <div class="entry-content">{{content}}</div><!-- .entry-content -->';
      $panel_def[ 'custom1' ]       = '<div class="entry-customfield entry-customfield-1">{{custom1innards}}</div><!-- .entry-custom 1 -->';
      $panel_def[ 'custom2' ]       = '<div class="entry-customfield entry-customfield-2">custom2innards}}</div><!-- .entry-custom 2 -->';
      $panel_def[ 'custom3' ]       = '<div class="entry-customfield entry-customfield-3">{{custom3innards}}</div><!-- .entry-custom 3 -->';
      $panel_def[ 'footer' ]        = '<footer class="entry-meta">{{footerinnards}}</footer><!-- .entry-meta -->';

      return $panel_def;
    }
  }

  /**
   * Class arc_PanelDef_image
   */
  class arc_PanelDef_image implements arc_PanelDefinitions
  {

    static function panel_def()
    {
      $panel_def[ 'wrapper' ]  = '{{bgimagetl}}<div id="galleryimage-{{postid}}" class="pzarc-{{classname}}  galleryimage-{{postid}} galleryimage {{pzclasses}}">{{wrapperinnards}}</div>{{bgimagebr}}';
      $panel_def[ 'header' ]   = '<header class="entry-header">{{headerinnards}}</header><!-- .entry-header -->';
      $panel_def[ 'title' ]    = '<h3 class="entry-title">{{title}}</h3>';
      $panel_def[ 'excerpt' ]  = ' <div class="entry-excerpt">{{excerpt}}</div><!-- .entry-excerpt -->';
      $panel_def[ 'content' ]  = ' <div class="entry-content">{{content}}</div><!-- .entry-content -->';
      $panel_def[ 'footer' ]   = '<footer class="entry-meta">{{footerinnards}}</footer><!-- .entry-meta -->';
      $panel_def[ 'postlink' ] = '<a href="{{permalink}}" title="{{title}}">';
      $panel_def[ 'image' ]    = '<figure class="entry-thumbnail {{incontent}}">{{postlink}}<img width="{{width}}" src="{{image}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}">{{closepostlink}}{{captioncode}}</figure>';
      // Need atrick to include caption to hide when responsive and caption is description
      $panel_def[ 'caption' ] = '<figcaption class="caption">{{caption}}</figcaption>';

      return $panel_def;
    }
  }

  /**
   * Class arc_PanelDef_gallery
   */
  class arc_PanelDef_gallery implements arc_PanelDefinitions
  {

    static function panel_def()
    {
      $panel_def = arc_PanelDef_image::panel_def();

      return $panel_def;
    }

  } // EOC





