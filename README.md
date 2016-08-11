# PHP Quick Translate

A quick function for providing mutliple translations in PHP code.

This script was developed as a personal project by [Michel Descoteaux](https://micheldescoteaux.com) and is free to use and distribute.

## Examples

```php
<?php
	include_once('phpquicktranslate.php');
	_et('[:en]English Text[:fr]French Text');
	// OR
	echo _t('[:en]English Text[:fr]French Text');
?>
```
If the url is `http://website.com/page.php?lang=en`
```
English Text
```
If the url is `http://website.com/page.php?lang=fr`
```
French Text
```
<a href="https://micheldescoteaux.com/phpquicktranslate/demo.php" target="_blank">Click Here</a> to see a demo

## How to Set Up
This script can be set up by using the following steps:

1. Save the `phpquicktranslate.php` file into your web directory

2. Add this code to the top of your php document: `<?php include_once('phpquicktranslate.php'); ?>`

3. The script will detect the language based on the "**lang**" `$_GET` parameter. So your url should look like this: `http://website.com/page.php?lang=en` (if no `lang` parameter is specified then the script will look for the first `[:]` section in the string and use that as the default)

4. Then in your php code if your run this function `<?php _et('[:en]Hola[:fr]Senior'); ?>`, the function will echo: `Hola`
