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

class QuickTranslate
{
    protected string $language;
    protected bool $useFirstString;
    protected array $translations = [];

    /**
     * Initialize QuickTranslate
     *
     * @param string  $language           Current Language
     * @param bool    $useFirstString If no match is found, use first available translation
     */
    public function __construct(string $language = "en", bool $useFirstString = true)
    {
        $this->language       = !empty($language) ? $language : "en";
        $this->useFirstString = $useFirstString;
    }

    /**
     * Translate string
     *
     * @param array|string $translations String(s) you want to translate
     */
    public function t(array|string $translations): array|string
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
        if (!empty($this->language) && !empty($translations[$this->language])) {
            return $translations[$this->language];
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
     */
    private function parseSubstringTranslations(string $string): array
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
     * @param array|string $translations String(s) you want to translate
     */
    public function et(array|string $translations): static
    {
        echo $this->t($translations);
        return $this;
    }

    /**
     * Change current language
     */
    public function setLanguage(string $language): static
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Get current language
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Add a translation.
     *
     * @param string $language Language of the translation.
     * @param string $key Translation key.
     * @param string|null $value Translation value.
     */
    public function addTranslation(string $language, string $key, ?string $value = null): static
    {
        $this->translations[$key][$language] = $value ?? $key;
        return $this;
    }

    /**
     * Add translation JSON source file or directory.
     *
     * @param string      $source   Path to JSON file containing translations OR directory
     * @param string|null $language Language of translations
     *                              (Only required if translations don't contain language codes)
     *
     * @see https://github.com/MouseEatsCat/phpquicktranslate#single-language-json Single language json example.
     * @see https://github.com/MouseEatsCat/phpquicktranslate#multilingual-json Multilingual json example.
     */
    public function addTranslationSource(string $source, ?string $language = null): static
    {
        $sources = [];

        try {
            if (is_dir($source)) {
                $it = new RecursiveDirectoryIterator($source);

                foreach (new RecursiveIteratorIterator($it) as $file) {
                    if ($file->getExtension() === 'json') {
                        $sources[] = [
                            'file' => $file->getRealPath(),
                            'lang' => $language ?? $file->getBasename('.json')
                        ];
                    }
                }
            } elseif ($this::fileIsJson($source) && file_exists($source)) {
                $sources = [[
                    'file' => $source,
                    'lang' => $language
                ]];
            } else {
                throw new InvalidArgumentException(sprintf(
                    'Could not find translation JSON file or directory: "%s"',
                    $source
                ));
            }

            foreach ($sources as $src) {
                $file         = $src['file'];
                $language     = strtolower($src['lang']);
                $translations = json_decode(file_get_contents($file), JSON_OBJECT_AS_ARRAY);

                if ($translations) {
                    $this->addTranslations($translations, $language);
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

        return $this;
    }

    /**
     * Add multiple translations.
     *
     * @param array       $translations  Array of translations to add
     * @param string|null $language          Language of translations
     *                                   (Only required if translations don't contain language codes)
     */
    public function addTranslations(array $translations, ?string $language = null): static
    {
        try {
            foreach ($translations as $translationKey => $translationVal) {
                if (empty($language)) {
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
                } elseif (is_array($translationVal)) {
                    foreach ($translationVal as $langKey => $value) {
                        $this->addTranslation($langKey, $translationKey, $value);
                    }
                } else {
                    $this->addTranslation($language, $translationKey, $translationVal);
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
     * @param string $key Translation key.
     */
    public function hasTranslation(string $key, ?string $language = null): bool
    {
        $language = $language ?? $this->language;
        return isset($this->translations[$key][$language]);
    }

    /**
     * Get translation by key.
     *
     * @param string      $key Translation key.
     */
    public function getTranslation(string $key, ?string $language = null): string
    {
        $value = '';
        $language  = $language ?? $this->language;

        if ($this->hasTranslation($key, $language)) {
            $value = (string)$this->translations[$key][$language];
        }

        return $value;
    }

    /**
     * Determine if file is json using it's extension.
     */
    private static function fileIsJson(string $filePath): bool
    {
        $needle     = '.json';
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($filePath, $needle, - $needle_len));
    }

    /**
     * Clear all translations.
     */
    public function clearTranslations(): static
    {
        $this->translations = [];
        return $this;
    }
}
