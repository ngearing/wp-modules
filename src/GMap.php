<?php
/**
 * https://developers.google.com/maps/documentation/maps-static/dev-guide
 */

namespace Ngearing\Wp;

class GMap {

	private $markers;
	private $api_key;
	private $hash;

	function __construct( $marker_data = [], $key = '' ) {
		$this->api_key = $key;

		foreach ( $marker_data as $marker ) {
			$this->add_marker( $marker );
		}
	}

	public function add_marker( $marker = [] ) {
		$old_markers = $this->markers ?: [];
		array_push( $old_markers, $marker );
		$this->markers = $old_markers;
	}

	private function get_static() {

		$url = 'https://maps.googleapis.com/maps/api/staticmap';

		$data = [
			'key'     => $this->api_key,
			'center'  => '0,0',
			'size'    => '400x400',
			'zoom'    => 13,
			'scale'   => 1,
			'format'  => 'jpg',
			'maptype' => 'roadmap',
			'markers' => '',
		];

		$curl = curl_init( $url . '?' . http_build_query( $data ) );

	}

	public function render() {
		echo '<pre>' . print_r( $this->markers, true ) . '</pre>';
		printf(
			'<div class="gmap">%s%s</div>',
			$this->render_static(),
			$this->render_markers()
		);
	}

	private function render_static() {}

	private function render_markers() {
		$content = '';

		foreach ( $this->markers as $marker ) :

			$marker_attrs = array_map(
				function( $v, $k ) {
					return "data-$k='$v'";
				}, $marker, array_keys( $marker )
			);

			$content .= sprintf(
				'<div class="marker" %s></div>',
				implode( $marker_attrs )
			);

		endforeach;

		return $content;
	}
}
