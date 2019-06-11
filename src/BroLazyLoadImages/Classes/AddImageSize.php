<?php

namespace BroLazyLoadImages\Classes;


class AddImageSize {

	public static $size='image_32x32';

	public function __construct() {
		add_image_size( self::$size, 32, 32, [ 'center', 'center' ] );
	}
}
