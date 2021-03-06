<?php

class Paper_Setting
{
	static private $data = array();

	static public function instance( $id, $default, Array $options = array())
	{
		if ( array_key_exists( $id, static::$data ) ) {
			return static::$data[$id];
		} else {
			return new static( $id, $default, $options );
		}
	}

	public function Paper_Setting( $id, $default, Array $options = array() )
	{
		$this->id = $id;
		$this->options = $options;

		if ( ! is_array( $this->options ) )
		{
			$this->options = array();
		}

		$this->options['default'] = $default;

		static::$data[$id] = $this;

		add_action( 'customize_register', array( $this, 'apply' ) );
	}

	private $id;
	private $options = array();
	private $section = null;
	private $control = null;

	public function set_section( $id, Array $options = array() )
	{
		if ( $this->section != null )
		{
			return false;
		}

		$options['title'] = Paper_Inflector::humanize( $id );

		$this->section = array(
			'id' => $id,
			'options' => $options
		);

		return $this;
	}

	public function with_color_control()
	{
		$this->control = array(
			'class' => 'WP_Customize_Color_Control',
			'options' => array(
				'label' => Paper_Inflector::humanize( $this->id ),
				'section' => $this->section['id'],
				'settings' => $this->id
			)
		);

		return $this;
	}

	public function with_string_control()
	{
		$section = '';
		if ( is_array($this->section) && array_key_exists( 'id', $this->section ) ) {
			$section = $this->section['id'];
		}
		if ( array_key_exists( 'section', $this->options ) ) {
			$section = $this->options['section'];
		}

		$this->control = array(
			'label' => Paper_Inflector::humanize( $this->id ),
			'type' => 'text',
			'section' => $section
		);

		return $this;
	}

	public function apply()
	{
		global $wp_customize;

		$wp_customize->add_setting( $this->id, $this->options );

		if ( $this->section != null )
		{
			$wp_customize->add_section( $this->section['id'], $this->section['options'] );
		}

		if ( $this->control != null )
		{
			if ( array_key_exists('class', $this->control) ) {
				$control_class = $this->control['class'];
				$wp_customize->add_control( new $control_class( $wp_customize, $this->id, $this->control['options'] ) );
			} else {
				$wp_customize->add_control( $this->id, $this->control );
			}
		}
	}
}
