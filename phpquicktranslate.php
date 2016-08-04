<?php
/*
* PHP Quick Translate
* Author: Michel Descoteaux (https://micheldescoteaux.com)
* GitHub: https://github.com/MouseEatsCat/phpquicktranslate
*/

if(!function_exists('_t')){
	function _t($string){

		// Set the Default Language
		if(strpos($string, '[:') !== false){
			// Find the first instance of a language section in the string
			$defaultLang = substr($string, (strpos($string, '[:') + 2), (strpos($string, ']') - (strpos($string, '[:') + 2)));
		}else{
			// Language was not present in string
			return $string;
		}

		// Check to see if the "lang" GET parameter is set and is present in the string
		if(!empty($_GET['lang']) && strpos($string, '[:'. $_GET['lang'] .']') !== false){
			$defaultLang = $_GET['lang'];
		}
		// CHECKING THE STRING
		if(strpos($string, '[:'. $defaultLang .']') !== false){
			$tmpString = substr($string, (strpos($string, '[:'. $defaultLang .']') + strlen($defaultLang) + 3));
			if(strpos($tmpString, '[:') !== false){
				// Language was present in string and a following language section was found
				return substr($tmpString, 0, strpos($tmpString, '[:'));
			}else{
				// Language was present in string and no further languages in string
				return $tmpString;
			}
		}else{
			// Language was not present in string
			return $string;
		}
	}
}
/*
* ADDITIONAL FUNCTION
* Run the quick translate function and then echo the result
*/
if(!function_exists('_et')){
	function _et($string){
	  echo _t($string);
	}
}
