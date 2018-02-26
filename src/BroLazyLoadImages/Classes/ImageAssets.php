<?php

namespace BroLazyLoadImages\Classes;

use BroLazyLoadImages\Utils\Assets;

class ImageAssets {

	use Assets;

	public function __construct() {
		$this->addCss( 'lazy-lasy-load-image' );

		$this->addJs( 'lazy-lasy-load-image','footer');
	}


}