<?php

class Paper_Script
{
  private $name = "";
  private $path = "";
  private $deps = [];

  public function Paper_Script( $name, $path, array $deps = array(), $ver = false, $in_footer = false )
  {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

    $this->name = $name;
    $this->path = $path;
    $this->deps = $deps;
    $this->ver = $ver;
    $this->in_footer = $in_footer;
  }

  public function wp_enqueue_scripts()
  {
  	wp_enqueue_script( $this->name, $this->path, $this->deps, $this->ver, $this->in_footer);
  }
}
