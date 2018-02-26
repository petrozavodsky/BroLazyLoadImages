<?php

namespace BroLazyLoadImages\Classes;


class Router {


	public function __construct() {

		add_action( 'template_redirect', [ $this, 'payload' ] );

	}

	public function payload() {
		d(
			$GLOBALS['wp_query']
		);
	}
}