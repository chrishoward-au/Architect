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
      //TODO: Need to get a way to always wrap components in pzarc-compenents div.Problem is...dev has to create definition correctly.
      $panel_def[ 'components-open' ]  = '<article id="post-{{postid}}" class="block-type-content post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{categories}} {{tags}} {{pzclasses}}">';
      $panel_def[ 'components-close' ] = '</article>';
      $panel_def[ 'postlink' ]         = '<a href="{{permalink}}" title="{{title}}">';
      $panel_def[ 'header' ]           = '<header class="entry-header">{{headerinnards}}</header>';
      $panel_def[ 'title' ]            = '<h1 class="entry-title">{{postlink}}{{title}}{{closepostlink}}</h1>';
      $panel_def[ 'meta1' ]            = '<div class="entry-meta entry-meta1">{{meta1innards}}</div>';
      $panel_def[ 'meta2' ]            = '<div class="entry-meta entry-meta2">{{meta2innards}}</div>';
      $panel_def[ 'meta3' ]            = '<div class="entry-meta entry-meta3">{{meta3innards}}</div>';
      $panel_def[ 'datetime' ]         = '<span class="entry-date"><a href="{{permalink}}"<time class="entry-date" datetime="{{datetime}}">{{fdatetime}}</time></span></a></span>';
      $panel_def[ 'categories' ]       = '<span class="categories-links">{{categorieslinks}}</span>';
      $panel_def[ 'tags' ]             = '<span class="tags-links">{{tagslinks}}</span>';
      $panel_def[ 'author' ]           = '<span class="byline"><span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span></span>';
      $panel_def[ 'email' ]            = '<span class="byline email"><span class="author vcard"><a class="url fn n" href="mailto:{{authoremail}}" title="Email {{authorname}}" rel="author">{{authoremail}}</a></span></span>';
      //     $panel_def[ 'image' ]       = '<figure class="entry-thumbnail {{incontent}}">{{postlink}}<img width="{{width}}" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}">{{closepostlink}}{{captioncode}}</figure>';
      $panel_def[ 'image' ]         = '<figure class="entry-thumbnail {{incontent}} {{centred}}">{{postlink}}{{image}}{{closelink}}{{captioncode}}</figure>';
      $panel_def[ 'bgimage' ]       = '<figure class="entry-bgimage pzarc-bg-image {{trim-scale}}">{{bgimage}}</figure>';
      $panel_def[ 'caption' ]       = '<figcaption class="caption">{{caption}}</figcaption>';
      $panel_def[ 'content' ]       = ' <div class="entry-content {{nothumb}}">{{image-in-content}}{{content}}</div>';
      $panel_def[ 'custom1' ]       = '<div class="entry-customfieldgroup entry-customfieldgroup-1">{{custom1innards}}</div>';
      $panel_def[ 'custom2' ]       = '<div class="entry-customfieldgroup entry-customfieldgroup-2">{{custom2innards}}</div>';
      $panel_def[ 'custom3' ]       = '<div class="entry-customfieldgroup entry-customfieldgroup-3">{{custom3innards}}</div>';
      $panel_def[ 'cfield' ]        = '<div class="entry-customfield entry-customfield-{{cfieldname}}">{{cfieldcontent}}</div>';
      $panel_def[ 'footer' ]        = '<footer class="entry-footer">{{footerinnards}}</footer>';
      $panel_def[ 'excerpt' ]       = ' <div class="entry-excerpt {{nothumb}}">{{image-in-content}}{{excerpt}}</div>';
      $panel_def[ 'feature' ]       = '{{feature}}';
      $panel_def[ 'editlink' ]      = '<span class="edit-link"><a class="post-edit-link" href="{{permalink}}" title="Edit post {{title}}">Edit</a></span>';
      $panel_def[ 'comments-link' ] = '<span class="comments-link"><a href="{{permalink}}/#comments" title="Comment on {{title}}">Comments: {{commentscount}}</a></span>';
