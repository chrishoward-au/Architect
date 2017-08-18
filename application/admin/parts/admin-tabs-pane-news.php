<?php
	/**
	 * Created by PhpStorm.
	 * User: chrishoward
	 * Date: 18/8/17
	 * Time: 12:36 PM
	 */

	echo ' <div class="tabs-pane " id="latest">
                  <h2>' . __('Latest News') . '</h2>
                  <div class="arc-info-boxes">
                    <div class="arc-info col1">';
	include_once(ABSPATH . WPINC . '/feed.php');

	//      add_filter( 'wp_feed_cache_transient_lifetime' , 'return_10' );
	$rss = fetch_feed('http://pizazzwp.com/category/architect/feed');
	//      remove_filter( 'wp_feed_cache_transient_lifetime' , 'return_10' );
	//      var_dump($rss);
	if (!is_wp_error($rss))  // Checks that the object is created correctly
		// Figure out how many total items there are, but limit it to 5.
	{
		$maxitems = $rss->get_item_quantity(5);

		// Build an array of all the items, starting with element 0 (first element).
		$rss_items = $rss->get_items(0, $maxitems);


		echo '<div class="postbox pzwp_blog" style="width:68%;float:left;">
                                      <h3 class="handle" style="line-height:30px;padding-left:10px;">Latest Architect News</h3>
                                      <ul class="inside">';
		if ($maxitems == 0) {
			echo '<li>No items.</li>';
		}
		else // Loop through each feed item and display each item as a hyperlink.
		{
			foreach ($rss_items as $item) :
				echo '<li>
                                  <h4 style="font-size:15px;"><a href=' . esc_url($item->get_permalink()) . '
                                                                 title=' . esc_html($item->get_title()) . '
                                                                 target=_blank>
                                      ' . esc_html($item->get_title()) . '</a></h4>

                                  <p style="line-height:0;font-style:italic">' . $item->get_date('j F Y') . '</p>

                                  <p>' . $item->get_description() . '<a
                                      href="' . esc_url($item->get_permalink()) . '" target=_blank>
                                      Continue reading</a></p>
                                </li>';
			endforeach;
		}

		echo '     </ul>
                      </div>';
	}
	else {
		echo "There was a problem accessing the news feed. As WP caches feeds for 12 hours, you won't be able to check again for a while.";
	}

	echo '</div>
                  </div>
                </div>
';