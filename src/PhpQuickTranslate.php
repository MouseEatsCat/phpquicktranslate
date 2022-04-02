<?php

/**
PHP Quick Translate
Author: Michel Descoteaux (https://micheldescoteaux.com)
GitHub: https://github.com/MouseEatsCat/phpquicktranslate
*/

namespace MouseEatsCat;

use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PhpQuickTranslate
{
    /** @var string */
    protected $lang;

    /** @var bool */
    protected $useFirstString;

    /** @var array */
    protected $translations = [];

    /**
     * Initialize PhpQuickTranslate
     *
     * @param string  $lang           Current Language
     * @param bool    $useFirstString If no match is found, use first available translation
     */
    public function __construct(string $lang = "en", bool $useFirstString = true)
    {
        $this->lang = !empty($lang) ? $lang : "en";
        $this->useFirstString = $useFirstString;
    }

    /**
     * Translate string
     *
     * @param string|array $translations String(s) you want to translate
     * @return string
     */
    public function t($translations)
    {
        if (is_string($translations)) {
            if ($this->hasTranslation($translations)) {
                return $this->getTranslation($translations);
            }

            $translations = $this->parseSubstringTranslations($translations);
        }

        if (empty($translations)) {
            return $translations;
        }

        // Check to see if the current lang is set and is present in the string
        if (!empty($this->lang) && !empty($translations[$this->lang])) {
            return $translations[$this->lang];
        }

        if ($this->useFirstString) {
            return reset($translations);
        }

        return $translations;
    }

    /**
     * Get translations from string
     * Example: '[:en]English Text[:es]Texto en español'
     *
     * @param string $string
     * @return array
     */
    private function parseSubstringTranslations(string $string)
    {
        $translations = [];

        preg_match_all('/\[:(\w+)]([^\[]*)/m', $string, $matches);

        if (count($matches) === 3) {
            foreach ($matches[1] as $index => $match) {
                if (!empty($matches[2][$index])) {
                    $translations[$match] = $matches[2][$index];
                }
            }
        }

        if (empty($translations)) {
            $translations[] = $string;
        }

        return $translations;
    }

    /**
     * Translate and echo
     *
     * @param string|array $translations String(s) you want to translate
     * @return $this
     */
    public function et($translations)
    {
        echo $this->t($translations);
        return $this;
    }

    /**
     * Change current language
     *
     * @param string $lang
     * @return $this
     */
    public function setLang(string $lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Get current language
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Add a translation.
     *
     * @param string $lang Language of the translation.
     * @param string $key Translation key.
     * @param string|null $value Translation value.
     * @return $this
     */
    public function addTranslation(string $lang, string $key, string $value = null)
    {
        $this->translations[$key][$lang] = $value ?? $key;
        return $this;
    }

    /**
     * Add translation JSON source file or directory.
     *
     * @param string      $source   Path to JSON file containing translations OR directory
     * @param string|null $lang     Language of translations
     *                              (Only required if translations don't contain language codes)
     * @return $this
     *
     * @see https://github.com/MouseEatsCat/phpquicktranslate#single-language-json Single language json example.
     * @see https://github.com/MouseEatsCat/phpquicktranslate#multilingual-json Multilingual json example.
     */
    public function addTranslationSource(string $source, string $lang = null)
    {
        $sources = [];

        try {
            if (is_dir($source)) {
                $it = new RecursiveDirectoryIterator($source);

                foreach (new RecursiveIteratorIterator($it) as $file) {
                    if ($file->getExtension() == 'json') {
                        $sources[] = [
                            'file' => $file->getRealPath(),
                            'lang' => $lang ?? $file->getBasename('.json')
                        ];
                    }
                }
            } elseif ($this::strEndsWith($source, '.json') && file_exists($source)) {
                $sources = [[
                    'file' => $source,
                    'lang' => $lang
                ]];
            } else {
                throw new InvalidArgumentException(sprintf(
                    'Could not find translation JSON file or directory: "%s"',
                    $source
                ));
            }

            foreach ($sources as $src) {
                $file = $src['file'];
                $lang = strtolower($src['lang']);
                $translations = json_decode(file_get_contents($file), JSON_OBJECT_AS_ARRAY);

                if ($translations) {
                    $this->addTranslations($translations, $lang);
                } else {
                    throw new InvalidArgumentException(sprintf(
                        'Invalid JSON in: "%s"',
                        $file
                    ));
                }
            }
        } catch (InvalidArgumentException $e) {
            $message = 'Failed to add translation source: ' . $e->getMessage();
            trigger_error($message, E_USER_WARNING);
        }
    }

    /**
     * Add multiple translations.
     *
     * @param array       $translations  Array of translations to add
     * @param string|null $lang          Language of translations
     *                                   (Only required if translations don't contain language codes)
     * @return $this
     */
    public function addTranslations(array $translations, string $lang = null)
    {
        try {
            foreach ($translations as $translationKey => $translationVal) {
                if (empty($lang)) {
                    // Assume each translation is an array of [lang => value]
                    if (!is_array($translationVal)) {
                        throw new InvalidArgumentException(sprintf(
                            'Missing translations for translation: "%s"',
                            $translationKey
                        ));
                    }
                    foreach ($translationVal as $langKey => $value) {
                        $this->addTranslation($langKey, $translationKey, $value);
                    }
                } else {
                    $this->addTranslation($lang, $translationKey, $translationVal);
                }
            }
        } catch (InvalidArgumentException $e) {
            $message = 'Failed to add translations: ' . $e->getMessage();
            trigger_error($message, E_USER_WARNING);
        }

        return $this;
    }

    /**
     * Determine if translation exists for key.
     *
     * @param string      $key Translation key.
     * @param string|null $lang
     * @return bool
     */
    public function hasTranslation(string $key, string $lang = null): bool
    {
        $lang = $lang ?? $this->lang;
        return isset($this->translations[$key][$lang]);
    }

    /**
     * Get translation by key.
     *
     * @param string      $key Translation key.
     * @param string|null $lang
     * @return string
     */
    public function getTranslation(string $key, string $lang = null): string
    {
        $value = '';
        $lang = $lang ?? $this->lang;

        if ($this->hasTranslation($key, $lang)) {
            $value = (string)$this->translations[$key][$lang];
        }

        return $value;
    }

    /**
     * Determine if string ends with needle.
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    private static function strEndsWith(string $haystack, string $needle): bool
    {
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, - $needle_len));
    }
}