//TODO This has to be changed back once we.if we use a link instead of theget thumnail
      //$panel_def[ 'image' ]        = '<img class="entry-image" src="{{image}}">';
      // Yes, WP themes (T13, T14 etc) actually link the date to the post, not the archive for the date. Maybe it's an SEO thing, but I'm going to remove it
      // $panel_def[ 'datetime' ]      = '<span class="date"><a href="{{permalink}}" title="{{title}}" rel="bookmark"><time class="entry-date" datetime="{{datetime}}">{{fdatetime}}</time></a></span>';
      // oops should be using this for featured image

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
//      $panel_def[ 'wrapper' ]       = '<div id="post-{{postid}}" class="pzarc-postexcerpt post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{category-categories}} {{tag-tags}}">{{wrapperinnards}}</div>';
//      $panel_def[ 'header' ]        = '<header class="entry-header">{{headerinnards}}</header>';
//      $panel_def[ 'meta1' ]         = '<div class="entry-meta entry-meta-1">{{meta1innards}}</div>';
//      $panel_def[ 'meta2' ]         = '<div class="entry-meta entry-meta-2">{{meta2innards}}</div>';
//      $panel_def[ 'meta3' ]         = '<div class="entry-meta entry-meta-3">{{meta3innards}}</div>';
//      $panel_def[ 'datetime' ]      = '<span class="date"><a href="http://localhost/wp-mba.dev/hello-world/" title="Permalink to Hello world!" rel="bookmark"><time class="entry-date" datetime="2013-10-08T15:04:20+00:00">October 8, 2013</time></a></span>';
//      $panel_def[ 'categories' ]    = '<span class="categories-links">{{categories}}</span>';
//      $panel_def[ 'categorylinks' ] = '<a href="{{categorylink}}" title="View all posts in {{categorynam}}" rel="category tag">{{categoryname}}</a>';
//      $panel_def[ 'tags' ]          = '<span class="tags-links">{{tags}}</span>';
//      $panel_def[ 'taglinks' ]      = '<a href="{{taglink}}" rel="tag">{{tag}}</a>';
//      $panel_def[ 'author' ]        = '<span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span>';
//      $panel_def[ 'edit' ]          = '<span class="edit-link"><a class="post-edit-link" href="http://localhost/wp-mba.dev/wp-admin/post.php?post={{postid}}&amp;action=edit">Edit</a></span>';
//      $panel_def[ 'featuredimage' ] = '<div class="entry-thumbnail"><img width="100%" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}">{{caption}}</div>';
//      $panel_def[ 'title' ]         = '<h2 class="entry-title">{{title}}</h2>';
//      $panel_def[ 'excerpt' ]       = ' <div class="entry-excerpt">{{excerpt}}</div>';
//      $panel_def[ 'custom1' ]       = '<div class="entry-customfield entry-customfield-1">{{custom1innards}}</div>';
//      $panel_def[ 'custom2' ]       = '<div class="entry-customfield entry-customfield-2">custom2innards}}</div>';
//      $panel_def[ 'custom3' ]       = '<div class="entry-customfield entry-customfield-3">{{custom3innards}}</div>';
//      $panel_def[ 'footer' ]        = '<footer class="entry-meta">{{footerinnards}}</footer>';
      $panel_def = arc_PanelDef_post::panel_def();

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

//      $panel_def[ 'wrapper' ]       = '<div id="post-{{postid}}" class="pzarc-feature post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{category-categories}} {{tag-tags}}">{{wrapperinnards}}</div>';
//      $panel_def[ 'header' ]        = '<header class="entry-header">{{headerinnards}}</header>';
//      $panel_def[ 'meta1' ]         = '<div class="entry-meta entry-meta-1">{{meta1innards}}</div>';
//      $panel_def[ 'meta2' ]         = '<div class="entry-meta entry-meta-2">{{meta2innards}}</div>';
//      $panel_def[ 'meta3' ]         = '<div class="entry-meta entry-meta-3">{{meta3innards}}</div>';
//      $panel_def[ 'datetime' ]      = '<span class="date"><a href="http://localhost/wp-mba.dev/hello-world/" title="Permalink to Hello world!" rel="bookmark"><time class="entry-date" datetime="2013-10-08T15:04:20+00:00">October 8, 2013</time></a></span>';
//      $panel_def[ 'categories' ]    = '<span class="categories-links">{{categories}}</span>';
//      $panel_def[ 'categorylinks' ] = '<a href="{{categorylink}}" title="View all posts in {{categorynam}}" rel="category tag">{{categoryname}}</a>';
//      $panel_def[ 'tags' ]          = '<span class="tags-links">{{tags}}</span>';
//      $panel_def[ 'taglinks' ]      = '<a href="{{taglink}}" rel="tag">{{tag}}</a>';
//      $panel_def[ 'author' ]        = '<span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span>';
//      $panel_def[ 'featuredimage' ] = '<div class="entry-thumbnail"><img width="{{width}}" height="{{height}}" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}"></div>';
//      $panel_def[ 'title' ]         = '<h2 class="entry-title">{{title}}</h2>';
//      $panel_def[ 'excerpt' ]       = ' <div class="entry-excerpt">{{excerpt}}</div>';
//      $panel_def[ 'body' ]          = ' <div class="entry-content">{{content}}</div>';
//      $panel_def[ 'custom1' ]       = '<div class="entry-customfield entry-customfield-1">{{custom1innards}}</div>';
//      $panel_def[ 'custom2' ]       = '<div class="entry-customfield entry-customfield-2">custom2innards}}</div>';
//      $panel_def[ 'custom3' ]       = '<div class="entry-customfield entry-customfield-3">{{custom3innards}}</div>';
//      $panel_def[ 'footer' ]        = '<footer class="entry-meta">{{footerinnards}}</footer>';
      $panel_def = arc_PanelDef_post::panel_def();

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
      $panel_def[ 'header' ]   = '<header class="entry-header">{{headerinnards}}</header>';
      $panel_def[ 'title' ]    = '<h3 class="entry-title">{{title}}</h3>';
      $panel_def[ 'excerpt' ]  = ' <div class="entry-excerpt">{{excerpt}}</div>';
      $panel_def[ 'content' ]  = ' <div class="entry-content">{{content}}</div>';
      $panel_def[ 'footer' ]   = '<footer class="entry-meta">{{footerinnards}}</footer>';
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





