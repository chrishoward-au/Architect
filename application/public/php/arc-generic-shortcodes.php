<?php
  /**
   * Created by PhpStorm.
   * User: chrishoward
   * Date: 14/1/18
   * Time: 7:17 PM
   */

  add_shortcode( 'arcdummytext', 'dummy_text' ); // A test shortcode
  add_shortcode( 'arc_hasmedia', 'has_media' );
  add_shortcode( 'arccf', 'arc_get_table_field_value' );

  function dummy_text() {
    $dummy_text = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Id interdum velit laoreet id donec ultrices tincidunt arcu. Facilisis magna etiam tempor orci eu. Ullamcorper sit amet risus nullam eget felis eget nunc. Id nibh tortor id aliquet lectus proin nibh nisl. Urna condimentum mattis pellentesque id nibh tortor id aliquet lectus. Convallis posuere morbi leo urna molestie at elementum eu facilisis. Augue interdum velit euismod in pellentesque massa placerat duis ultricies. Eleifend donec pretium vulputate sapien nec sagittis. Adipiscing commodo elit at imperdiet dui accumsan sit amet nulla. Volutpat sed cras ornare arcu. Et magnis dis parturient montes. Nibh ipsum consequat nisl vel pretium lectus. Nulla porttitor massa id neque aliquam vestibulum morbi. Nulla aliquet enim tortor at. Interdum posuere lorem ipsum dolor sit. Sem et tortor consequat id. Ullamcorper sit amet risus nullam eget felis eget. Interdum velit laoreet id donec ultrices. Diam phasellus vestibulum lorem sed risus ultricies tristique nulla aliquet.</p>';

    $dummy_text .= '<p>Sed faucibus turpis in eu mi. In nibh mauris cursus mattis molestie. Laoreet suspendisse interdum consectetur libero id faucibus nisl tincidunt eget. Auctor urna nunc id cursus metus aliquam eleifend mi. Ullamcorper velit sed ullamcorper morbi tincidunt ornare massa eget egestas. Diam maecenas ultricies mi eget mauris pharetra et. Vel orci porta non pulvinar neque laoreet suspendisse interdum consectetur. Quis risus sed vulputate odio ut enim blandit. Ut pharetra sit amet aliquam. Placerat duis ultricies lacus sed. Non pulvinar neque laoreet suspendisse interdum consectetur libero id. Morbi tincidunt augue interdum velit euismod in pellentesque massa placerat.</p>';

    return $dummy_text;
  }


  /**
   * @param null $atts
   * @param null $content
   *
   * @return string
   */
  function has_media( $atts = NULL, $content = NULL ) {
    $return_val  = '';
    $media_types = pzarc_has_media( get_the_id() );

    if ( in_array( 'gallery', $media_types ) ) {
      $return_val .= '<svg class="svg-icon" width="20" height="20" viewBox="0 0 20 20">
							<path d="M18.555,15.354V4.592c0-0.248-0.202-0.451-0.45-0.451H1.888c-0.248,0-0.451,0.203-0.451,0.451v10.808c0,0.559,0.751,0.451,0.451,0.451h16.217h0.005C18.793,15.851,18.478,14.814,18.555,15.354 M2.8,14.949l4.944-6.464l4.144,5.419c0.003,0.003,0.003,0.003,0.003,0.005l0.797,1.04H2.8z M13.822,14.949l-1.006-1.317l1.689-2.218l2.688,3.535H13.822z M17.654,14.064l-2.791-3.666c-0.181-0.237-0.535-0.237-0.716,0l-1.899,2.493l-4.146-5.42c-0.18-0.237-0.536-0.237-0.716,0l-5.047,6.598V5.042h15.316V14.064z M12.474,6.393c-0.869,0-1.577,0.707-1.577,1.576s0.708,1.576,1.577,1.576s1.577-0.707,1.577-1.576S13.343,6.393,12.474,6.393 M12.474,8.645c-0.371,0-0.676-0.304-0.676-0.676s0.305-0.676,0.676-0.676c0.372,0,0.676,0.304,0.676,0.676S12.846,8.645,12.474,8.645"></path>
						</svg>';
    }

    if ( in_array( 'video', $media_types ) ) {
      $return_val .= '<svg class="svg-icon" width="20" height="20" viewBox="0 0 20 20">
							<path d="M17.919,4.633l-3.833,2.48V6.371c0-1-0.815-1.815-1.816-1.815H3.191c-1.001,0-1.816,0.814-1.816,1.815v7.261c0,1.001,0.815,1.815,1.816,1.815h9.079c1.001,0,1.816-0.814,1.816-1.815v-0.739l3.833,2.478c0.428,0.226,0.706-0.157,0.706-0.377V5.01C18.625,4.787,18.374,4.378,17.919,4.633 M13.178,13.632c0,0.501-0.406,0.907-0.908,0.907H3.191c-0.501,0-0.908-0.406-0.908-0.907V6.371c0-0.501,0.407-0.907,0.908-0.907h9.079c0.502,0,0.908,0.406,0.908,0.907V13.632zM17.717,14.158l-3.631-2.348V8.193l3.631-2.348V14.158z"></path>
						</svg>';
    }

    if ( in_array( 'audio', $media_types ) ) {
      $return_val .= '<svg class="svg-icon"  width="20" height="20" viewBox="0 0 20 20">
							<path d="M17.969,10c0,1.707-0.5,3.366-1.446,4.802c-0.076,0.115-0.203,0.179-0.333,0.179c-0.075,0-0.151-0.022-0.219-0.065c-0.184-0.122-0.233-0.369-0.113-0.553c0.86-1.302,1.314-2.812,1.314-4.362s-0.454-3.058-1.314-4.363c-0.12-0.183-0.07-0.43,0.113-0.552c0.186-0.12,0.432-0.07,0.552,0.114C17.469,6.633,17.969,8.293,17.969,10 M15.938,10c0,1.165-0.305,2.319-0.88,3.339c-0.074,0.129-0.21,0.201-0.347,0.201c-0.068,0-0.134-0.016-0.197-0.052c-0.191-0.107-0.259-0.351-0.149-0.542c0.508-0.9,0.776-1.918,0.776-2.946c0-1.028-0.269-2.046-0.776-2.946c-0.109-0.191-0.042-0.434,0.149-0.542c0.193-0.109,0.436-0.042,0.544,0.149C15.634,7.681,15.938,8.834,15.938,10 M13.91,10c0,0.629-0.119,1.237-0.354,1.811c-0.063,0.153-0.211,0.247-0.368,0.247c-0.05,0-0.102-0.01-0.151-0.029c-0.203-0.084-0.301-0.317-0.217-0.521c0.194-0.476,0.294-0.984,0.294-1.508s-0.1-1.032-0.294-1.508c-0.084-0.203,0.014-0.437,0.217-0.52c0.203-0.084,0.437,0.014,0.52,0.217C13.791,8.763,13.91,9.373,13.91,10 M11.594,3.227v13.546c0,0.161-0.098,0.307-0.245,0.368c-0.05,0.021-0.102,0.03-0.153,0.03c-0.104,0-0.205-0.04-0.281-0.117l-3.669-3.668H2.43c-0.219,0-0.398-0.18-0.398-0.398V7.012c0-0.219,0.179-0.398,0.398-0.398h4.815l3.669-3.668c0.114-0.115,0.285-0.149,0.435-0.087C11.496,2.92,11.594,3.065,11.594,3.227 M7.012,7.41H2.828v5.18h4.184V7.41z M10.797,4.189L7.809,7.177v5.646l2.988,2.988V4.189z"></path>
						</svg>';
    }

    if ( in_array( 'document', $media_types ) ) {
      $return_val .= '<svg class="svg-icon" width="20" height="20" viewBox="0 0 20 20">
							<path d="M15.475,6.692l-4.084-4.083C11.32,2.538,11.223,2.5,11.125,2.5h-6c-0.413,0-0.75,0.337-0.75,0.75v13.5c0,0.412,0.337,0.75,0.75,0.75h9.75c0.412,0,0.75-0.338,0.75-0.75V6.94C15.609,6.839,15.554,6.771,15.475,6.692 M11.5,3.779l2.843,2.846H11.5V3.779z M14.875,16.75h-9.75V3.25h5.625V7c0,0.206,0.168,0.375,0.375,0.375h3.75V16.75z"></path>
						</svg>';
    }

    return ! empty( $return_val ) ? '<div class="arc-media-icons">' . $return_val . '</div>' : $return_val;
  }

  function arc_get_table_field_value( $atts = NULL, $contents = NULL ) {
    switch ( TRUE ) {
      case empty( $atts ) :
        return 'Missing table and field data';
        break;
      case ! isset( $atts['table'] ):
        return 'Missing table name';
        break;
      case ! isset( $atts['field'] ):
        return 'Missing field name';
        break;
    }

    // Sanitise
    $table = esc_html( esc_sql( $atts['table'] ) );
    $field = esc_html( esc_sql( $atts['field'] ) );
    global $wpdb, $post;

    // Get key field for linking. This will not work if multiple key fields...
    $pkey = $wpdb->get_results( "SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'" );
    if ( ! isset( $pkey[0] ) ) {
      return "No primary key found for table {$table}. Check table name is valid.";
    } else {
      $id_field = $pkey[0]->Column_name;
    }

    unset( $results );
    $results = $wpdb->get_results( "SELECT * FROM {$table} WHERE {$id_field} = {$post->ID}", OBJECT );

    if ( ! empty( $results[0] ) && isset( $results[0]->$field ) ) {
      return $results[0]->$field;
    } else {
      return NULL;
    }
  }
