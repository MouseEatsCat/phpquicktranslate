<?php
/*
*	This is the configuration file for the PHP Quick Translate script.
* Change the settings below to modify how it behaves.
*/

	$phpQTranslateOptions = array(
		"langParam" => $_GET["lang"], // Parameter to use as language
		"globalDefaultLang" => "", // Default language if no language parameter is sent (optional) ex. "fr"
		"useFirstString" => true // If no matching translation is found, use first available translation or Default Language
	);
