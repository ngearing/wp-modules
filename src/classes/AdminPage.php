<?php
/**
 * Admin page functionality.
 *
 * @package wp
 */

namespace Ngearing\Wp;

/**
 * Admin page class
 *
 * Handles the setting up and render of an admin page.
 */
class AdminPage {

	var $settings = [];

	/**
	 * Setup variables.
	 *
	 * @param string $menu_title The name of admin page.
	 * @param array  $args        Args used to setup the admin page.
	 *  $args = [
	 *    'menu_slug'   => (string) The menu name.
	 *    'page_title'  => (string) Title to display at the top of the page.
	 *    'capability'  => (string) User Capabilty required to view this page.
	 *    'parent_slug' => (string) The parent admin page name.
	 *    'template'    => (string) The full path to template.
	 *    'render_func' => (callable) A callable to render the admin page, instead of a template. (optional)
	 *  ]
	 */
	public function __construct( $menu_title = 'My Plugin', $args = [] ) {

		$this->settings = array_replace_recursive(
			[
				'menu_slug'   => strtolower( str_replace( ' ', '_', $menu_title ) ),
				'page_title'  => "$menu_title Admin Page",
				'capability'  => 'install_plugins',
				'parent_slug' => 'options-general.php',
				'template'    => __DIR__ . 'templates/admin-page-template.php',
				'render_func' => '',
			],
			$args
		);

		$this->title       = $menu_title;
		$this->slug        = isset( $args['menu_slug'] ) ? $args['menu_slug'] : strtolower( str_replace( ' ', '_', $menu_title ) );
		$this->page_title  = isset( $args['page_title'] ) ? $args['page_title'] : "$menu_title Admin Page";
		$this->capability  = isset( $args['capability'] ) ? $args['capability'] : 'install_plugins';
		$this->parent_slug = isset( $args['parent_slug'] ) ? $args['parent_slug'] : 'options-general.php';
		$this->template    = isset( $args['template'] ) ? $args['template'] : false;
		$this->render_func = isset( $args['render_func'] ) ? $args['render_func'] : '';
	}

	/**
	 * Register the menu.
	 *
	 * Should be called in the admin_menu action.
	 *
	 * @return void
	 */
	public function register() {
		call_user_func_array( 'add_submenu_page', $this->get_page_arguments() );
	}

	/**
	 * Render the plugins' admin page.
	 *
	 * @return void
	 */
	public function render_page() {
		$this->render_template();
	}

	/**
	 * Renders the given template if it's readable.
	 *
	 * @return void
	 */
	private function render_template() {
		$template = $this->template;

		if ( ! is_readable( $template ) ) {
			return;
		}

		include $template;
	}

	/**
	 * Get the page arguments to register this admin page.
	 *
	 * @return array
	 */
	public function get_page_arguments() {
		return [
			$this->get_parent_slug(),
			$this->get_page_title(),
			$this->get_title(),
			$this->get_capability(),
			$this->get_slug(),
			$this->get_render_func() ?: [ $this, 'render_page' ],
		];
	}

	public function get_parent_slug() {
		return $this->parent_slug;
	}

	public function get_page_title() {
		return $this->page_title;
	}

	public function get_title() {
		return $this->title;
	}

	public function get_capability() {
		return $this->capability;
	}

	public function get_slug() {
		return $this->slug;
	}

	public function get_template() {
		return $this->template;
	}

	public function get_render_func() {
		return $this->render_func;
	}
}
