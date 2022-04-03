# PHP Quick Translate
[![Packagist](https://img.shields.io/packagist/v/mouseeatscat/phpquicktranslate.svg?style=flat-square)](https://packagist.org/packages/mouseeatscat/phpquicktranslate)

A class for quickly providing multiple translations in PHP.

This script was developed as a personal project by [Michel Descoteaux](https://micheldescoteaux.com) and is free to use and distribute.

## Requirements

* PHP >= 7.4
* Composer - [Install](https://getcomposer.org/download/)

## Installation
1. Run `composer require mouseeatscat/phpquicktranslate`

2. Add this code to the top of your php document:
  ```php
  <?php

  use MouseEatsCat\PhpQuickTranslate\PhpQuickTranslate;

  require_once __DIR__ . '/vendor/autoload.php';

  // Get the current language
  $lang = !empty($_GET['lang']) ? $_GET['lang']: 'en';

  // Instantiate PHP Quick Translate
  $qt = new PhpQuickTranslate($lang);
  ```

You can optionally add a [mulilingal](#single-language-json-example) JSON translation file or multiple [single-language](#single-language-json-example) files.
```php
// Single-Language JSON files
$qt->addTranslationSource('/translations/en.json', 'en');
$qt->addTranslationSource('/translations/fr.json', 'fr');
// OR Multilingual JSON file
$qt->addTranslationSource('/translations/multilingual.json');
// OR Path to translations directory
$qt->addTranslationSource('/translations/');
```
You can then load a translation by it's key:
```php
echo $qt->t('translation_key');
// OR echo using a method
$qt->et('translation_key');
```
### JSON translation file examples
#### Single-Language JSON Example
Assuming you have one json file for each language. Each file would contain something like this:
```json
{
	"translation_example": "Translated Text"
}
```
#### Multilingual JSON Example
Assuming you have one json file containing all translations for all languages. The file would contain something like this:
```json
{
	"translation_example": {
		"en": "English Translated Text",
		"fr": "French Translated Text"
	}
}
```
### Translating without translation files
You can also create a one-time translation as you go. (both alternatives below are equivalent)
```php
$qt->et([
  'en' => 'English Test',
  'fr' => 'French Text'
]);

// OR Alternative

$qt->et('[:en]English Text[:fr]French Text');
```
If the url is `http://website.com/?lang=en` OR `http://website.com/`, the result will be:
```
English Text
```
If the url is `http://website.com/?lang=fr`, the result will be:
```
French Text
```

## PHPQuickTranslate Methods
```php
// Instantiate PHP Quick Translate
$qt = new PHPQuickTranslate('lang');

// Translate
$qt->t('translation_key');

// Same as t() except with echo
$qt->et('translation_key');

// Set the current language
$qt->setLang('lang');

// Get the current language
$qt->getLang();

// Add a translation
$qt->addTranslation('lang', 'translation_key', 'translation_value');

// Add a JSON translation source file directory
// translation source file directory
$qt->addTranslationSource('path/to/json/translations/');
// Individual translation source file
$qt->addTranslationSource('path/to/json/translations/lang.json', 'lang');
$qt->addTranslationSource('path/to/json/translations/multilingual.json');

// Add array or translations
// (single language + multilingual)
$qt->addTranslations(['translation_key' => 'translation_value'], 'lang');
$qt->addTranslations([
  'translation_key' => [
    'en' => 'translation_value',
    'fr' => 'translation_value'
  ]
]);

// Determine if a translation exists for given key
// If lang isn't provided, the current language will be used
$qt->hasTranslation('translation_key', 'lang');

// Gets the translation for given key
// If lang isn't provided, the current language will be used
$qt->getTranslation('translation_key', 'lang');
```
