<?php

namespace BroLazyLoadImages\Classes;

use BroLazyLoadImages\Utils\Assets;

class ImageAssets {

	use Assets;

	private $version = '1.1.1';

	public function __construct() {
		$this->addCss( 'lazy-load-image', 'header', [], $this->version );
	}

	public function js_helper() {
		$this->addJs( 'lazy-load-image', 'footer', [], $this->version );
	}

}