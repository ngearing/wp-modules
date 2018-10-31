<?php

namespace Wp;

class PostTax {

	/**
	 * Name of post tax, lowercase no spaces.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Title of post tax.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Plural of post tax.
	 *
	 * @var string
	 */
	public $plural;

	/**
	 * The post tax object
	 *
	 * @var object
	 */
	public $post_type;

	/**
	 * Array of args.
	 *
	 * @var array
	 */
	private $args;

	public function __construct( $name, $post_type, $args = [] ) {

		$plural = '';
		$title  = '';

		if ( \is_array( $name ) ) {
			$plural = $name[2] ?: false;
			$title  = $name[1] ?: false;
			$name   = $name[0];
		}
		$this->name   = $name;
		$this->title  = $title ?: ucwords( str_replace( [ '-', '_' ], ' ', $name ) );
		$this->plural = $plural ?: $this->pluralize( $this->title );

		$this->post_type = $post_type;

		$this->filter_args( $args );

		$this->taxonomy = register_taxonomy( $this->name, $this->post_type, $this->args );

	}

	private function filter_args( $args ) {
		// For labels.
		$name   = $this->name;
		$title  = $this->title;
		$plural = $this->plural;

		$default_args = [
			'label'             => $name,
			'labels'            => [
				'name'                       => _x( $plural, 'taxonomy general name' ),
				'singular_name'              => _x( $title, 'taxonomy singular name' ),
				'search_items'               => __( "Search $plural" ),
				'popular_items'              => __( "Popular $plural" ),
				'all_items'                  => __( "All $plural" ),
				'parent_item'                => __( "Parent $title" ),
				'parent_item_colon'          => __( "Parent $title:" ),
				'edit_item'                  => __( "Edit $title" ),
				'view_item'                  => __( "View $title" ),
				'update_item'                => __( "Update $title" ),
				'add_new_item'               => __( "Add New $title" ),
				'new_item_name'              => __( "New $title" ),
				'separate_items_with_commas' => __( "Separate $name with commas" ),
				'add_or_remove_items'        => __( "Add or remove $name" ),
				'choose_from_most_used'      => __( "Choose from the most used $name" ),
				'not_found'                  => __( "No $plural found." ),
				'no_terms'                   => __( "No $name" ),
				'items_list_navigation'      => __( "$plural list navigation" ),
				'items_list'                 => __( "$plural list" ),
				'most_used'                  => _x( 'Most Used', $name ),
				'back_to_items'              => __( "&larr; Back to $title" ),
			],
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => false,
		];

		$this->args = array_replace_recursive( $default_args, $args );
	}

	/**
	 * Basic Pluralize function
	 *
	 * @param string $singular Singular form of word.
	 * @return string Pluralized word if quantity is not one, otherwise singular
	 */
	private function pluralize( $singular ) {
		$last_letter = strtolower( $singular[ strlen( $singular ) - 1 ] );
		switch ( $last_letter ) {
			case 'y':
				return substr( $singular, 0, -1 ) . 'ies';
			case 's':
				return $singular . 'es';
			default:
				return $singular . 's';
		}
	}
}
