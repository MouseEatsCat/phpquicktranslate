<?php
/**
PHP Quick Translate
Author: Michel Descoteaux (https://micheldescoteaux.com)
GitHub: https://github.com/MouseEatsCat/phpquicktranslate
*/

include_once("config.php");

if (!function_exists('_t')) {
	function _t($string){
		global $phpQTranslateOptions;
		$langParam = $phpQTranslateOptions["langParam"];
		$useFirstString = $phpQTranslateOptions["useFirstString"];
		$globalDefaultLang = $phpQTranslateOptions["globalDefaultLang"];

		// Set the Default Language
		if (substr_count($string, '[:') > 0) {
			// Find the first instance of a language section in the string
			$tagstart = strpos($string, '[:') + 2;
			$defaultLang = substr($string, $tagstart, strpos($string, ']') - $tagstart);
		} else {
			// Language was not present in string
			return $useFirstString == true ? $string : "";
		}

		// Check to see if the "lang" GET parameter is set and is present in the string
		if (!empty($langParam) && strpos($string, '[:'. $langParam .']') !== false) {
			$defaultLang = $langParam;
		} elseif (!empty($globalDefaultLang)) {
			$defaultLang = $globalDefaultLang;
			if ($useFirstString == false) {
				$defaultLang = $langParam;
			}
		} elseif ($useFirstString == false) {
			$defaultLang = $langParam;
		}

		// CHECKING THE STRING
		if (strpos($string, '[:'. $defaultLang .']') !== false) {
			$tmpString = substr($string, (strpos($string, '[:'. $defaultLang .']') + strlen($defaultLang) + 3));
			if (strpos($tmpString, '[:') !== false) {
				// Language was present in string and a following language section was found
				return substr($tmpString, 0, strpos($tmpString, '[:'));
			} else {
				// Language was present in string and no further languages in string
				return $tmpString;
			}
		} else {
			// Language was not present in string
			return $useFirstString == true ? $string : "";
		}

	}
}

/**
ADDITIONAL FUNCTION
Run the quick translate function and then echo the result
*/
if (!function_exists('_et')) {
	function _et($string){
		echo _t($string);
	}
}
