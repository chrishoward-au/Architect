bfi_thumb v1.3.1
================

On the fly image resizer / cropper / grayscaler / colorizer / opacitor :) for WordPress

**Update: BFI Thumb now integrates into the core WordPress image functions, you actually don't have to call `bfi_thumb` to use it!**

*You can read the tutorial and get more information about BFI_Thumb from this article: [Moving Away From TimThumb to BFIThumb](http://wp.tutsplus.com/tutorials/theme-development/moving-away-from-timthumb-to-bfithumb/)*

**Supports WordPress 3.5+**

Bfi_thumb resizes image on the fly using WordPress' Image Editor classes, thus supports both Imagick and GD and switches automatically depending on what's available. The default Image Editors only have a handful of basic functions (crop, resize, rotate, etc), bfi_thumb also extends these classes to include these new functions:
* Grayscale
* Color (colorize)
* Opacity
* Negate
* Resize images down or UP
* Image Quality (1-100) *NEW
* Crop offsets as percentages e.g., array( 0.2, 0.8 ) * NEW

The code was inspired by the awesome [Aqua Resizer](https://github.com/sy4mil/Aqua-Resizer/blob/master/aq_resizer.php)


New Features (as of v1.3.1)
===========================

You can now specify an image quality as a parameter on a per-jpeg-image basis; e.g., `<img src="<?= bfi_thumb( $src, array( 'quality' => 20 ) ) ?>" alt />`  
It's particularly useful with a retina image approach or using `picture` markup because you can spec your hi-res image at a lower quality to keep file size down.

Note that while we're talking about image compression, the parameter is 'quality', with 100 being lossless and 1 being worst quality (most compression).

Also added is the ability to specify a point to crop to as a "percentage". This is much like WP's `array( left, top )` crop direction option but we have the fidelity to crop to 20% from the left edge, 90% from the top like so: `array( 0.2, 0.9 )`.

Default is 0.5, 0.5 which crops to center so the BFI default behaviour hasn't changed.

To be honest, this is a little bit useless without a dashboard UI for specifying the point of focus to crop to. I'll look into that when I get a chance. 



Where do I use this?
====================

You can use this script in your WordPress themes or plugins. The script is safe to be included multiple times in a single WordPress site by different plugins and themes. When multiple versions of BFI_Thumb are found within a site, the newest version will be used.

This means that if you use bfi_thumb in your WordPress theme, and some plugins you also have activated use bfi_thumb, they would all work without any problems.


Demo
====

My themes usee BFI Thumb to generate all it's images:
* [MaS WP Theme](http://themeforest.net/item/mas-multilingual-responsive-multipurpose-theme/full_screen_preview/3978788?ref=bfintal)
* [Amplus WP Theme](http://themeforest.net/item/amplus-responsive-multilingual-wordpress-theme/full_screen_preview/180353?ref=bfintal)
* [Dimension WP Theme](http://themeforest.net/item/dimension-retina-responsive-multipurpose-theme/full_screen_preview/5495659?ref=bfintal)


Where are the images stored?
============================

Bfi_thumb stores previously created images in WP's default uploads directory in the subdirectory `bfi_thumb`, and uses those for future calls. The script automatically checks for updates on the original image then re-creates new images when needed. Any image can be used as long it is in your WordPress instance. Remote images are not supported due to security concerns.

You can change the location of where the images are saved by defining this constant anywhere in your code:

```
// Change the upload subdirectory to wp-content/uploads/other_dir
@define( BFITHUMB_UPLOAD_DIR, 'other_dir' );
```

Why Make a New One?
===================

Aqua Resizer is awesome, WP's Image Editor is awesome, Timthumb is awesome.

When WP reached 3.5 and introduced the Image Editor class, as a theme author I couldn't anymore use Timthumb in my WP themes. An alternative was to use Aqua Resizer instead. Although it did the job quite well, I needed some of the functionalities supported by Timthumb.. I needed to be allowed to grayscale, color tint images and change the opacity of images all while resizing them. I couldn't find anything that did everything I wanted, so I made BFI_Thumb.


Usage (New Method)
==================

***Use this if your image can be accessed by WP functions such as `the_post_thumbnail`***

1. Include / require BFI_Thumb.php into your WordPress files.

```php
require_once('BFI_Thumb.php');
```

2. Call your normal WordPress image functions, specify an array as the `$size` of your image, then add a parameter `'bfi_thumb' => true` to it.

```php
// Getting the featured image without bfi_thumb
the_post_thumbnail( array( 1024, 400 ) );

// With bfi_thumb
the_post_thumbnail( array( 1024, 400, 'bfi_thumb' => true ) );

// With bfi_thumb & with parameters
the_post_thumbnail( array( 1024, 400, 'bfi_thumb' => true, 'grayscale' => true ) );

// Getting an attachment image with bfi_thumb & multiple parameters
$params = array( 400, 300, 'opacity' => 50, 'grayscale' => true, 'colorize' => '#ff0000' );
wp_get_attachment_image_src( $attachment_id, $size )
```

***This method can be used also in other WordPress image functions that accept a `$size` parameter.***

Usage (Old Method that still has it's uses)
===========================================

***Use this if your image cannot be accessed by WP functions such as `the_post_thumbnail`***

1. Include / require BFI_Thumb.php into your WordPress files.

```php
require_once('BFI_Thumb.php');
```

2. Call the function to get the image URL

```php
$params = array( 'width' => 400 );
echo "<img src='" . bfi_thumb( "URL-to-image.jpg", $params ) . "'/>";
```

**$params is an associative array containing the actions to perform on your image.**

Examples:

```php
// Resize by width only
$params = array( 'width' => 400 );
bfi_thumb( "URL-to-image.jpg", $params );

// Resize by width and height
$params = array( 'width' => 400, 'height' => 300 );
bfi_thumb( "URL-to-image.jpg", $params );

// Crop
$params = array( 'width' => 400, 'height' => 300, 'crop' => true );
bfi_thumb( "URL-to-image.jpg", $params );

// Change opacity (makes your image into a PNG)
$params = array( 'opacity' => 80 ); // 80% opaque
bfi_thumb( "URL-to-image.jpg", $params );

// Grayscale
$params = array( 'grayscale' => true );
bfi_thumb( "URL-to-image.jpg", $params );

// Colorize
$params = array( 'color' => '#ff0000' );
bfi_thumb( "URL-to-image.jpg", $params );

// Negate
$params = array( 'negate' => true );
bfi_thumb( "URL-to-image.jpg", $params );

// Multiple parameters
$params = array( 'width' => 400, 'height' => 300, 'opacity' => 50, 'grayscale' => true, 'colorize' => '#ff0000' );
bfi_thumb( "URL-to-image.jpg", $params );
```

<hr>

Changelog
=========

v1.3
* Now integrates in WordPress image functions

v1.2.1
* Fixed T_PAAMAYIM_NEKUDOTAYIM error in PHP 5.2.x

License
=======
GPL 3.0

It's not required but I appreciate if you attribute and/or link back if you find this helpful :)


Social Stuff
============

Twitter: [@bfintal](https://twitter.com/bfintal) & [@gambitph](https://twitter.com/gambitph)

Google+: <a href='https://plus.google.com/113101541449927918834' rel='author'>+Benjamin Intal</a>
