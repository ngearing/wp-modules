<?php
/**
 * Post Class
 *
 * @package ngearing
 */

namespace Ngearing\Wp;

/**
 * This class represents a single post.
 */
class Post {

	/**
	 * The post object.
	 *
	 * @var object $post
	 */
	public $post = null;

	/**
	 * The post ID.
	 *
	 * @var integer $ID
	 */
	public $ID;

	/**
	 * The post ID.
	 *
	 * @var string $name
	 */
	public $name;

	/**
	 * The post ID.
	 *
	 * @var string $title
	 */
	public $title;

	/**
	 * The post object type.
	 */
	const POST_TYPE = 'post';

	/**
	 * Get and return post object.
	 *
	 * @param integer $post_id The post ID to get.
	 */
	public function __construct( $post_id ) {
		$post        = get_post( $post_id );
		$this->post  = $post;
		$this->ID    = $post->ID;
		$this->name  = $post->post_name;
		$this->title = $post->post_title;

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
				'post_type' => static::POST_TYPE,
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
