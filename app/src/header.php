<?php
require_once __DIR__ . '/constants.php';
include('db_config_test.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-P0Z003MZ9T"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'G-P0Z003MZ9T');
	</script>
	<link rel="stylesheet" href="<?php echo FA_CSS_URL; ?>" integrity="<?php echo FA_CSS_SRI; ?>" crossorigin="anonymous">
	<style>
		*, *::before, *::after {
			box-sizing: border-box;
		}

		.page-wrapper {
			aspect-ratio: 16/9;
			max-width: 100%;
			scroll-behavior: smooth;
		}

		body {
			width: 100%;
			min-width: 0;
			max-width: 1700px;
			padding: 8px;
			margin: auto;
			box-shadow: 0 10px 40px 0 rgba(0, 0, 0, 0.2), 0 10px 40px 0 rgba(0, 0, 0, 0.19);
			border-radius: 8px;
			background-color: white;
			background-size: auto;
		}

		a:link { color: #000; text-decoration: none; }
		a:visited { text-decoration: none; color: #000; }
		a:hover { text-decoration: none; color: #03F; }
		a:active { text-decoration: none; color: #999; }
	</style>
</head>
