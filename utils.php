<?php

class Paper {

  private static $constants = [];
  private static $settings = [];

  static function constant($name, $value = null)
  {
    if ( $value === null ) {
      if ( array_key_exists($name, self::$constants) ) {
        return self::tag('span', self::$constants[$name], [ "class" => 'paper-constant']);
      } else {
        return self::tag('span', $name, [ "class" => 'paper-constant paper-constant-error']);
      }
    } else {
      self::$constants[$name] = $value;
    }
  }

  static function setting($name, $default = null)
  {
    if ( array_key_exists($name, self::$settings) ) {
      return self::$settings[$name];
    } else {
      return $default;
    }
  }

  static function add_setting($name, $value)
  {
    self::$settings[$name] = $value;
  }

  static function tag($tag, $content, $attributes = [])
  {
    $attr = "";
    foreach ($attributes as $key => $value) {
      $attr .= ' '.htmlspecialchars($key).'="'.htmlspecialchars($value).'"';
    }
    $tag = strip_tags($tag);
    $content = strip_tags($content);
    return '<'.$tag.$attr.'>'.$content.'</'.$tag.'>';
  }
}
