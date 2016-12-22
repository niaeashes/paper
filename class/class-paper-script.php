<?php

class Paper_Script
{
  private $name = "";
  private $path = "";
  private $options = [];

  public function Paper_Script( $name, $path, $options = [] )
  {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

    $this->name = $name;
    $this->path = $path;
    $this->options = $options;
  }

  public function wp_enqueue_scripts()
  {
  	wp_enqueue_script( $this->name, $this->path);
  }
}
