<?php
  $address = do_shortcode( $settings->address);
  echo '<div class="fl-map"><iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyD09zQ9PNDNNy9TadMuzRV_UsPUoWKntt8&q=';
//  echo '<>';
  echo ($address);
//  echo '</>';
  echo '" style="border:0;width:100%;height:'.$settings->height.'px"></iframe></div>';
