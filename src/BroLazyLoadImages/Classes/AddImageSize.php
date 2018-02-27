<?php

namespace BroLazyLoadImages\Classes;


class AddImageSize {

	public static $size='image_60x49';

	public function __construct() {
		add_image_size( self::$size, 60, 49, [ 'center', 'center' ] );
	}
}