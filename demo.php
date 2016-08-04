<?php include_once('phpquicktranslate.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="robots" content="noindex,nofollow">
		<title>PHP Quick Translate Function Demo</title>
	</head>
	<body>
		<h1>PHP Quick Translate Function Demo</h1>
		<!-- HERE is the code you'll need to use -->
		<!-- You can do it this way... -->
		<?php _et('[:en]This text should be tranlated in English and French (method 1)[:fr]Ce texte devrait être traduit en français et anglais (method 1)'); ?>
		<br><br>
		<!-- Or like this... -->
		<?= _t('[:en]This text should be tranlated in English and French (method 2)[:fr]Ce texte devrait être traduit en français et anglais (method 2)'); ?>
	</body>
<html>
