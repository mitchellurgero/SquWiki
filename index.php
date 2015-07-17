<?php
/*
index.php
Framework file: used to parse incoming requests and push those parameters to the functions.php file.
*/
require_once('config.php');
require_once('parsedown.php');
require_once('parsedownextra.php');
require_once('functions.php');
//Begin Parsing request URI to get what file the user needs
$request  = str_replace($pageFolder, "", $_SERVER['REQUEST_URI']);
// $params = end(explode("/"), $request);
$params = split("/", $request);
//List any safe pages the user is allow to view (Any file in pages folder)
$safe_pages = scandir("./pages");
//Process request
  if(in_array($params[1].".md", $safe_pages)) {
    //process page
	echo "<html>";
	echo "\n";
    readPage($params[1]);
    echo "</html>";
  } elseif($params[1] === "") {
    readPage("home");
  } else {
  	//Parse 404 error
	header("Location: $siteURL/404");
	die();
  }
	footer();

?>