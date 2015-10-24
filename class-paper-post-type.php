<?php

class Paper_Post_Type
{
	public function Paper_Post_Type( $post_type, $options )
	{
		$this->post_type = $post_type;
		$this->options = $options;
		$this->supports = $options['supports'];
		add_action( 'init', [$this, 'init'], 0 );
	}

	private $post_type;
	private $options;
	private $supports;

	public function init()
	{
		$labels = [
			'singular_name' => self::singularize(ucfirst($this->post_type))
		];
		$options = array_merge([
			'label' => self::pluralize(ucfirst($this->post_type)),
			'labels' => $labels,
			'rewrite' => [ 'slug' => $this->post_type ],
			'public' => true,
			'has_archive' => true
		], $this->options);
		$options['supports'] = array_merge($this->supports, ['title', 'editor']);
		register_post_type( $this->post_type, $options );
	}

	static public function pluralize( $name )
	{
		return $name.'s';
	}

	static public function singularize( $name )
	{
		return preg_replace( '/s$/i', '', $name.'s' );
	}
}