<?php
  /*
   * bfi_thumb - WP Image Resizer v1.3
   *
   * (c) 2013 Benjamin F. Intal / Gambit
   *
   * This program is free software: you can redistribute it and/or modify
   * it under the terms of the GNU General Public License as published by
   * the Free Software Foundation, either version 3 of the License, or
   * (at your option) any later version.
   *
   * This program is distributed in the hope that it will be useful,
   * but WITHOUT ANY WARRANTY; without even the implied warranty of
   * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   * GNU General Public License for more details.
   *
   * You should have received a copy of the GNU General Public License
   * along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */

  /** Uses WP's Image Editor Class to resize and filter images
   *
   * @param $url    string the local image URL to manipulate
   * @param $params array the options to perform on the image. Keys and values supported:
   *                'width' int pixels
   *                'height' int pixels
   *                'opacity' int 0-100
   *                'color' string hex-color #000000-#ffffff
   *                'grayscale' bool
   *                'negate' bool
   *                'crop' bool
   * @param $single boolean, if false then an array of data will be returned
   *
   * @return string|array containing the url of the resized modofied image
   */

  if ( ! defined( 'ABSPATH' ) ) {
    exit;
  } // Exit if accessed directly

  if ( ! function_exists( 'bfi_thumb' ) ) {
    function bfi_thumb( $url, $params = array(), $single = TRUE ) {
      $class = BFI_Class_Factory::getNewestVersion( 'BFI_Thumb' );

      return call_user_func( array( $class, 'thumb' ), $url, $params, $single );
    }
  }


  /**
   * Class factory, this is to make sure that when multiple bfi_thumb scripts
   * are used (e.g. a plugin and a theme both use it), we always use the
   * latest version.
   */
  if ( ! class_exists( 'BFI_Class_Factory' ) ) {
    class BFI_Class_Factory {
      public static $versions = array();
      public static $latestClass = array();

      public static function addClassVersion( $baseClassName, $className, $version ) {
        if ( empty( self::$versions[ $baseClassName ] ) ) {
          self::$versions[ $baseClassName ] = array();
        }
        self::$versions[ $baseClassName ][] = array(
          'class'   => $className,
          'version' => $version,
        );
      }

      public static function getNewestVersion( $baseClassName ) {
        if ( empty( self::$latestClass[ $baseClassName ] ) ) {
          usort( self::$versions[ $baseClassName ], array( __CLASS__, "versionCompare" ) );
          self::$latestClass[ $baseClassName ] = self::$versions[ $baseClassName ][0]['class'];
          unset( self::$versions[ $baseClassName ] );
        }

        return self::$latestClass[ $baseClassName ];
      }

      public static function versionCompare( $a, $b ) {
        return version_compare( $a['version'], $b['version'] ) == 1 ? - 1 : 1;
      }
    }
  }


  /*
   * Change the default image editors
   */

