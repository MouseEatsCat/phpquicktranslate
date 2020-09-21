<?php
require_once __DIR__ . '/src/PhpQuickTranslate.php';
use MouseEatsCat\PhpQuickTranslate;

$qt = new PhpQuickTranslate($_GET["lang"]);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>PHP Quick Translate Function Demo</title>
    <style>
        pre {
            background-color: #ececec;
            padding: 5px 10px;
            border-radius: 2px;
            margin-bottom: 5px;
            display: inline-block;
            margin-top: 0px;
        }
        main {
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
        <a href="?lang=en">Click Here</a> to view the page in English (en)<br>
        <a href="?lang=es">Click Here</a> to view the page in Spanish (es)
        <h2>Example 1</h2>
        <pre><?php echo '$qt->et(\'[:en]English[:es]Español\');'; ?></pre>
        <br>
        <span style="color:#F00">Result:</span>
        <?php $qt->et('[:en]English[:es]Español'); ?>
        <h2>Example 2</h2>
        <pre><?php echo '$test = $qt->t(\'[:en]English[:es]Español\');
echo "This text is prepended to: ".$test;';
        ?></pre>
        <br>
        <span style="color:#F00">Result:</span>
        <?php echo 'This text is prepended to: ' . $qt->t('[:en]English[:es]Español'); ?>
    </main>
</body>
<html>
