<?php

namespace BroLazyLoadImages\Classes;

use BroLazyLoadImages\Utils\Assets;

class ImageAssets {

	use Assets;

	public function __construct() {
		$this->addCss( 'lazy-load-image' );
	}

	public function js_helper() {
		$this->addJs( 'lazy-load-image', 'footer' ,[],'1.0.0');
	}

}