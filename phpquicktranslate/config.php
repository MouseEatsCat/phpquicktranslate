<?php

	# Parameter to use as language
	$langParam = $_GET["lang"];

	# Default language if no language parameter is sent (optional) ex. "fr"
	$globalDefaultLang = "";

	# If no matching translation is found, use first available translation or Default Language
	$useFirstString = true;
