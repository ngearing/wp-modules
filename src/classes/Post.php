<?php

namespace Ngearing\Wp;

/**
 * This class represents a single post.
 */
class Post {

	/**
	 * The post object.
	 */
	var $post;

	/**
	 * The post ID.
	 */
	var $ID;

	/**
	 * The post object type.
	 */
	const post_type = 'post';

	public function __construct( $post_id ) {
		$post       = get_post( $post_id );
		$this->post = $post;
		$this->ID   = $post->ID;

		if ( ! $post ) {
			return false;
		}

	}

	/**
	 * Get post by name or slug.
	 *
	 * @param string $name The name or slug to search by.
	 * @return Ngearing\Wp\Post or bool
	 */
	public static function get_by_name( $name ) {
		$posts = get_posts(
			[
				'post_type' => static::post_type,
				'name'      => $name,
				'fields'    => 'ids',
			]
		);

		if ( $posts ) {
			return new static( array_pop( $posts ) );
		}

		return false;
	}

}