// Instead of using the default image editors, use our extended ones
  if ( ! function_exists( 'bfi_wp_image_editor' ) ) {
    function bfi_wp_image_editor( $editorArray ) {
      // Make sure that we use the latest versions
      return array(
        BFI_Class_Factory::getNewestVersion( 'BFI_Image_Editor_Imagick' ),
        BFI_Class_Factory::getNewestVersion( 'BFI_Image_Editor_GD' ),
      );
    }
  }

  require_once ABSPATH . WPINC . '/class-wp-image-editor.php';
  require_once ABSPATH . WPINC . '/class-wp-image-editor-imagick.php';
  require_once ABSPATH . WPINC . '/class-wp-image-editor-gd.php';


  /**
   * check for ImageMagick or GD
   */
  add_action( 'admin_init', 'bfi_wp_image_editor_check' );
  if ( ! function_exists( 'bfi_wp_image_editor_check' ) ) {
    function bfi_wp_image_editor_check() {
      $arg = array( 'mime_type' => 'image/jpeg' );
      if ( wp_image_editor_supports( $arg ) !== TRUE ) {
        add_filter( 'admin_notices', 'bfi_wp_image_editor_check_notice' );
      }
    }
  }
  if ( ! function_exists( 'bfi_wp_image_editor_check_notice' ) ) {
    function bfi_wp_image_editor_check_notice() {
      printf( "<div class='error'><p>%s</div>", __( "The server does not have ImageMagick or GD installed and/or enabled! Any of these includes are required for WordPress to be able to resize images. Please contact your server administrator to enable this before continuing.", "default" ) );
    }
  }


  /*
   * Enhanced Imagemagick Image Editor
   */
  if ( ! class_exists( 'BFI_Image_Editor_Imagick_1_3' ) ) {
    BFI_Class_Factory::addClassVersion( 'BFI_Image_Editor_Imagick', 'BFI_Image_Editor_Imagick_1_3', '1.3' );

    class BFI_Image_Editor_Imagick_1_3 extends WP_Image_Editor_Imagick {

      /** Changes the opacity of the image
       *
       * @supports 3.5.1
       * @access   public
       *
       * @param float $opacity (0.0-1.0)
       *
       * @return boolean|WP_Error
       */
      public function opacity( $opacity ) {
        $opacity /= 100;

        try {
          // From: http://stackoverflow.com/questions/3538851/php-imagick-setimageopacity-destroys-transparency-and-does-nothing
          // preserves transparency
          //$this->image->setImageOpacity($opacity);
          return $this->image->evaluateImage( Imagick::EVALUATE_MULTIPLY, $opacity, Imagick::CHANNEL_ALPHA );
        } catch ( Exception $e ) {
          return new WP_Error( 'image_opacity_error', $e->getMessage() );
        }
      }

      /** Tints the image a different color
       *
       * @supports 3.5.1
       * @access   public
       *
       * @param string hex color e.g. #ff00ff
       *
       * @return boolean|WP_Error
       */
      public function colorize( $hexColor ) {
        try {
          return $this->image->colorizeImage( $hexColor, 1.0 );
        } catch ( Exception $e ) {
          return new WP_Error( 'image_colorize_error', $e->getMessage() );
        }
      }

      /** Makes the image grayscale
       *
       * @supports 3.5.1
       * @access   public
       *
       * @return boolean|WP_Error
       */
      public function grayscale() {
        try {
          return $this->image->modulateImage( 100, 0, 100 );
        } catch ( Exception $e ) {
          return new WP_Error( 'image_grayscale_error', $e->getMessage() );
        }
      }

      /** Negates the image
       *
       * @supports 3.5.1
       * @access   public
       *
       * @return boolean|WP_Error
       */
      public function negate() {
        try {
          return $this->image->negateImage( FALSE );
        } catch ( Exception $e ) {
          return new WP_Error( 'image_negate_error', $e->getMessage() );
        }
      }
      public function addtext( $atts ) {
       try {
         $draw = new ImagickDraw();
         /* Black text */
         $pixel = new ImagickPixel( '#'.$atts['colour'] );
         $draw->setFillColor($pixel);

         /* Font properties */
         $draw->setFont($atts['font']);
         $draw->setFontSize( $atts['size'] );
         $position = array('top'=>($atts['size']+10),'middle'=>(($this->image->getImageHeight()+$atts['size'])/2),'bottom'=>($this->image->getImageHeight()+$atts['size']-$atts['size']-10));
         return $this->image->annotateImage( $draw, ($atts['size']+10),$position[$atts['position']],0,  $atts['text']);
        } catch (Exception $e) {}
        return new WP_Error( 'image_addtext_error', $e->getMessage());
      }

    }
  }


  /*
   * Enhanced GD Image Editor
   */
  if ( ! class_exists( 'BFI_Image_Editor_GD_1_3' ) ) {
    BFI_Class_Factory::addClassVersion( 'BFI_Image_Editor_GD', 'BFI_Image_Editor_GD_1_3', '1.3' );

    class BFI_Image_Editor_GD_1_3 extends WP_Image_Editor_GD {
      /** Rotates current image counter-clockwise by $angle.
       * Ported from image-edit.php
       * Added presevation of alpha channels
       *
       * @since  3.5.0
       * @access public
       *
       * @param float $angle
       *
       * @return boolean|WP_Error
       */
      public function rotate( $angle ) {
        if ( function_exists( 'imagerotate' ) ) {
          $rotated = imagerotate( $this->image, $angle, 0 );

          // Add alpha blending
          imagealphablending( $rotated, TRUE );
          imagesavealpha( $rotated, TRUE );

          if ( is_resource( $rotated ) ) {
            imagedestroy( $this->image );
            $this->image = $rotated;
            $this->update_size();

            return TRUE;
          }
        }

        return new WP_Error( 'image_rotate_error', __( 'Image rotate failed.', 'default' ), $this->file );
      }

      public function addtext( $atts ) {
        if ( function_exists( 'imagettftext' ) ) {
          $position = array('top'=>($atts['size']+10),'middle'=>((imagesy($this->image)+$atts['size'])/2),'bottom'=>(imagesy($this->image)+$atts['size']-$atts['size']-10));
          $coords = imagettftext( $this->image, $atts['size'], 0, ($atts['size']+10),$position[$atts['position']] , hexdec('0x'.$atts['colour']), $atts['font'], $atts['text']);
          return true;
        }
        return new WP_Error( 'image_addtext_error', __( 'Image add text failed.', 'default' ), $this->file );
      }

      /** Changes the opacity of the image
       *
       * @supports 3.5.1
       * @access   public
       *
       * @param float $opacity (0.0-1.0)
       *
       * @return boolean|WP_Error
       */
      public function opacity( $opacity ) {
        $opacity /= 100;

        $filtered = $this->_opacity( $this->image, $opacity );

        if ( is_resource( $filtered ) ) {
          // imagedestroy($this->image);
          $this->image = $filtered;

          return TRUE;
        }

        return new WP_Error( 'image_opacity_error', __( 'Image opacity change failed.', 'default' ), $this->file );
      }

      // from: http://php.net/manual/en/function.imagefilter.php
      // params: image resource id, opacity (eg. 0.0-1.0)
      protected function _opacity( $image, $opacity ) {
        if ( ! function_exists( 'imagealphablending' ) || ! function_exists( 'imagecolorat' ) || ! function_exists( 'imagecolorallocatealpha' ) || ! function_exists( 'imagesetpixel' ) ) {
          return FALSE;
        }

        //get image width and height
        $w = imagesx( $image );
        $h = imagesy( $image );

        //turn alpha blending off
        imagealphablending( $image, FALSE );

        //find the most opaque pixel in the image (the one with the smallest alpha value)
        $minalpha = 127;
        for ( $x = 0; $x < $w; $x ++ ) {
          for ( $y = 0; $y < $h; $y ++ ) {
            $alpha = ( imagecolorat( $image, $x, $y ) >> 24 ) & 0xFF;
            if ( $alpha < $minalpha ) {
              $minalpha = $alpha;
            }
          }
        }

        //loop through image pixels and modify alpha for each
        for ( $x = 0; $x < $w; $x ++ ) {
          for ( $y = 0; $y < $h; $y ++ ) {
            //get current alpha value (represents the TANSPARENCY!)
            $colorxy = imagecolorat( $image, $x, $y );
            $alpha   = ( $colorxy >> 24 ) & 0xFF;
            //calculate new alpha
            if ( $minalpha !== 127 ) {
              $alpha = 127 + 127 * $opacity * ( $alpha - 127 ) / ( 127 - $minalpha );
            } else {
              $alpha += 127 * $opacity;
            }
            //get the color index with new alpha
            $alphacolorxy = imagecolorallocatealpha( $image, ( $colorxy >> 16 ) & 0xFF, ( $colorxy >> 8 ) & 0xFF, $colorxy & 0xFF, $alpha );
            //set pixel with the new color + opacity
            if ( ! imagesetpixel( $image, $x, $y, $alphacolorxy ) ) {
              return FALSE;
            }
          }
        }

        imagesavealpha( $image, TRUE );

        return $image;
      }

      /** Tints the image a different color
       *
       * @supports 3.5.1
       * @access   public
       *
       * @param string hex color e.g. #ff00ff
       *
       * @return boolean|WP_Error
       */
      public function colorize( $hexColor ) {
        if ( function_exists( 'imagefilter' ) && function_exists( 'imagesavealpha' ) && function_exists( 'imagealphablending' ) ) {
          $hexColor = preg_replace( '#^\##', '', $hexColor );
          $r        = hexdec( substr( $hexColor, 0, 2 ) );
          $g        = hexdec( substr( $hexColor, 2, 2 ) );
          $b        = hexdec( substr( $hexColor, 2, 2 ) );

          imagealphablending( $this->image, FALSE );
          if ( imagefilter( $this->image, IMG_FILTER_COLORIZE, $r, $g, $b, 0 ) ) {
            imagesavealpha( $this->image, TRUE );

            return TRUE;
          }
        }

        return new WP_Error( 'image_colorize_error', __( 'Image color change failed.', 'default' ), $this->file );
      }

      /** Makes the image grayscale
       *
       * @supports 3.5.1
       * @access   public
       *
       * @return boolean|WP_Error
       */
      public function grayscale() {
        if ( function_exists( 'imagefilter' ) ) {
          if ( imagefilter( $this->image, IMG_FILTER_GRAYSCALE ) ) {
            return TRUE;
          }
        }

        return new WP_Error( 'image_grayscale_error', __( 'Image grayscale failed.', 'default' ), $this->file );
      }

      /** Negates the image
       *
       * @supports 3.5.1
       * @access   public
       *
       * @return boolean|WP_Error
       */
      public function negate() {
        if ( function_exists( 'imagefilter' ) ) {
          if ( imagefilter( $this->image, IMG_FILTER_NEGATE ) ) {
            return TRUE;
          }
        }

        return new WP_Error( 'image_negate_error', __( 'Image negate failed.', 'default' ), $this->file );
      }

      public function progressivejpeg() {
        if ( function_exists( 'imageinterlace' ) ) {
          if ( imageinterlace( $this->image, TRUE ) ) {
            return TRUE;
          }
        }

      }
    }
  }


  /*
   * Main Class
   */
  if ( ! class_exists( 'BFI_Thumb_1_3' ) ) {
    BFI_Class_Factory::addClassVersion( 'BFI_Thumb', 'BFI_Thumb_1_3', '1.3' );

    class BFI_Thumb_1_3 {
      /** Uses WP's Image Editor Class to resize and filter images
       * Inspired by: https://github.com/sy4mil/Aqua-Resizer/blob/master/aq_resizer.php
       *
       * @param $url    string the local image URL to manipulate
       * @param $params array the options to perform on the image. Keys and values supported:
       *                'width' int pixels
       *                'height' int pixels
       *                'opacity' int 0-100
       *                'color' string hex-color #000000-#ffffff
       *                'grayscale' bool
       *                'crop' bool
       *                'negate' bool
       *                'quality' int 0-100
       *                 text': array(angle,size,colour,font,text)
       * @param $single boolean, if false then an array of data will be returned
       *
       * @return string|array
       */
      public static function thumb( $url, $params = array(), $single = TRUE ) {
//        var_Dump($url,$params);
        extract( $params );
        //validate inputs
        if ( ! $url ) {
          return FALSE;
        }
        //define upload path & dir
        $upload_info = wp_upload_dir();
        $upload_dir  = $upload_info['basedir'];
        $upload_url  = $upload_info['baseurl'];
        $theme_url   = get_template_directory_uri();
        $theme_dir   = get_template_directory();
        $content_url = content_url();

        // TODO: WP says not to use this!
        $content_dir = WP_CONTENT_DIR;
        $nextgen_url = $content_url . '/gallery/';
        $nextgen_dir = $content_dir . '/gallery/';

        // find the path of the image. Perform 2 checks:
        // #1 check if the image is in the uploads folder
        $img_path = self::get_img_paths( $url, $upload_url, $upload_dir, $theme_url, $theme_dir, $nextgen_url, $nextgen_dir );

        // Fail if we can't find the image in our WP local directory
        if ( empty( $img_path ) ) {
          // Move image to local server and try again.
          $dest = PZARC_CACHE_PATH. basename($url);
          if (!file_exists($dest)) {
            file_put_contents( $dest, file_get_contents( $url ) );
          }
          $img_path = $dest;
        }

        //check if img path exists, and is an image indeed
        if ( ! @file_exists( $img_path ) OR ! getimagesize( $img_path ) ) {
          return $url;
        }

        // This is the filename
        $basename = basename( $img_path );
        //get image info
        $info = pathinfo( $img_path );
        $ext  = $info['extension'];
        list( $orig_w, $orig_h ) = getimagesize( $img_path );
        // support percentage dimensions. compute percentage based on
        // the original dimensions
        if ( isset( $width ) ) {
          if ( stripos( $width, '%' ) !== FALSE ) {
            $width = (int) ( (float) str_replace( '%', '', $width ) / 100 * $orig_w );
          }
        }
        if ( isset( $height ) ) {
          if ( stripos( $height, '%' ) !== FALSE ) {
            $height = (int) ( (float) str_replace( '%', '', $height ) / 100 * $orig_h );
          }
        }

        // The only purpose of this is to detemine the final width and height
        // without performing any actual image manipulation, which will be used
        // to check whether a resize was previously done.
        if ( isset( $width ) ) {
          //get image size after cropping
          $dims  = image_resize_dimensions( $orig_w, $orig_h, $width, isset( $height ) ? $height : NULL, ( isset( $crop ) ? $crop : FALSE ) );
          $dst_w = $dims[4];
          $dst_h = $dims[5];
        }

        // create the suffix for the saved file
        // we can use this to check whether we need to create a new file or just use an existing one.
        $suffix = (string) filemtime( $img_path ) . ( isset( $width ) ? str_pad( (string) $width, 5, '0', STR_PAD_LEFT ) : '00000' ) . ( isset( $height ) ? str_pad( (string) $height, 5, '0', STR_PAD_LEFT ) : '00000' ) . ( isset( $opacity ) ? str_pad( (string) $opacity, 3, '0', STR_PAD_LEFT ) : '100' ) . ( isset( $color ) ? str_pad( preg_replace( '#^\##', '', $color ), 8, '0', STR_PAD_LEFT ) : '00000000' ) . ( isset( $grayscale ) ? ( $grayscale ? '1' : '0' ) : '0' ) . ( isset( $crop ) ? ( $crop ? '1' : '0' ) : '0' ) . ( isset( $negate ) ? ( $negate ? '1' : '0' ) : '0' );
        $suffix = self::base_convert_arbitrary( $suffix, 10, 36 );

        // use this to check if cropped image already exists, so we can return that instead
        $dst_rel_path = str_replace( '.' . $ext, '', basename( $img_path ) );

        // If opacity is set, change the image type to png
        if ( isset( $opacity ) ) {
          $ext = 'png';
        }


        // Create the upload subdirectory, this is where
        // we store all our generated images
        if ( defined( 'BFITHUMB_UPLOAD_DIR' ) ) {
          $upload_dir .= "/" . BFITHUMB_UPLOAD_DIR;
          $upload_url .= "/" . BFITHUMB_UPLOAD_DIR;
        } else {
          $upload_dir .= "/bfi_thumb";
          $upload_url .= "/bfi_thumb";
        }
        if ( ! is_dir( $upload_dir ) ) {
          wp_mkdir_p( $upload_dir );
        }


        // desination paths and urls
        $destfilename = "{$upload_dir}/{$dst_rel_path}-{$suffix}.{$ext}";
        $img_url      = "{$upload_url}/{$dst_rel_path}-{$suffix}.{$ext}";

        if ( @file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
          // if file exists, just return it
          // We could add cache ageing in here
        } else {
          // perform resizing and other filters
          $editor = wp_get_image_editor( $img_path );

          if ( is_wp_error( $editor ) ) {
            return FALSE;
          }

          /*
           * Perform image manipulations
           */
          if ( ( isset( $width ) && $width ) || ( isset( $height ) && $height ) ) {

            if ( is_wp_error( $editor->resize( isset( $width ) ? $width : NULL, isset( $height ) ? $height : NULL, ( isset( $crop ) ? $crop : FALSE ) ) ) ) {
              return FALSE;
            }
          }

          if ( isset( $negate ) ) {
            if ( $negate ) {
              if ( is_wp_error( $editor->negate() ) ) {
                return FALSE;
              }
            }
          }

          if ( isset( $opacity ) ) {
            if ( is_wp_error( $editor->opacity( $opacity ) ) ) {
              return FALSE;
            }
          }

          if ( isset( $grayscale ) ) {
            if ( $grayscale ) {
              if ( is_wp_error( $editor->grayscale() ) ) {
                return FALSE;
              }
            }
          }

          if ( isset( $color ) ) {
            if ( is_wp_error( $editor->colorize( $color ) ) ) {
              return FALSE;
            }
          }


          if ( !empty( $text ) ) {
            if ( is_wp_error( $editor->addtext( maybe_unserialize($text) ) ) ) {
              return FALSE;
            }
          }


          // save our new image
          $mime_type = isset( $opacity ) ? 'image/png' : NULL;
          if ( $mime_type === 'image/jpeg' || empty( $mime_type ) ) {
            // Added this check incase a custom editor like EWWWIO_GD_Editor  that might not have this method
            if ( method_exists( get_class( $editor ), 'progressivejpeg' ) ) {
              $editor->progressivejpeg();
            }
          }

          if ( isset( $quality ) ) {
            $editor->set_quality( $quality );
          }
          $resized_file = $editor->save( $destfilename, $mime_type );
        }

        //return the output
        if ( $single ) {
          $image = $img_url;
        } else {
          //array return
          $image = array(
            0 => $img_url,
            1 => isset( $dst_w ) ? $dst_w : $orig_w,
            2 => isset( $dst_h ) ? $dst_h : $orig_h,
          );
        }

        return $image;
      }

      /** Shortens a number into a base 36 string
       *
       * @param $number   string a string of numbers to convert
       * @param $fromBase starting base
       * @param $toBase   base to convert the number to
       *
       * @return string base converted characters
       */
      protected static function base_convert_arbitrary( $number, $fromBase, $toBase ) {
        $digits = '0123456789abcdefghijklmnopqrstuvwxyz';
        $length = strlen( $number );
        $result = '';

        $nibbles = array();
        for ( $i = 0; $i < $length; ++ $i ) {
          $nibbles[ $i ] = strpos( $digits, $number[ $i ] );
        }

        do {
          $value  = 0;
          $newlen = 0;
          for ( $i = 0; $i < $length; ++ $i ) {
            $value = $value * $fromBase + $nibbles[ $i ];
            if ( $value >= $toBase ) {
              $nibbles[ $newlen ++ ] = (int) ( $value / $toBase );
              $value                 %= $toBase;
            } else {
              if ( $newlen > 0 ) {
                $nibbles[ $newlen ++ ] = 0;
              }
            }
          }
          $length = $newlen;
          $result = $digits[ $value ] . $result;
        } while ( $newlen != 0 );

        return $result;
      }

      /**
       * @param $url
       * @param $upload_url
       * @param $upload_dir
       * @param $theme_url
       * @param $theme_dir
       * @param $nextgen_url
       * @param $nextgen_dir
       *
       * @return string
       */
      public static function get_img_paths( $url, $upload_url, $upload_dir, $theme_url, $theme_dir, $nextgen_url, $nextgen_dir ) {

        $img_path = '';
        if ( strpos( $url, $upload_url ) !== FALSE ) {
          $rel_path = str_replace( $upload_url, '', $url );
          $img_path = $upload_dir . $rel_path;

          // #2 check if the image is in the current theme folder
        } elseif ( strpos( $url, $theme_url ) !== FALSE ) {
          $rel_path = str_replace( $theme_url, '', $url );
          $img_path = $theme_dir . $rel_path;

        } elseif ( strpos( $url, $nextgen_url ) !== FALSE ) {
          $rel_path = str_replace( $nextgen_url, '', $url );
          $img_path = $nextgen_dir . $rel_path;

        }

        return $img_path;
      }
    }
  }


