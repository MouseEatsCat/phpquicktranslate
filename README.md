# PHP Quick Translate
[![Packagist](https://img.shields.io/packagist/v/mouseeatscat/phpquicktranslate.svg?style=flat-square)](https://packagist.org/packages/mouseeatscat/phpquicktranslate)

A class for quickly providing multiple translations in PHP.

This script was developed as a personal project by [Michel Descoteaux](https://micheldescoteaux.com) and is free to use and distribute.

## Requirements

* PHP >= 5.6
* Composer - [Install](https://getcomposer.org/download/)

## Examples

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

$lang = !empty($_GET["lang"]) ? $_GET["lang"]: "";
$qt = new MouseEatsCat\PhpQuickTranslate($lang);

echo $qt->t('[:en]English Text[:fr]French Text');
// OR
$qt->et('[:en]English Text[:fr]French Text');
```
If the url is `http://website.com/page.php?lang=en` OR `http://website.com/page.php`
```
English Text
```
If the url is `http://website.com/page.php?lang=fr`
```
French Text
```
[Click Here](https://micheldescoteaux.com/phpquicktranslate/demo.php) to see a demo

## Basic Set Up
This script can be set up by using the following steps:

1. Run `composer require mouseeatscat/phpquicktranslate`

2. Add this code to the top of your php document:
  ```php
  <?php
  require_once __DIR__ . '/vendor/autoload.php';

  $qt = new MouseEatsCat\PhpQuickTranslate("en");
  ```

3. Then use either `$qt->t()` or `$qt->et()` to translate a given string (example):
  ```php
  echo $qt->t('[:en]Hello world[:fr]Bonjour monde');
  // The output is: "Hello world"
  ```

4. You can then change the language at any time using `$qt->changeLanguage()` (example):
  ```php
  $qt->changeLanguage('fr');
  echo $qt->t('[:en]Hello world[:fr]Bonjour monde');
  // The output is: "Bonjour monde"
  ```
