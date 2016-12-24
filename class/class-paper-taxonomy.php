<?php

class Paper_Taxonomy
{
  private $name = "";
  private $object_type = "";
  private $options = array();

  public function Paper_Taxonomy( $name, $object_type = 'page', $options = array() )
  {
		add_action( 'init', array( $this, 'wp_init' ) );

    $this->name = $name;
    $this->object_type = $object_type;
    $this->options = $options;
  }

  /**
   * @method_chain
   */
  public function object_type( $object_type )
  {
    $this->object_type = $object_type;
    return $this;
  }

  /**
   * @method_chain
   */
  public function label( $label )
  {
    $this->options['label'] = $label;
    return $this;
  }

  /**
   * @method_chain
   */
  public function hierarchical( $hierarchical )
  {
    $this->options['hierarchical'] = $hierarchical;
    return $this;
  }

	/**
	 * @wordpress_action init
	 */
   public function wp_init()
   {
     register_taxonomy($this->name, $this->object_type, $this->options);
   }
}
