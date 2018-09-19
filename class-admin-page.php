<?php
/**
 * Admin page functionality.
 *
 * @package wp
 */

namespace wp;

/**
 * Admin page class
 *
 * Handles the setting up and render of an admin page.
 */
class Admin_Page {

	/**
	 * Arg doc
	 *
	 * @var [type]
	 */
	private $page_args;

	/**
	 * Construct.
	 *
	 * @param string $menu_title
	 * @param string $menu_slug
	 * @param string $page_title
	 * @param string $capability
	 * @param string $parent_slug
	 * @param string $template
	 * @param mixed  $render_func
	 */
	public function __construct(
		$menu_title = 'My Plugin',
		$menu_slug = '',
		$page_title = '',
		$capability = 'install_plugins',
		$parent_slug = 'options-general.php',
		$template_path = __FILE__,
		$render_func = ''
	) {
		$this->page_args = [
			'menu_title'    => $menu_title,
			'menu_slug'     => $menu_slug ?: strtolower( str_replace( ' ', '_', $menu_title ) ),
			'page_title'    => $page_title ?: "$menu_title Admin Page",
			'capability'    => $capability,
			'parent_slug'   => $parent_slug,
			'template_path' => realpath(
				pathinfo( $template_path )['basename'] ?
					pathinfo( $template_path )['dirname'] :
					$template_path
			),
			'render_func'   => $render_func,
		];
	}

	/**
	 * Configure the admin page using Settings API.
	 *
	 * @return void
	 */
	public function configure() {
		// Register setting.
		\register_setting( $this->get_menu_slug(), $this->get_menu_slug() . '_option' );

		// Register section and field.
		\add_settings_section(
			$this->get_menu_slug() . '-section',
			__( 'Section Title', $this->get_menu_slug() ),
			[ $this, 'render_section' ],
			$this->get_menu_slug()
		);
		\add_settings_field(
			$this->get_menu_slug() . '-api-status',
			__( 'My option', $this->get_menu_slug() ),
			$this->get_menu_slug(),
			$this->get_menu_slug() . '-section'
		);
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
	 * Renders the option field.
	 *
	 * @return void
	 */
	public function render_option_field() {
		$this->render_template( 'option_field' );
	}

	/**
	 * Render the plugins' admin page.
	 *
	 * @return void
	 */
	public function render_page() {
		$this->render_template( 'page' );
	}

	/**
	 * Render the top section of plugin's admin page.
	 *
	 * @return void
	 */
	public function render_section() {
		$this->render_template( 'section' );
	}

	/**
	 * Renders the given template if it's readable.
	 *
	 * @param string $template The template name.
	 * @return void
	 */
	private function render_template( $template ) {
		$template_path = $this->get_template_path() . '/' . $template . '.php';

		if ( ! is_readable( $template_path ) ) {
			return;
		}

		include $template_path;
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
			$this->get_menu_title(),
			$this->get_capability(),
			$this->get_menu_slug(),
			$this->get_render_func() ?: [ $this, 'render_page' ],
		];
	}

	public function get_parent_slug() {
		return $this->page_args['parent_slug'];
	}

	public function get_page_title() {
		return $this->page_args['page_title'];
	}

	public function get_menu_title() {
		return $this->page_args['menu_title'];
	}

	public function get_capability() {
		return $this->page_args['capability'];
	}

	public function get_menu_slug() {
		return $this->page_args['menu_slug'];
	}

	public function get_template_path() {
		return $this->page_args['template_path'];
	}

	public function get_render_func() {
		return $this->page_args['render_func'];
	}
}
