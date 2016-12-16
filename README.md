# paper

## Install

Include your theme.

```bash
cd wp-content/themes/your_theme
git clone https://github.com/niaeashes/paper.git modules
echo 'require get_template_directory() . "/modules/include.php";' >> functions.php
```

Include your wordpress.

```bash
cd wp-content
git clone https://github.com/niaeashes/paper.git modules
echo 'require WP_CONTENT_DIR . "/modules/include.php";' >> themes/your_theme/functions.php
```

## Create custom post type

Define custom post type using method chain.

```php
require get_template_directory() . "/modules/include.php";
(new Paper_Post_Type('custom'))
  ->support_thumbnail()
  ->support_custom_field();
```

Note: 'title' and 'editor' is default supported.

## Add theme supports

### Post thumbnails and Custom image size

```php
Paper_Initializer::instance()
	->support_post_thumbnails()
	->add_image_size( 'custom-size', 800, 600, true );
```

## Add wordpress menu

Define menu location.

```php
new Paper_Menu( 'theme-location' );
```

Show the menu in template file.

```php
<?php echo Paper_Menu::instance( 'theme-location' ) ?>
```

Another methods.

```php
Paper_Menu::exists('theme-location'); // return true when 'theme-location' menu is defined, but false in not defined.
Paper_Menu::instance('theme-location')->has_menu(); // return true when menu is defined on administrator screen.
Paper_Menu::instance('theme-location')
  ->description("Write menu description for Administrator page.");
```

## Constant for Theme

Define the constant in functions.php or another place.

```php
Paper::constant('copyright', "niaeashes");
```

Show the constant.

```php
&copy; <?php echo Paper::constant('copyright') ?>
```

Output.

```html
&copy; <span class="paper-constant">niaeashes</span>
```