// don't use the default resizer since we want to allow resizing to larger sizes (than the original one)
// Parts are copied from media.php
// Crop is always applied (just like timthumb)
  if ( ! function_exists( 'bfi_image_resize_dimensions' ) ) {
    function bfi_image_resize_dimensions( $payload, $orig_w, $orig_h, $dest_w, $dest_h, $crop = FALSE ) {
      // Need to pass $crop in string form because WP function doesn't cope with it as an array (throws a PHP Notice" Array to String conversion)
      // Use format XxYxF
      // X = crop X position as a decimal between 0 and 1
      // Y = crop Y position
      // F = focal point

//     var_Dump($payload, $orig_w, $orig_h, $dest_w, $dest_h, $crop);
      if ( is_string( $crop ) ) {
        $crop = explode( 'x', $crop );
        // Check if defaults needed
        // TODO: Need to work with WP left, right, center options
        $crop[0] = ( ! isset( $crop[0] ) ? 50 : $crop[0] );
        $crop[1] = ( ! isset( $crop[1] ) ? 50 : $crop[1] );
        $crop[2] = ( ! isset( $crop[2] ) ? 'center' : $crop[2] );
      } elseif ( TRUE === $crop || 1 === $crop ) {
        $crop = array( '50', '50', 'center' );
      } elseif ( $crop !== FALSE && ! isset( $crop[2] ) && is_array( $crop ) ) {
        $crop[2] = 'respect';
      }
      switch ( $crop[2] ) {
        case 'topleft':
          $crop[0] = 0;
          $crop[1] = 0;
          break;
        case 'topcentre':
          $crop[0] = 50;
          $crop[1] = 0;
          break;
        case 'topright':
          $crop[0] = 100;
          $crop[1] = 0;
          break;
        case 'midleft':
          $crop[0] = 0;
          $crop[1] = 50;
          break;
        case 'midcentre':
          $crop[0] = 50;
          $crop[1] = 50;
          break;
        case 'midright':
          $crop[0] = 100;
          $crop[1] = 50;
          break;
        case 'bottomleft':
          $crop[0] = 0;
          $crop[1] = 100;
          break;
        case 'bottomcentre':
          $crop[0] = 50;
          $crop[1] = 100;
          break;
        case 'bottomright':
          $crop[0] = 100;
          $crop[1] = 100;
          break;
      }


      $aspect_ratio = $orig_w / $orig_h;

      $new_w = $dest_w;
      $new_h = $dest_h;

      if ( ! $new_w ) {
        $new_w = intval( $new_h * $aspect_ratio );
      }

      if ( ! $new_h ) {
        $new_h = intval( $new_w / $aspect_ratio );
      }

      $size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

      $crop_w = round( $new_w / $size_ratio );
      $crop_h = round( $new_h / $size_ratio );

      // Crop from offsets (left, top) as percentages
      list( $x, $y, $f ) = $crop;

      // Convert $x and $y to decimal if necessary
      $x = $x > 1 ? $x / 100 : $x;
      $y = $y > 1 ? $y / 100 : $y;

      $ideal_s_x = 0;
      $ideal_s_y = 0;

      // Handle focal point positioning in output image
      switch ( $f ) {

        case 'topleft':
        case 'none':
          // No focal point. Crop from top left
          $ideal_s_x = 0;
          $ideal_s_y = 0;
          break;

        case 'topcentre':
          $ideal_s_x = $x * $orig_w - ( $crop_w * 0.5 );
          $ideal_s_y = 0;
          break;

        case 'topright':
          $ideal_s_x = $x * $orig_w - ( $crop_w * 1 );
          $ideal_s_y = 0;
          break;

        case 'midleft':
          $ideal_s_x = 0;
          $ideal_s_y = $y * $orig_h - ( $crop_h * 0.5 );
          break;

        case 'midcentre':
          $ideal_s_x = $x * $orig_w - ( $crop_w * 0.5 );
          $ideal_s_y = $y * $orig_h - ( $crop_h * 0.5 );
          break;

        case 'midright':
          $ideal_s_x = $x * $orig_w - ( $crop_w * 1 );
          $ideal_s_y = $y * $orig_h - ( $crop_h * 0.5 );
          break;

        case 'bottomleft':
          $ideal_s_x = 0;
          $ideal_s_y = $y * $orig_h - ( $crop_h * 1 );
          break;

        case 'bottomcentre':
          $ideal_s_x = $x * $orig_w - ( $crop_w * 0.5 );
          $ideal_s_y = $y * $orig_h - ( $crop_h * 1 );
          break;

        case 'bottomright':
          $ideal_s_x = $x * $orig_w - ( $crop_w * 1 );
          $ideal_s_y = $y * $orig_h - ( $crop_h * 1 );
          break;


        case 'scale':

          $w_size = $dest_h / $orig_h * $orig_w;
          $h_size = $dest_w / $orig_w * $orig_h;


          if ( $w_size < $dest_w ) {
            $new_h = $h_size;
          }

          if ( $h_size < $dest_h ) {
            $new_w = $w_size;
          }

          $crop_w = $orig_w;
          $crop_h = $orig_h;

          $ideal_s_x = 0;
          $ideal_s_y = 0;
          break;

        case 'scale_height':

          $w_size = $dest_h / $orig_h * $orig_w;
          $h_size = $dest_w / $orig_w * $orig_h;

//          if ($w_size < $dest_w) {
          $new_w = $w_size;
//            $new_h = $h_size;
//          }

//          if ($h_size > $dest_h) {
//            $new_w = $w_size;
          $new_h = $dest_h;
//          }


          $crop_w = $orig_w;
          $crop_h = $orig_h;

          $ideal_s_x = 0;
          $ideal_s_y = 0;
          break;
        case 'centre':
        case 'center':
          // Try to centre focal point
          $ideal_s_x = $x * $orig_w - ( $crop_w * 0.5 );
          $ideal_s_y = $y * $orig_h - ( $crop_h * 0.5 );
          break;

        case 'shrink':


          $new_w = $dest_w;
          $new_h = ( $dest_w / $orig_w ) * $orig_h;
          if ( $new_h > $dest_h ) {
            $new_h = $dest_h;
            $new_w = ( $dest_h / $orig_h ) * $orig_w;
          }

          $crop_w = $orig_w;
          $crop_h = $orig_h;

          $ideal_s_x = 0;
          $ideal_s_y = 0;

          break;

        default:
        case 'respect':
          // Try to maintain relative focal point
          $ideal_s_x = $x * $orig_w - ( $crop_w * $x );
          $ideal_s_y = $y * $orig_h - ( $crop_h * $y );
          break;

      }
      // Ideally we want our x,y crop-focus-point as chosen
      // but to put (for example) the top left corner in the centre of our cropped
      // image we end up with black strips where there isn't enough image on the
      // left and top.
      // This maths takes our ideal offsets and gets as close to it as possible.

      if ( $ideal_s_x < 0 ): {
        $s_x = 0;
      } elseif ( $ideal_s_x + $crop_w > $orig_w ): {
        $s_x = $orig_w - $crop_w;
      }
      else: {
        $s_x = floor( $ideal_s_x );
      }
      endif;

      if ( $ideal_s_y < 0 ): {
        $s_y = 0;
      } elseif ( $ideal_s_y + $crop_h > $orig_h ): {
        $s_y = $orig_h - $crop_h;
      }
      else: {
        $s_y = floor( $ideal_s_y );
      }
      endif;
      // the return array matches the parameters to imagecopyresampled()
      // int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
      $return_arr = array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
      return $return_arr;
    }
  }


