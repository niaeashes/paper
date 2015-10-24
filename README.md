# paper

## Install

```bash
cd wp-content/themes/your_theme
git clone git@github.com:niaeashes/paper.git modules
echo 'require get_template_directory() . "/modules/include.php";' > functions.php
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
