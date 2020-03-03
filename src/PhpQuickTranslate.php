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
    public function __construct(string $lang, $useFirstString = true)
    {
        $this->lang = empty($lang) ? "en" : $lang;
        $this->useFirstString = $useFirstString;
    }
    
    /**
     * Translate string
     *
     * @param string $string String you want to translate
     * @return string
     */
    public function t($string)
    {
        $translations = $this->getTranslations($string);

        if (empty($translations)) {
            return $string;
        }

        // Check to see if the current lang is set and is present in the string
        if (!empty($this->lang) && !empty($translations[$this->lang])) {
            return $translations[$this->lang];
        } elseif ($this->useFirstString) {
            return reset($translations);
        }
        return $string;
    }

    /**
     * Get translations from string
     *
     * @param string $string
     * @return array|string
     */
    private function getTranslations($string)
    {
        $translations = [];
        preg_match_all('/\[:(\w+)\]([^\[]*)/m', $string, $matches);
        if (count($matches) == 3) {
            foreach ($matches[1] as $index => $match) {
                if (!empty($matches[2][$index])) {
                    $translations[$match] = $matches[2][$index];
                }
            }
        } else {
            return $string;
        }
        return $translations;
    }

    /**
     * Translate and echo
     *
     * @param string $string String you want to translate
     * @return $this
     */
    public function et($string)
    {
        echo $this->t($string);
        return $this;
    }

    /**
     * Change current language
     *
     * @param string $lang
     * @return $this
     */
    public function changeLanguage($lang)
    {
        $this->lang = $lang;
        return $this;
    }
}
