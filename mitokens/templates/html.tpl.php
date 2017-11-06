<!DOCTYPE html>

<html class="<?=$classes_html;?>" id="top" lang="<?=$language->language;?>" dir="<?=$language->dir;?>">

	<head>
		<title><?=$head_title;?></title>
		<?=$head;?>
		<meta name="viewport" content="initial-scale=1.0, target-densitydpi=device-dpi, width=device-width" />
		<link href='http://fonts.googleapis.com/css?family=Andika|Source+Code+Pro:400,700|Source+Sans+Pro:400,600,700|Source+Serif+Pro:400,700' rel='stylesheet' type='text/css'>
		<?=$styles;?>
		<?=$scripts;?>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/sites/all/libraries/swfobject/swfobject.js"></script>
		<script type="text/javascript" src="/sites/all/libraries/js-cookie/js.cookie.js"></script>
		<script type="text/javascript" src="https://www.youtube.com/iframe_api"></script>
	</head>
	
	<body class="<?=$classes;?>"<?=$attributes;?>>
		<?=$page_top;?>
		<?=$page;?>
		<?=$page_bottom;?>
	</body>
	
</html>