<?php
/*
config.php
This file is the only *.php file you should edit (unless you know what you are doing of course, but edit at your own risk!)
Each variable is very specific to the wiki working properly, and make sure to follow the same format for any URL or URI variables.
*/
//Basic Variables
$SiteName = "SquWiki - Flat File Wiki"; //Site Name for the Wiki
$logo = ""; //Logo file path (absolute path need ex: http://domain.com/logo.png)
$Description = "The SquWiki Clean and modern PHP Wiki System."; //Description of the Wiki Site.
$AllowPublicEdit = true; //Leave false to disable public editing.
$rootFolder = "code/workspace/squwiki/"; //Folder where SquWiki is stored. (If this is stored in the root folder of your web server use: "./", otherwise follow: "foldername/" and yes the last slash is important.)
$siteURL = "urgero.org/code/workspace/squwiki"; //External URL where the site will be accessed. (No trailing slash) **DO NOT INCLUDE PROTOCOL (http:// or https://) THE SCRIPT HANDLES THAT FOR YOU.
$forceSSL = false; //Force SSL is really just a feature I needed, you can ignore this if you want.


?>