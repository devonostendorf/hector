<!doctype html public "records">
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en"> 
	<head>
		<meta charset="utf-8"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="title" content="Because face it, everyone collects something!"> 
		<meta name="description" content="Keep track of your collections.">
		<meta name="resource-type" content="document"> 
		<meta name="revisit-after" content="10 days"> 
		<meta name="classification" content="consumer"> 
		<meta name="keywords" content="[words,words,keywords]">   
		<meta name="robots" content="noindex, nofollow">
		<meta name="distribution" content="global"> 
		<meta name="rating" content="general"> 
<?php      
		echo '<meta name="copyright" content="2015'.(date('Y') != '2015' ? '-'.date('Y') : '').'">';
?> 
		<meta name="web author" content="Devon Ostendorf - https://devonostendorf.com">    
		<title>
<?php
	echo $title;
?>
		</title>
<?php 
?>
		<link rel="icon" href="media/img/favicon.ico"/>
<?php 
	foreach ($styles as $style) 
	{
		echo HTML::style($style); 
	}
	echo HTML::script('media/js/vendor/modernizr.js');
?>
	</head>
  	<body>
  		<div class="row">
  			<div class="large-12 columns">
<?php 
	echo $navbar;
	echo $content; 
?>
			</div>
		</div>
<?php
	foreach ($scripts as $file)
	{
		echo HTML::script($file);
	}
?>
		<script>
			$(document).foundation();
		</script>
	</body>
<?php	
	if ($set_focus !== '')
	{
		echo '<script>document.'.$set_focus.'.focus();</script>';
	}
?>	
</html>
