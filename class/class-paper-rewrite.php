<?php

class PaperRewrite
{
  public static function disable_author()
  {
    add_filter('author_rewrite_rules', ['PaperRewrite', 'return_empty']);
  }

  public static function disable_comments()
  {
    add_filter('comments_rewrite_rules', ['PaperRewrite', 'return_empty']);
  }

  public static function disable_date()
  {
    add_filter('date_rewrite_rules', ['PaperRewrite', 'return_empty']);
  }

  public static function add_rule($regex, $redirect, $after = 'bottom')
  {
    add_action('rewrite_rules_array', function($rules) use ($regex, $redirect, $after) {
    	$newrules = array();
    	$newrules[$regex] = $redirect;
    	return $newrules + $rules;
    });
  }

  public static function return_empty()
  {
    return [];
  }
}
