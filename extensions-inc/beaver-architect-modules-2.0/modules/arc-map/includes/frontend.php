<?php
  $address ='';
  foreach ($settings->address as $address_field){
    $address .= arc_get_table_field_value( ArcFun::extract_table_field( $address_field ) ).' ';
  }
  echo '<div class="fl-map"><iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyD09zQ9PNDNNy9TadMuzRV_UsPUoWKntt8&q=';
  echo urlencode(trim($address));
  echo '" style="border:0;width:100%;height:'.$settings->height.'px"></iframe></div>';
