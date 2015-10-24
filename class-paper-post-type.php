<?php

class Paper_Post_Type
{
	public function Paper_Post_Type( $post_type, $options = array() )
	{
		$this->post_type = $post_type;
		$this->options = $options;
		if ( array_key_exists('supports', $options) ) {
			$this->supports = array_merge($options['supports'], $this->supports );
		}
		add_action( 'init', array( $this, 'init' ), 0 );
	}

	private $post_type;
	private $options = array();
	private $supports = array( 'title', 'editor' );

	public function init()
	{
		$labels = array(
			'singular_name' => Paper_Inflector::singularize(ucfirst($this->post_type))
		);
		$options = array_merge(array(
			'label' => Paper_Inflector::pluralize(ucfirst($this->post_type)),
			'labels' => $labels,
			'rewrite' => array( 'slug' => $this->post_type ),
			'public' => true,
			'has_archive' => true
		), $this->options);
		$options['supports'] = $this->supports;
		register_post_type( $this->post_type, $options );
	}

	static private $_allowed_supports = array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom_fields', 'comments', 'revisions', 'page-attributes', 'post-formats');

	public function __call($name, $arguments)
	{
		if ( preg_match( '/^support_(' . str_replace('-', '_', implode(self::$_allowed_supports, '|')) . ')$/', $name, $matches ) ) {
			return $this->add_support( str_replace('_', '-', $matches[1]) );
		}
	}

	/**
	 * Add support to this post type.
	 *
	 * You can use alias name is wraped by __call magic method.
	 *
	 * @alias add_support('title') => support_title()
	 * @alias add_support('custom-field') => support_custom_field()
	 * @param string in 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats'.
	 */
	public function add_support( $value )
	{
		if ( in_array( $value, self::$_allowed_supports ) ) {
			array_push( $this->supports, $value );
		} else {
			// TODO: Warning
		}
		return $this;
	}
}