// This function allows us to latch on WP image functions such as
// the_post_thumbnail, get_image_tag and wp_get_attachment_image_src
// so that you won't have to use the function bfi_thumb in order to do resizing.
// To make this work, in the WP image functions, when specifying an
// array for the image dimensions, add a 'bfi_thumb' => true to
// the array, then add your normal $params arguments.
//
// e.g. the_post_thumbnail( array( 1024, 400, 'bfi_thumb' => true, 'grayscale' => true ) );
  if ( ! function_exists( 'bfi_image_downsize' ) ) {
    function bfi_image_downsize( $out, $id, $size ) {
      if ( ! is_array( $size ) ) {
        return FALSE;
      }
      if ( ! array_key_exists( 'bfi_thumb', $size ) ) {
        return FALSE;
      }
      if ( empty( $size['bfi_thumb'] ) ) {
        return FALSE;
      }

      $img_url = wp_get_attachment_url( $id );

      $params           = $size;
      $params['width']  = $size[0];
      $params['height'] = $size[1];
      $resized_img_url  = bfi_thumb( $img_url, $params );

      $return_arr = array( $resized_img_url, $size[0], $size[1], FALSE );

      return $return_arr;
    }
  }


  /**
   * Add all the admin menus etc
   */


  function bfi_add_options_pages() {
    add_options_page( 'BFI Thumbs', 'BFI Thumbs', 'manage_options', 'bfithumbs', 'bfi_thumbs_settings' );

  }

  if ( ! class_exists( 'ArchitectAdmin' ) ) {
    add_action( 'admin_menu', 'bfi_add_options_pages' );
  }
  /**
   * bfi_thumbs_settings
   */
  function bfi_thumbs_settings() {
    if ( ! current_user_can( 'edit_others_pages' ) ) {
      wp_die( __( 'You do not have sufficient permissions to access this page.', 'default' ) );
    }

    global $title;
    // TODO: Need to nonce this probs!
    ?>

    <div class="wrap">
    <!-- Display Plugin Icon, Header, and Description -->
    <div class="icon32" id="icon-tools"><br></div>
    <h2><?php echo $title ?></h2>

    <h3>Flush BFI Thumbs image cache</h3>

    <p>If you update or change images in any posts,sometimes the image cache may get out-of-sync. In that case, you can
      refresh the thumbs image cache to ensure your site visitors are seeing the correct images.</p>

    <p>Please note:
      Refreshing the cache causes no problems other than the next person who visits your site may have to wait a little
      longer as the cache images get recreated. <strong>No images in any post will be affected</strong>. </p>

    <p>Click the
      button to empty the BFI Thumbs image cache.</p>

    <form action="options-general.php?page=bfithumbs" method="post">
      <input class="button-primary" type="submit" name="flushbficache" value="Flush BFI Thumbs image cache">
    </form>
    <hr style="margin-top:20px;border-color:#eee;border-style:solid;"/>
    <?php
    if ( isset( $_POST['flushbficache'] ) ) {
      bfi_flush_image_cache();
      echo '<div id="message" class="updated"><p>BFI Thumbs image cache cleared. It will be recreated next time someone vists your site.</p></div>';
    }

  }


