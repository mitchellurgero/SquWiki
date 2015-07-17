<?php
/*
files.php
If public editing is allowed, this file will assit in the security and maintain the files for you (Includes backups, saves, and edits.)
*/
require_once('config.php');
require_once('parsedown.php');
require_once('parsedownextra.php');
require_once('functions.php');
//Parse GET variables


//Parse for edit operation
if(isset($_GET['filename']) && isset($_GET['op'])){
	if($_GET['op'] == "edit"){
		if(canEdit($_GET['filename']) === true){
			getFile($_GET['filename']);
		} else {
			echo 'This file cannot be edited due to administrator security settings, please contact the administrator.';
		}
	}
}

//Parse for save operation
if(isset($_POST['filename']) && isset($_POST['op']) && isset($_POST['content'])){
	if($_POST['op'] == "save"){
		if(canEdit($_POST['filename']) === true){
			saveFile($_POST['filename'], $_POST['content']);
		} else {
			echo 'This file cannot be edited due to administrator security settings, please contact the administrator.';
		}
	}
	
}

//Functions

//getFile will get the contents of a file and return it to the user.
function getFile($filename){
	$file = file_get_contents("./pages/$filename".".md") or die("Unable to open file!");
	echo $file;
	die();
	
}
//saveFile will get content from the user and save it to the file.
function saveFile($filename, $content){
	$now = date("m-d-Y-H-i-s", time());
	copy("./pages/".$filename.".md", "./backupPages/".$filename."_".$now.".md");
	$myfile = fopen("./pages/".$filename.".md", "w") or die("Unable to open file!");
	fwrite($myfile, $content);
	fclose($myfile);
	$pd = new ParsedownExtra();
	readPageEdit($filename);
	
}
//canEdit will verify the file can be edited (Useful for static pages like a home page, about us, or contact page.)
function canEdit($filename){
	global $AllowPublicEdit;
	if($AllowPublicEdit === true){
		if (!file_exists("./lock/".$filename."lock")){
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
	
}

?>