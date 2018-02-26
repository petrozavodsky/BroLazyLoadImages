<?php

namespace BroLazyLoadImages\Classes;

class Reformer {

	private $exclude = [];

	public function __construct( $exclude ) {
		$this->exclude = $exclude;

		add_filter( 'post_thumbnail_html', [ $this, 'image_html' ], 10, 3 );
	}

	public function image_html( $html, $post_id, $post_thumbnail_id ) {

		if ( ! in_array( intval( $post_id ), $this->exclude ) ) {

			$preview = wp_get_attachment_image_src( $post_thumbnail_id, 'image_60x49' );


			$html = str_replace(
				[ 'src=', 'srcset=', '<img ' ],
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
	}


	public function insert_base64_encoded_image_src( $image_id ) {

		$data       = wp_get_attachment_metadata( $image_id );
		$mime       = $data['sizes']['image_60x49']['mime-type'];
		$dirname    = dirname( $data['file'] );
		$upload_dir = wp_get_upload_dir();
		$file       = $upload_dir['basedir'] . $dirname .'/'. $data['sizes']['image_60x49']['file'];

		$imageData = base64_encode( file_get_contents( $file ) );
		$imageSrc  = "data:{$mime};base64,{$imageData}";

		return $imageSrc;
	}


}