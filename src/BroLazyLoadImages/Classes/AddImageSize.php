<?php

namespace BroLazyLoadImages\Classes;


class AddImageSize {

	public function __construct() {
		add_image_size( 'image_60x49', 60, 49, [ 'center', 'center' ] );
	}
}