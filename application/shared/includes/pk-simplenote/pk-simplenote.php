<?php
/*
Plugin Name: PK Simplenote
Plugin URI: http://structurewebdev.com/
Description: Sandbox plugin created and used by Paul Kaiser for tutorials.
Author: Paul Kaiser
Version: 1.0
Author URI: http://paulekaiser.com/
http://premium.wpmudev.org/blog/using-wordpress-pointers-in-your-own-plugins/
*/
	
// Define our plugin's wrapper class
if ( !class_exists( "PKSimpleNote" ) )
{
	class PKSimpleNote
	{
		function PKSimpleNote() // Constructor
		{
			register_activation_hook( __FILE__, array($this, 'run_on_activate') );
			add_action('admin_init', array($this, 'init_admin'));

			// adds our plugin options page
			add_action('admin_menu', array($this, 'pk_simplenote_addoptions_page_fn'));
			
			// Adding Custom boxes (Meta boxes) to Write panels (for Post, Page, and Custom Post Types)
			add_action('add_meta_boxes', array($this, 'pk_simplenote_add_custom_box_fn'));
			add_action('save_post', array($this, 'pk_simplenote_save_postdata_fn'));
			
			// filter lets Simplenote add notes to content when desired
			add_filter( 'the_content', array( $this, 'pksimplenote_addnote' ) );
			
			// This adds scripts for ANY admin screen
			add_action( 'admin_enqueue_scripts', array( $this, 'pksimplenote_admin_scripts' ) );
		}
		
// == Script and CSS Enqueuing
		function pksimplenote_admin_scripts() {
			// You might of course have other scripts enqueued here,
			// for functionality other than WordPress Pointers.

			// WordPress Pointer Handling
			// find out which pointer ids this user has already seen
			$seen_it = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
			// at first assume we don't want to show pointers
			$do_add_script = false;
			
			// Handle our first pointer announcing the plugin's new settings screen.
			// check for dismissal of pksimplenote settings menu pointer 'pksn1'
			if ( ! in_array( 'pksn1', $seen_it ) ) {
				// flip the flag enabling pointer scripts and styles to be added later
				$do_add_script = true;
				// hook to function that will output pointer script just for pksn1
				add_action( 'admin_print_footer_scripts', array( $this, 'simplenote_pksn1_footer_script' ) );
			}
			
			// Handle our second pointer highlighting the note entry field on edit screen.
			// check for dismissal of pksimplenote note box pointer 'pksn2'
			if ( ! in_array( 'pksn2', $seen_it ) ) {
				// flip the flag enabling pointer scripts and styles to be added later
				$do_add_script = true;
				// hook to function that will output pointer script just for pksn2
				add_action( 'admin_print_footer_scripts', array( $this, 'simplenote_pksn2_footer_script' ) );
			}
			
			// now finally enqueue scripts and styles if we ended up with do_add_script == TRUE
			if ( $do_add_script ) {
				// add JavaScript for WP Pointers
				wp_enqueue_script( 'wp-pointer' );
				// add CSS for WP Pointers
				wp_enqueue_style( 'wp-pointer' );
			}
		}
		
		// Each pointer has its own function responsible for putting appropriate JavaScript into footer
		function simplenote_pksn1_footer_script() {
			// Build the main content of your pointer balloon in a variable
			$pointer_content = '<h3>New Simplenote Settings</h3>'; // Title should be <h3> for proper formatting.
			$pointer_content .= '<p>I hope you find Simplenote useful. You should probably <a href="';
			$pointer_content .= bloginfo( 'wpurl' );
			$pointer_content .= '/wp-admin/options-general.php?page=PKSimplenote_Options">check your settings</a> before using it.</p>';
			// In JavaScript below:
			// 1. "#menu-plugins" needs to be the unique id of whatever DOM element in your HTML you want to attach your pointer balloon to.
			// 2. "pksn1" needs to be the unique id, for internal use, of this pointer
			// 3. "position" -- edge indicates which horizontal spot to hang on to; align indicates how to align with element vertically
			?>
			<script type="text/javascript">// <![CDATA[
			jQuery(document).ready(function($) {
				/* make sure pointers will actually work and have content */
				if(typeof(jQuery().pointer) != 'undefined') {
					$('#menu-plugins').pointer({
						content: '<?php echo $pointer_content; ?>',
						position: {
							edge: 'left',
							align: 'center'
						},
						close: function() {
							$.post( ajaxurl, {
								pointer: 'pksn1',
								action: 'dismiss-wp-pointer'
							});
						}
					}).pointer('open');
				}
			});
			// ]]></script>
			<?php
		} // end simplenote_pksn1_footer_script()

		function simplenote_pksn2_footer_script() {
			// Build the main content of your pointer balloon in a variable
			$pointer_content = '<h3>Simplenote Entry field</h3>'; // Title should be <h3> for proper formatting.
			$pointer_content .= '<p>Enter any text here to add a simple note for this post or page.</p>';
			// In JavaScript below:
			// * "position" -- we use a different method, indicating:
			// ** What spot on the targeted element do we hang on to?
			// ** What spot on our pointer do we want hanging on to the targeted element?
			// Here we take the top left corner of our pointer and hang it by the bottom left corner of the entry field box.
			?>
			<script type="text/javascript">// <![CDATA[
			jQuery(document).ready(function($) {
				/* make sure pointers will actually work and have content */
				if(typeof(jQuery().pointer) != 'undefined') {
					$('#pksn-box').pointer({
						content: '<?php echo $pointer_content; ?>',
						position: {
							at: 'left bottom',
							my: 'left top'
						},
						close: function() {
							$.post( ajaxurl, {
								pointer: 'pksn2',
								action: 'dismiss-wp-pointer'
							});
						}
					}).pointer('open');
				}
			});
			// ]]></script>
			<?php
		} // end simplenote_pksn2_footer_script()

		
		
		
		
		
		


// == Options Field Drawing Functions for add_settings
		// Where do we want to show the note, relative to content?
		// show-where
		function draw_set_showwhere_fn()
		{
			$options = get_option('PKSimplenote_Options');
			$items = array(
							array('0', __("Do not show", 'pksimplenote'), __("Do not show notes automatically.", 'pksimplenote') ),
							array('1', __("Show before", 'pksimplenote'), __("Show note before content.", 'pksimplenote') ),
							array('2', __("Show after", 'pksimplenote'), __("Show note after content.", 'pksimplenote') )
							);
			foreach( $items as $item )
			{
				$checked = ($options['show-where'] == $item[0] ) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$item[0]' name='PKSimplenote_Options[show-where]' type='radio' /> $item[1] &mdash; $item[2]</label><br />";
			}
		}

// == Administration Init Stuff
		function init_admin()
		{
			// Register a new setting GROUP
			register_setting(
				'PKSimplenote_Options_Group',
				'PKSimplenote_Options');
			// Add SECTIONS to the setting group
			add_settings_section(
				'PKSimplenote_Options_ID',
				__('Simplenote Options', 'pksimplenote'),
				array($this, 'draw_overview'),
				'PKSimplenote_Page_Title');
			// Add FIELDS to the setting group's sections
			// Where do we want simplenotes to show in content?
			add_settings_field(
				'show-where',
				__('Show note where?', 'pksimplenote'),
				array($this, 'draw_set_showwhere_fn'),
				'PKSimplenote_Page_Title',
				'PKSimplenote_Options_ID');
		}

// == Administration Menus Stuff
		function pk_simplenote_addoptions_page_fn()
		{
			// Make sure we should be here
			if (!function_exists('current_user_can') || !current_user_can('manage_options') )
			return;
			// Add our plugin options page
			if ( function_exists( 'add_options_page' ) )
			{
				add_options_page(
					__('Simplenote Options Page', 'pksimplenote'),
					__('Simplenote', 'pksimplenote'),
					'manage_options',
					'PKSimplenote_Options',
					array( $this, 'pk_simplenote_drawoptions_fn' ) );
			}
		}
		
		// Show our Options page in Admin
		function pk_simplenote_drawoptions_fn()
		{
			$PKSimplenote_Options = get_option('PKSimplenote_Options');
			?>
			<div class="wrap">
				<?php screen_icon("options-general"); ?>
				<h2>Simplenote <?php echo $PKSimplenote_Options['version']; ?></h2>
				<form action="options.php" method="post">
				<?php
				settings_fields('PKSimplenote_Options_Group');
				do_settings_sections('PKSimplenote_Page_Title');
				?>
					<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'contentscheduler'); ?>" />
					</p>
				</form>
			</div>
			<?php
		}
		
		function draw_overview()
		{
			// This shows things under the title of our settings section
			echo "<p>";
			_e( 'Choose where to automatically insert note and whether to show pointers for this plugin.', 'pksimplenote' );
			echo "</p>\n";
		}
		
// == Set options to defaults - used during plugin activation
		function run_on_activate()
		{
			$options = get_option('PKSimplenote_Options');
			// Build an array of each option and its default setting
			$arr_defaults = array
			(
			    "version" => "1.0.0",
				"show-where" => "1",
			    "show-pointers" => "1"
			);
			// check to see if we need to set defaults
			if( !is_array( $options )  )
			{
				update_option('PKSimplenote_Options', $arr_defaults);
			}
		}
		
// == Functions for using Custom Controls / Panels
		function pk_simplenote_add_custom_box_fn()
		{
			// Add the box to Post write panels
		    add_meta_box( 'pksn-box', 
							__( 'Simplenote', 
							'pksimplenote' ), 
							array($this, 'draw_custom_box_fn'), 
							'post' );
		    // Add the box to Page write panels
		    add_meta_box( 'pksn-box', 
							__( 'Simplenote', 
							'pksimplenote' ), 
							array($this, 'draw_custom_box_fn'), 
							'page' );
			// Get a list of all custom post types
			$args = array(
				'public'   => true,
				'_builtin' => false
			); 
			$output = 'names'; // names or objects
			$operator = 'and'; // 'and' or 'or'
			$post_types = get_post_types( $args, $output, $operator );
			// Step through each public custom type and add the content scheduler box
			foreach ($post_types  as $post_type )
			{
				// echo '<p>'. $post_type. '</p>';
				add_meta_box( 'pksn-box',
								__( 'Simplenote',
								'pksimplenote' ),
								array( $this, 'draw_custom_box_fn'),
								$post_type );
			}
		}
		
		// Prints the box content
		function draw_custom_box_fn()
		{
			global $post;
			wp_nonce_field( 'pksimplenote_values', 'PKSimpleNote_noncename' );
			$note_string = ( get_post_meta( $post->ID, '_pksimplenote-note', true) );
			echo '<label for="pksimplenote-note">' . __("Note for this content:", 'pksimplenote' ) . '</label><br />';
			echo '<textarea rows="4" cols="80" id="pksimplenote-note" name="_pksimplenote-note" >';
			echo $note_string;
			echo "</textarea>\n";
		}
		
		// When the post is saved, saves our custom data
		function pk_simplenote_save_postdata_fn( $post_id )
		{
			// verify this came from our screen and with proper authorization,
			if( !empty( $_POST['PKSimpleNote_noncename'] ) )
			{
				if ( !wp_verify_nonce( $_POST['PKSimpleNote_noncename'], 'pksimplenote_values' ))
				{
					return $post_id;
				}
			}
			else
			{
				return $post_id;
			}
			// verify if this is an auto save routine. If it is our form has not been submitted, 
			// so we dont want to do anything
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			{
				return $post_id;
			}
			// Check permissions, whether we're editing a Page or a Post
			if ( 'page' == $_POST['post_type'] )
			{
				if ( !current_user_can( 'edit_page', $post_id ) )
				return $post_id;
			}
			else
			{
				if ( !current_user_can( 'edit_post', $post_id ) )
				return $post_id;
			}
			// OK, we're authenticated: we need to find and save the data
			$note_string = $_POST['_pksimplenote-note'];
			update_post_meta( $post_id, '_pksimplenote-note', $note_string );
			return true;
		}

// == FUNCTION TO ACTUALLY PRINT THE SIMPLENOTE CONTENT
		function pksimplenote_addnote( $the_content ) {
			// where should we put the note?
			$options = get_option('PKSimplenote_Options');
			if ( $options['show-where'] > 0 ) {
				// we know we'll print it somewhere
				if ( $options['show-where'] == 1 ) {
					// we'll show it before the content
					$the_content = $this->pksimplenote_getnote() . $the_content;
				} else {
					// we'll show it after the content
					$the_content = $the_content . $this->pksimplenote_getnote();
				}
			}
			return $the_content;
		}
		
		function pksimplenote_getnote() {
			global $post;
			$the_note = get_post_meta( $post->ID, '_pksimplenote-note', true );
			if( !$the_note ) {
				$the_note = "There is no note assigned to this post.";
			}
			return "<p style='border:1px solid #000;background:#FFFF7F;padding:8px;'>$the_note</p>\n";
		}	
	}
} // End Class

// Instantiating the Class
if (class_exists("PKSimpleNote")) {
	$PKSimpleNote = new PKSimpleNote();
}