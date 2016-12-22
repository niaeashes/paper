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
			var zoom = parseInt($(this).attr('data-zoom')) || 17;
	    var map = new google.maps.Map($(this)[0], {
	      center: { lat: lat, lng: lng },
	      zoom: zoom,
	      disableDefaultUI: true,
	      zoomControl: false,
	      scaleControl: false,
	      scrollwheel: false
	    });

			var markers = JSON.parse($(this).attr('data-markers'));
			for ( var i in markers ) {
				var data = markers[i];
		    var marker = new google.maps.Marker({
		      position: { lat: data.lat, lng: data.lng },
		      map: map,
		      title: data.title
		    });
				console.log(data);
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

add_shortcode( 'bartag', 'bartag_func' );
		}
		self::$maps[$map->id()] = $map;
	}

	function short_code( $atts ) {
		$a = shortcode_atts( array(
				'foo' => 'something',
				'bar' => 'something else',
		), $atts );

		return "foo = {$a['foo']}";
	}
  private $id;
	private $options;
  private $lat;
  private $lng;
	private $zoom;
	private $markers = array();

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
    $attributes = [
			'class' => 'paper-map',
			'data-lat' => $this->lat,
			'data-lng' => $this->lng,
			'data-zoom' => $this->zoom,
			'data-markers' => $this->markers_json()
		];
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
		$this->zoom = $zoom;
		return $this;
	}

	public function add_marker($title, $lat, $lng) {
		$marker = new Paper_Map_Marker($this, $title, $lat, $lng);
		array_push($this->markers, $marker);
		return $marker;
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
