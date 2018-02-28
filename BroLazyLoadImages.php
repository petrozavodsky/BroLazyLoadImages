<?php

/*
Plugin Name: Bro Lazy Load Images
Plugin URI: http://alkoweb.ru
Author: Petrozavodsky
Author URI: http://alkoweb.ru
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( plugin_dir_path( __FILE__ ) . "includes/Autoloader.php" );

use BroLazyLoadImages\Autoloader;

new Autoloader( __FILE__, 'BroLazyLoadImages' );


use BroLazyLoadImages\Base\Wrap;
use BroLazyLoadImages\Classes\AddImageSize;
use BroLazyLoadImages\Classes\ImageAssets;
use BroLazyLoadImages\Classes\Reformer;
use BroLazyLoadImages\Classes\Router;


class BroLazyLoadImages extends Wrap {
	public $version = '1.0.0';
	public static $textdomine;

	function __construct() {
		self::$textdomine = $this->setTextdomain();

//		if ( current_user_can( 'edit_published_posts' )  ) {
			new AddImageSize();
			new Router();
//		}

	}

}

function BroLazyLoadImages__init() {
	new BroLazyLoadImages();
}

add_action( 'plugins_loaded', 'BroLazyLoadImages__init', 30 );
