<?php

namespace BroLazyLoadImages\Classes;

class Reformer {

	public function __construct() {


		add_filter( 'wp_get_attachment_image_attributes', function ( $attr, $attachment ) {

			if ( in_array( $attachment->ID, [ 306079,294089,292251,293903,240436 ] ) ) {
				$attr['class'] .= " blur";
			}

			return $attr;
		}, 10, 2 );


		add_filter( 'post_thumbnail_html', function ( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
			// https://github.com/craigbuckler/progressive-image.js
			// https://www.sitepoint.com/how-to-build-your-own-progressive-image-loader/
			#src=[',"]([^"]*)[',"]\h

			if ( in_array( $post_id, [ 293658, 293974, 289777, 293765, 240242 ] ) ) {

				$preview = wp_get_attachment_image_src( (int) $post_thumbnail_id, 'image_60x49' );


				$html = str_replace(
					[ 'src=', 'srcset=', '<img '  ],
					[ 'data-lazy-src=', 'data-lazy-srcset=', "<img src='{$preview[0]}' " ],
					$html
				);

				$res = '';
				$res .= "<div class='lazy-load-img__wrapper'>";
				$res .= $html;
				$res .= "<div style='background-image: url({$preview[0]});' class='lazy-load-img__placeholder'></div>";
				$res .= "</div>";

				return $res;
			}

			return $html;
		}, 10, 5 );
	}


	public function insert_base64_encoded_image_src( $img ) {
		$imageSize = getimagesize( $img );
		$imageData = base64_encode( file_get_contents( $img ) );
		$imageSrc  = "data:{$imageSize['mime']};base64,{$imageData}";

		return $imageSrc;
	}


}