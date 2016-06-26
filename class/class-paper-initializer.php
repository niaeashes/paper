<?php

/**
 * This is singleton.
 */
class Paper_Initializer
{
	static private $instance;

	private function Paper_Initializer()
	{
		add_action( 'init', array( $this, 'wp_init' ), 0 );
	}

	public static function instance()
	{
		if ( self::$instance == null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private $_supports = array();
	private $_image_sizes = array();

	public function theme_support( $name )
	{
		array_push( $this->_supports, $name );
		return $this;
	}

	public function support_post_thumbnails()
	{
		return $this->theme_support( 'post-thumbnails' );
	}

	public function support_title_tag()
	{
		return $this->theme_support( 'title-tag' );
	}

	public function add_image_size( $name, $width, $height, $crop = false )
	{
		array_push( $this->_image_sizes, array( $name, $width, $height, $crop) );
		return $this;
	}

	/**
	 * @wordpress_action init
	 */
	public function wp_init()
	{
		foreach ( $this->_supports as $support ) {
			add_theme_support( $support );
		}

		foreach ( $this->_image_sizes as $image_size ) {
			call_user_func_array( 'add_image_size', $image_size );
		}
	}
}
