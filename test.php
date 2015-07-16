<?php
require_once('config.php');
require_once('parsedown.php');
require_once('parsedownextra.php');
	$pd = new Parsedown();
	$page_id = "./pages/home.md";
	echo $pd->text("Hello World **test**");

?>