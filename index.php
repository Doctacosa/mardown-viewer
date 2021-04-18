<?php

require_once 'vendor/autoload.php';

use League\CommonMark\GithubFlavoredMarkdownConverter;

if (isset($_REQUEST['debug']))
	var_dump($_REQUEST);


$file_requested = '';
if (!empty($_GET['file'])) {
	$file_requested = htmlentities($_GET['file']);
	$file_full = 'notes/'.$_GET['file'].'.md';
}


//Get the list of files
$files_raw = scandir('notes/');
$files = [];
foreach($files_raw as $file_data) {
	//TODO: Handle subdirectories
	if ($file_data == '.' || $file_data == '..' || substr($file_data, -3) != '.md')
		continue;
	$files[] = substr($file_data, 0, -3);
}


//Get the current file
if (isset($file_full) && is_file($file_full)) {
	$converter = new GithubFlavoredMarkdownConverter([
		'html_input' => false,
		'allow_unsafe_links' => 'true',
		'max_nesting_level' => 15,
		'use_underscore' => false,
	]);
	$page_content = $converter->convertToHtml(file_get_contents($file_full));
}


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

		.structure {
			display: flex;
		}

		.files {
			width: 250px;
		}

		.files li {
			padding: 2px;
		}

		.selected {
			background-color: rgba(0, 0, 0, 0.15);
			padding: 3px;
		}

		h2 {
			border-top: 1px solid #BBB;
			padding-top: 10px;
		}

		@media all and (max-width: 700px) {
			.structure {
				display: block;
			}

			.files {
				width: 100%;
				border-bottom: 1px dotted #AAA;
				overflow-y: auto;
				max-height: 200px;
			}
		}
	</style>
</head>

<body>

	<div class="structure">
		<div class="files">
			<h4>Pages</h4>
			<ul>
				<?php
				foreach($files as $file_name) {
					$selected = ($file_name == $file_requested) ? 'selected' : '';
					echo '	<li><a href="'.$file_name.'" class="'.$selected.'">'.$file_name.'</a></li>';
				}
				?>
			</ul>
		</div>

		<div class="main">
			<?php
			if (isset($page_content))
				echo $page_content;
			else
				echo '<p>Select a page.</p>';
			?>
		</div>
	</div>
</body>
</html>
