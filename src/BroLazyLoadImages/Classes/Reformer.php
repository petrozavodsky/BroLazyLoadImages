<?php

namespace BroLazyLoadImages\Classes;

class Reformer {

	private $exclude = [];

	private $size;

	public function __construct( $exclude ) {

		$this->size = AddImageSize::$size;

		$this->exclude = $exclude;

		add_filter( 'post_thumbnail_html', [ $this, 'image_html' ], 10, 3 );
	}

	public function image_html( $html, $post_id, $post_thumbnail_id ) {


		if ( ! in_array( intval( $post_id ), $this->exclude ) ) {


			$preview = $this->insert_base64_encoded_image_src( $post_thumbnail_id );

			$html = str_replace(
				[ 'src=', 'srcset=', '<img ' ],
				[ 'data-lazy-src=', 'data-lazy-srcset=', "<img src='{$preview}' " ],
				$html
			);

			$res = '';
			$res .= "<div class='lazy-load-img__wrapper'>";
			$res .= $html;
			$res .= "<div style='background-image: url({$preview});' class='lazy-load-img__placeholder'></div>";
			$res .= "</div>";

			return $res;
		}

		return $html;
	}


	public function image_helper( $image_id ) {
		$preview = wp_get_attachment_image_src( $image_id, $this->size );
		return array_shift( $preview );
	}

	public function insert_base64_encoded_image_src( $image_id ) {

		$data = wp_get_attachment_metadata( $image_id );
		$mime = get_post_mime_type( $image_id );

		if ( array_key_exists( $this->size, $data['sizes'] ) ) {
			$file_name = $data['sizes'][ $this->size ]['file'];
		} else if ( 0 < count( $data['sizes'] ) ) {
			$first_size = array_shift( $data['sizes'] );
			$file_name  = $first_size['file'];
		} else {
			$file_name = basename( $data["file"] );
		}

		$dirname    = dirname( $data['file'] );
		$upload_dir = wp_get_upload_dir();
		$file       = $upload_dir['basedir'] . $dirname . '/' . $file_name;

		if ( file_exists( $file ) ) {
			$imageData = base64_encode( file_get_contents( $file ) );
			$imageSrc  = "data:{$mime};base64,{$imageData}";

		}

		return $imageSrc;
	}


}