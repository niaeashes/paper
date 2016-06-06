<?php

/**
 * This is wrapper of single wordpress menu.
 *
 * use in functions.php
 * <usage>
 * new Paper_Menu("primary", __( 'Primary Menu', 'theme-slug' ));
 * </usage>
 */
class Paper_Menu
{
	static public function instance( $location )
	{
		if ( array_key_exists( $location, self::$menus ) ) {
			return self::$menus[ $location ];
		} else {
			if ( WP_DEBUG ) {
				echo '<pre class="debug-message">';
				var_export( self::$menus[ $location ] );
				echo '</pre>';
			}
			return null;
		}
	}

	static public function exists( $location )
	{
		return array_key_exists( $location, self::$menus );
	}

	static protected $menus = array();

	static protected function add_instance( Paper_Menu $menu )
	{
		self::$menus[$menu->location()] = $menu;
	}

	private $location;
	private $description;
	private $options;

	/**
	 * Initialize wordpress menu, But don't register for theme.
	 * Add Paper_Menu::register method to wordpress actions for calling register_nav_menu builtin function.
	 *
	 * @param string $location    Menu location identifier, like a slug.
	 * @param array  $options     Menu options is used as arguments of wp_nav_menu function.
	 * @param string $description Menu location descriptive text.
	 */
	public function Paper_Menu( $location, $options = array(), $description = 'no description' )
	{
		add_action( 'after_setup_theme', array( $this, 'register' ) );

		$this->location = $location;

		$this->options = array_merge(array(
		), $options);
		unset($this->options['theme_location']);

		$this->description = $description;

		/* Add this instance to static collection. */
		self::add_instance($this);
	}

	/**
	 * Register wordpress menu after setup theme.
	 *
	 * @wordpress-action after_setup_theme
	 * @return null
	 */
	public function register()
	{
		register_nav_menu( $this->location, $this->description );
	}

	public function location()
	{
		return $this->location;
	}

	public function description()
	{
		return $this->description;
	}

	public function has_menu()
	{
		return has_nav_menu( $this->location );
	}

	/**
	 * Build menu html from Paper_Menu instance.
	 *
	 * container_class option is automatically added theme location name.
	 */
	public function __toString()
	{
		try {
			/* Add theme location to container_class */
			if ( ! array_key_exists( 'container_class', $this->options ) ) $this->options['container_class'] = '';
			$this->options['container_class'] = $this->options['container_class'].' '.$this->location;

			return wp_nav_menu(array_merge($this->options, array(
				'theme_location' => $this->location,
				'echo' => false
			) ) );
		} catch ( Exception $e ) {
			return "";
		}
	}
}
