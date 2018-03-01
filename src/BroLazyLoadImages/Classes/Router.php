<?php

namespace BroLazyLoadImages\Classes;


class Router {

	public $offset = 2;
	public $exclude = [];

	public function __construct() {
		add_action( 'wp', [ $this, 'payload' ] );
	}

	public function payload() {
		$Assets = new ImageAssets();

		if (  is_front_page() ) {
			$this->offset = 5;
			$Assets->js_helper();
			$this->offset();
			new Reformer( $this->exclude );
		}elseif ( is_category() || is_tag() || is_tax()){
			$this->offset = 8;
			$Assets->js_helper();
			$this->offset();
			new Reformer( $this->exclude );
		}
	}


	public function offset() {
		$array = $GLOBALS['wp_query']->posts;
		if ( count( $array ) >= $this->offset ) {
			$exclude       = array_slice( $array, 0, $this->offset );
			$this->exclude = array_map( function ( $element ) {
				return  $element->ID;
			}, $exclude );
		}
	}

}