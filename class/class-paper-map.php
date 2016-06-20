<?php

class Paper_Map
{
	static public function instance( $id )
	{
		if ( array_key_exists( $id, self::$maps ) ) {
			return self::$maps[ $id ];
		} else {
			if ( WP_DEBUG ) {
				echo '<pre class="debug-message">';
				var_export( self::$maps[ $id ] );
				echo '</pre>';
			}
			return null;
		}
	}

	static public function setup( $id, $options = array() )
	{
		return new static($id, $options);
	}

	static public function exists( $id )
	{
		return array_key_exists( $id, self::$maps );
	}

	static protected $maps = array();

	static protected function add_instance( Paper_Map $map )
	{
		self::$maps[$map->id()] = $map;
	}

  private $id;
	private $options;
  private $title = '';
  private $latlng = false;
  private $lat;
  private $lng;

  public function Paper_Map( $id, $options = array() )
  {
   $this->id = $id;

   $this->options = array_merge(array(
   ), $options);
   unset($this->options['theme_location']);

   /* Add this instance to static collection. */
   static::add_instance($this);
  }

  public function __toString()
  {
    $attributes = ['id' => 'map', 'data-title' => $this->title];
    if ( $this->latlng ) {
      $attributes['data-lat'] = $this->lat;
      $attributes['data-lng'] = $this->lng;
    }
    return Paper::tag('div', '', $attributes);
  }

  public function id()
  {
    return $this->id;
  }

  /**
   * Support method chain.
   */
  public function title($title)
  {
    $this->title = $title;
    return $this;
  }

  /**
   * Support method chain.
   */
  public function latlng($lat, $lng)
  {
    $this->latlng = true;
    $this->lat = $lat;
    $this->lng = $lng;
    return $this;
  }
}
