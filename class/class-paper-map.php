<?php

class Paper_Map
{
	static public function instance( $id, Array $options = array() )
	{
		if ( array_key_exists( $id, self::$maps ) ) {
			return self::$maps[ $id ];
		} else {
			return new static( $id, $options );
		}
	}

	static public function exists( $id )
	{
		return array_key_exists( $id, self::$maps );
	}

	static public function init_script()
	{
?>
<script>
	( function( $ ) {
		$('.paper-map').each(function(){
	    var lat = parseFloat($(this).attr('data-lat'));
	    var lng = parseFloat($(this).attr('data-lng'));
			var options = JSON.parse($(this).attr('data-options'));
			options.center = { lat: lat, lng: lng }
	    var map = new google.maps.Map($(this)[0], options);
			var markers = JSON.parse($(this).attr('data-markers'));
			for ( var i in markers ) {
				var data = markers[i];
		    var marker = new google.maps.Marker({
		      position: { lat: data.lat, lng: data.lng },
		      map: map,
		      title: data.title
		    });
			}
	  })
	} )( jQuery )
</script>
<?php
	}

	static protected $maps = array();

	static protected function add_instance( Paper_Map $map )
	{
		if ( count( self::$maps ) == 0 ) {
			add_action('wp_footer', array( 'Paper_Map', 'init_script' ) );
			add_shortcode('paper-map', array('Paper_Map', 'short_code'));
		}
		self::$maps[$map->id()] = $map;
	}

	static public function short_code( $atts )
	{
		$a = shortcode_atts( array(
			'id' => ''
		), $atts );

		$id = $a['id'];

		if ( ! array_key_exists( $id, static::$maps ) ) {
			return;
		}

		$map = static::$maps[$id];

		return '<div class="paper-map-container">'.$map.'</div>';
	}

	static public function enable_short_code()
	{
		//add_shortcode('paper-map', array('Paper_Map', 'short_code'));
	}

  private $id;
	private $options;
  private $lat;
  private $lng;
	private $map_attributes = array(
		'zoom' => 17,
		'disableDefaultUI' => true,
		'zoomControl' => false,
		'scaleControl' => false,
		'scrollwheel' => false
	);
	private $markers = array();

  public function Paper_Map( $id, Array $options = array() )
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
    $attributes = array(
			'class' => 'paper-map',
			'data-lat' => $this->lat,
			'data-lng' => $this->lng,
			'data-options' => $this->map_attributes_json(),
			'data-markers' => $this->markers_json()
		);
    return Paper::tag('div', '', $attributes);
  }

  public function id()
  {
    return $this->id;
  }

  /**
   * Support method chain.
   */
  public function latlng($lat, $lng)
  {
    $this->lat = $lat;
    $this->lng = $lng;
    return $this;
  }

	public function zoom($zoom)
	{
		return $this->set_attribute('zoom', $zoom);
	}

	public function set_attribute($name, $value)
	{
		$this->map_attributes[$name] = $value;
		return $this;
	}

	public function add_marker($title, $lat, $lng) {
		$marker = new Paper_Map_Marker($this, $title, $lat, $lng);
		array_push($this->markers, $marker);
		return $marker;
	}

	private function map_attributes_json()
	{
		return json_encode($this->map_attributes);
	}

	private function markers_json()
	{
		$output = array();
		foreach ( $this->markers as $marker ) {
			array_push($output, $marker->to_object());
		}
		return json_encode($output);
	}
}

class Paper_Map_Marker {

	public function Paper_Map_Marker(Paper_Map $map, $title, $lat, $lng) {
		$this->map = $map;
		$this->title = $title;
		$this->lat = $lat;
		$this->lng = $lng;
	}

  private $title = '';
  private $lat;
  private $lng;

	public function add_marker($title, $lat, $lng) {
		return $this->map->add_marker($title, $lat, $lng);
	}

	public function to_object() {
		return (object) array(
			'title' => $this->title,
			'lat' => $this->lat,
			'lng' => $this->lng
		);
	}
}
