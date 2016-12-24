<?php

class Paper_Style
{
  private $name = "";
  private $path = "";
  private $options = array();

  public function Paper_Style( $name, $path, Array $options = array() )
  {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

    $this->name = $name;
    $this->path = $path;
    $this->options = $options;
  }

  public function wp_enqueue_scripts()
  {
  	wp_enqueue_style( $this->name, $this->path);
  }
}
