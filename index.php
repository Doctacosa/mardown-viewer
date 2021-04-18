<?php

require_once 'vendor/autoload.php';

use League\CommonMark\GithubFlavoredMarkdownConverter;

if (isset($_REQUEST['debug']))
	var_dump($_REQUEST);

if (empty($_GET['file']))
	die('File parameter messing');

$file = 'notes/'.$_GET['file'].'.md';
if (!is_file($file))
	die('File not found');



$converter = new GithubFlavoredMarkdownConverter([
	'html_input' => false,
	'allow_unsafe_links' => 'true',
	'max_nesting_level' => 15,
	'use_underscore' => false,
]);
$page_content = $converter->convertToHtml(file_get_contents($file));


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Creeper's Lab - Activity graph</title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<style>
		@import "//www.interordi.com/files/css/normalize.css";
		@import "//www.interordi.com/files/css/base.css";
	</style>
</head>

<body>
	<?=$page_content?>
</body>
</html>
