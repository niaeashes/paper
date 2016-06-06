<?php

class Paper {

  private static $constants = [];

  static function constant($name, $value = null)
  {
    if ( $value === null ) {
      if ( self::$constants[$name] != null ) {
        return self::tag('span', self::$constants[$name], [ "class" => 'paper-constant']);
      } else {
        return self::tag('span', $name, [ "class" => 'paper-constant paper-constant-error']);
      }
    } else {
      self::$constants[$name] = $value;
    }
  }

  static function tag($tag, $content, $attributes = [])
  {
    $attr = "";
    foreach ($attributes as $key => $value) {
      $attr .= ' '.strip_tags($key).'="'.strip_tags($value).'"';
    }
    $tag = strip_tags($tag);
    $content = strip_tags($content);
    return '<'.$tag.$attr.'>'.$content.'</.$tag.>';
  }
}
