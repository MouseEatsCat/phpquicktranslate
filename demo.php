<?php include_once('phpquicktranslate.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="robots" content="noindex,nofollow">
		<title>PHP Quick Translate Function Demo</title>
		<style>
			pre{
				background-color: #ececec;
				padding: 5px 10px;
				border-radius: 4px;
				margin-bottom: 5px;
				display: inline-block;
				margin-top: 0px;
			}
			main{
				max-width:900px;
				width:100%;
				margin:auto;
				font-family:sans-serif;
			}
			h2 {
			    margin-bottom: 7px;
					margin-top: 35px;
			}
		</style>
	</head>
	<body>
		<main>
			<h1>PHP Quick Translate Function Demo</h1>
			<a href="?lang=en">Click Here</a> to view the page in English<br>
			<a href="?lang=es">Click Here</a> to view the page in Spanish
			<!-- HERE is the code you'll need to use -->
			<!-- You can do it this way... -->
			<h2>Example 1</h2>
<?= "<pre>&lt;?php
   _et('[:en]English[:es]Español');
?&gt;</pre><br>" ?>
			<span style="color:#F00">Result:</span><br>
			<?php _et('[:en]English[:es]Español'); ?>
			<h2>Example 2</h2>
			<!-- Or like this... -->
<?= '<pre>&lt;?php
   $test = _t(\'[:en]English[:es]Español\');
   echo "This text is prepended to : ".$test;
?&gt;</pre><br>' ?>
			<?php
			$test = _t('[:en]English[:es]Español');
			echo '<span style="color:#F00">Result:</span><br>';
			echo 'This text is prepended to : '.$test;
			?>
		</main>
	</body>
<html>
