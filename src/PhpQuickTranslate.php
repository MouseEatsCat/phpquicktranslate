<?php

/**
PHP Quick Translate
Author: Michel Descoteaux (https://micheldescoteaux.com)
GitHub: https://github.com/MouseEatsCat/phpquicktranslate
*/

namespace MouseEatsCat;

class PhpQuickTranslate
{
    public $lang;
    public $useFirstString;

    /**
     * Initialize PhpQuickTranslate
     *
     * @param string  $lang           Current Language
     * @param boolean $useFirstString If no match is found, use first available translation
     */
    public function __construct($lang = "en", $useFirstString = true)
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
        if (!is_array($translations)) {
            $translations = $this->getTranslations($translations);
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
    private function getTranslations($string)
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
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }
}
