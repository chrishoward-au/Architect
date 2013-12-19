<?php


  function pzucd_celldef($def)
  {
    // Just a guide to available parts of a cell
    // We put these in an array because the cell layout is user defined, so we can't order them until we have it later in processing.
    //Somewhere we will need to define some basic build formulas, yes? Or we jsut going to make assumptions on what goes in innards?
    $celldefs[ 'null' ] = array(
      array('wrapper' => ''),
      array('header' => ''),
      array('meta1' => ''),
      array('meta2' => ''),
      array('meta3' => ''),
      array('datetime' => ''),
      array('categories' => ''), array('categorylinks' => ''),
      array('tags' => ''), array('taglinks' => ''),
      array('author' => ''),
      array('edit' => ''),
      array('featuredimage' => ''),
      array('title' => ''),
      array('body' => ''),
      array('excerpt' => ''),
      array('custom1' => ''),
      array('custom2' => ''),
      array('custom3' => ''),
      array('footer' => ''),

    );

    // should we add filters? e.g apply_filters('ucdtitle','{{title}}')
    $celldefs[ 'post' ]['wrapper'] = '<article id="post-{{postid}}" class="pzucd-{{classname}} post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{category-categories}} {{tag-tags}}">{{wrapperinnards}}</article>';

    $celldefs[ 'post' ]['header'] = '<header class="entry-header">{{headerinnards}}</header><!-- .entry-header -->';
    $celldefs[ 'post' ]['title'] = '<h1 class="entry-title">{{title}}</h1>';
    $celldefs[ 'post' ]['meta1'] = '<div class="entry-meta entry-meta-1">{{meta1innards}}</div><!-- .entry-meta 1 -->';
    $celldefs[ 'post' ]['meta2'] = '<div class="entry-meta entry-meta-2">{{meta2innards}}</div><!-- .entry-meta 2 -->';
    $celldefs[ 'post' ]['meta3'] = '<div class="entry-meta entry-meta-3">{{meta3innards}}</div><!-- .entry-meta 3 -->';
    $celldefs[ 'post' ]['datetime'] = '<span class="date"><a href="http://localhost/wp-mba.dev/hello-world/" title="Permalink to Hello world!" rel="bookmark"><time class="entry-date" datetime="2013-10-08T15:04:20+00:00">October 8, 2013</time></a></span>';
    $celldefs[ 'post' ]['categories'] = '<span class="categories-links">{{categories}}</span>';
    $celldefs[ 'post' ]['categorylinks'] = '<a href="{{categorylink}}" title="View all posts in {{categoryname}}" rel="category tag">{{categoryname}}</a>';
    $celldefs[ 'post' ]['tags'] = '<span class="tags-links">{{tags}}</span>';
    $celldefs[ 'post' ]['taglinks'] = '<a href="{{taglink}}" rel="tag">{{tag}}</a>';
    $celldefs[ 'post' ]['author'] = '<span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span>';
    $celldefs[ 'post' ]['edit'] = '<span class="edit-link"><a class="post-edit-link" href="http://localhost/wp-mba.dev/wp-admin/post.php?post={{postid}}&amp;action=edit">Edit</a></span>';

    $celldefs[ 'post' ]['featuredimage'] = '<div class="entry-thumbnail"><img width="{{width}}" height="{{height}}" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}"></div>';
    $celldefs[ 'post' ]['content'] = ' <div class="entry-content">{{content}}</div><!-- .entry-content -->';
    $celldefs[ 'post' ]['custom1'] = '<div class="entry-customfield entry-customfield-1">{{custom1innards}}</div><!-- .entry-custom 1 -->';
    $celldefs[ 'post' ]['custom2'] = '<div class="entry-customfield entry-customfield-2">custom2innards}}</div><!-- .entry-custom 2 -->';
    $celldefs[ 'post' ]['custom3'] = '<div class="entry-customfield entry-customfield-3">{{custom3innards}}</div><!-- .entry-custom 3 -->';
    $celldefs[ 'post' ]['footer'] = '<footer class="entry-meta">{{footerinnards}}</footer><!-- .entry-meta -->';

    $celldefs[ 'post' ]['image'] = '<img class="entry-image" src="{{image}}">';
    $celldefs[ 'post' ]['excerpt'] = '{{excerpt}}';






    $celldefs[ 'page' ] = array();

    $celldefs[ 'excerpt' ] = array(
      array('wrapper' => '<div id="post-{{postid}}" class="pzucd-postexcerpt post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{category-categories}} {{tag-tags}}">{{wrapperinnards}}</div>'),
      array('header' => '<header class="entry-header">{{headerinnards}}</header><!-- .entry-header -->'),
      array('meta1' => '<div class="entry-meta entry-meta-1">{{meta1innards}}</div><!-- .entry-meta 1 -->'),
      array('meta2' => '<div class="entry-meta entry-meta-2">{{meta2innards}}</div><!-- .entry-meta 2 -->'),
      array('meta3' => '<div class="entry-meta entry-meta-3">{{meta3innards}}</div><!-- .entry-meta 3 -->'),
      array('datetime' => '<span class="date"><a href="http://localhost/wp-mba.dev/hello-world/" title="Permalink to Hello world!" rel="bookmark"><time class="entry-date" datetime="2013-10-08T15:04:20+00:00">October 8, 2013</time></a></span>'),
      array('categories' => '<span class="categories-links">{{categories}}</span>'),
      array('categorylinks' => '<a href="{{categorylink}}" title="View all posts in {{categorynam}}" rel="category tag">{{categoryname}}</a>'),
      array('tags' => '<span class="tags-links">{{tags}}</span>'),
      array('taglinks' => '<a href="{{taglink}}" rel="tag">{{tag}}</a>'),
      array('author' => '<span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span>'),
      array('edit' => '<span class="edit-link"><a class="post-edit-link" href="http://localhost/wp-mba.dev/wp-admin/post.php?post={{postid}}&amp;action=edit">Edit</a></span>'),
      array('featuredimage' => '<div class="entry-thumbnail"><img width="{{width}}" height="{{height}}" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}"></div>'),
      array('title' => '<h2 class="entry-title">{{title}}</h2>'),
      array('excerpt' => ' <div class="entry-excerpt">{{excerpt}}</div><!-- .entry-excerpt -->'),
      array('custom1' => '<div class="entry-customfield entry-customfield-1">{{custom1innards}}</div><!-- .entry-custom 1 -->'),
      array('custom2' => '<div class="entry-customfield entry-customfield-2">custom2innards}}</div><!-- .entry-custom 2 -->'),
      array('custom3' => '<div class="entry-customfield entry-customfield-3">{{custom3innards}}</div><!-- .entry-custom 3 -->'),
      array('footer' => '<footer class="entry-meta">{{footerinnards}}</footer><!-- .entry-meta -->'),
    );

    $celldefs[ 'feature' ] = array(
      array('wrapper' => '<div id="post-{{postid}}" class="pzucd-feature post-{{postid}} post type-{{posttype}} status-{{poststatus}} format-{{postformat}} hentry {{category-categories}} {{tag-tags}}">{{wrapperinnards}}</div>'),
      array('header' => '<header class="entry-header">{{headerinnards}}</header><!-- .entry-header -->'),
      array('meta1' => '<div class="entry-meta entry-meta-1">{{meta1innards}}</div><!-- .entry-meta 1 -->'),
      array('meta2' => '<div class="entry-meta entry-meta-2">{{meta2innards}}</div><!-- .entry-meta 2 -->'),
      array('meta3' => '<div class="entry-meta entry-meta-3">{{meta3innards}}</div><!-- .entry-meta 3 -->'),
      array('datetime' => '<span class="date"><a href="http://localhost/wp-mba.dev/hello-world/" title="Permalink to Hello world!" rel="bookmark"><time class="entry-date" datetime="2013-10-08T15:04:20+00:00">October 8, 2013</time></a></span>'),
      array('categories' => '<span class="categories-links">{{categories}}</span>'),
      array('categorylinks' => '<a href="{{categorylink}}" title="View all posts in {{categorynam}}" rel="category tag">{{categoryname}}</a>'),
      array('tags' => '<span class="tags-links">{{tags}}</span>'),
      array('taglinks' => '<a href="{{taglink}}" rel="tag">{{tag}}</a>'),
      array('author' => '<span class="author vcard"><a class="url fn n" href="{{authorlink}}" title="View all posts by {{authorname}}" rel="author">{{authorname}}</a></span>'),
      array('featuredimage' => '<div class="entry-thumbnail"><img width="{{width}}" height="{{height}}" src="{{imgsrc}}" class="attachment-post-thumbnail wp-post-image" alt="{{alttext}}"></div>'),
      array('title' => '<h2 class="entry-title">{{title}}</h2>'),
      array('excerpt' => ' <div class="entry-excerpt">{{excerpt}}</div><!-- .entry-excerpt -->'),
      array('body' => ' <div class="entry-content">{{content}}</div><!-- .entry-content -->'),
      array('custom1' => '<div class="entry-customfield entry-customfield-1">{{custom1innards}}</div><!-- .entry-custom 1 -->'),
      array('custom2' => '<div class="entry-customfield entry-customfield-2">custom2innards}}</div><!-- .entry-custom 2 -->'),
      array('custom3' => '<div class="entry-customfield entry-customfield-3">{{custom3innards}}</div><!-- .entry-custom 3 -->'),
      array('footer' => '<footer class="entry-meta">{{footerinnards}}</footer><!-- .entry-meta -->'),
    );

    $celldefs[ 'media' ] = array(
      array('wrapper' => '<div id="galleryimage-{{postid}}" class="pzucd-gallery galleryimage-{{postid}} galleryimage">{{wrapperinnards}}</div>'),
      array('header' => '<header class="entry-header">{{headerinnards}}</header><!-- .entry-header -->'),
      array('title' => '<h3 class="entry-title">{{title}}</h3>'),
      array('body' => ' <div class="entry-content">{{content}}</div><!-- .entry-content -->'),
      array('footer' => '<footer class="entry-meta">{{footerinnards}}</footer><!-- .entry-meta -->'),
    );

    return $celldefs[$def];
  }







