<?php
  $address = ( ! empty( $settings->address ) ) ? $settings->address . ' ' : '';
  if ( ! empty( $settings->other_address[0] ) ) {
    foreach ( $settings->other_address as $address_field ) {
      $address .= arc_get_table_field_value( ArcFun::extract_table_field( $address_field ) ) . ' ';
    }
  }
  $googleapikey = ( ! empty( $settings->googleapikey ) ) ? $settings->googleapikey . ' ' : '';
  echo '<div class="fl-map"><iframe src="https://www.google.com/maps/embed/v1/place?key=';
  echo urlencode( trim( $googleapikey ) );
  echo '&q=';
  echo urlencode( trim( $address ) );
  echo '" style="border:0;width:100%;height:' . $settings->height . 'px"></iframe></div>';
