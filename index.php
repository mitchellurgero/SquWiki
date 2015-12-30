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
$request  = str_replace($rootFolder, "", $_SERVER['REQUEST_URI']);
//Detect Force SSL
if($forceSSL === true){
	//Remove the trailing slash when there is a request to the server. (ForceSSL Only)
	$siteURL = rtrim($siteURL , "/");
	if(is_ssl() === false){
		if($request == "/"){
			header("Location: https://".$siteURL);
			die();
		} else {
			header("Location: https://".$siteURL.$request);
			die();
		}
	}
}
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
	read404($params[1]);
  }
	footer();

?>
