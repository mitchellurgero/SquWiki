<?php
require_once('config.php');
require_once('parsedown.php');
require_once('parsedownextra.php');
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


//functions

/*
The readPage function does exactly as it says on the tin: Read the <page>.md file and output the result to the page
This uses Parsedown & ParsedownExtra to read markdown quickly, then output.
*/
function readPage($page){
	$pd = new ParsedownExtra();
	$page_id = "./pages/".$page.".md";
	//Read file into an array for easy access.
	$lines = file($page_id, FILE_IGNORE_NEW_LINES);
	//Begin output of header
	echo "<head>";
	echo "\n";
	echo'<header>'."\n";
	//Get variables from config.php to determin Site name, Description, and if admin is allowing public edit access.
	global $SiteName, $Description, $AllowPublicEdit;
	echo "<h3>".$SiteName."</h3>"."\n";
	echo $Description."<br />"."\n";
	echo'</header>'."\n";
	menu();
	echo '<div id="content">';
    echo '<div id="mainContent">';
	echo '<section id="intro">';
	//Parse the first 4 lines of <page>.md file for Page Title, description, and keywords (SEO meta data for better Google results.)
	foreach($lines as $line_num => $line)
	{
		// $line = str_replace("\n","", $line);
		if(substr($line, 0, strlen("title:")) === "title:"){
			echo "<title>".str_replace("title: ","", $line)." | ".$SiteName."</title>";
			echo "\n";
		} else {

		}
		if(substr($line, 0, strlen("description:")) === "description:"){
			echo '<meta name="description" content="'.str_replace("description: ","", $line).'">';
			echo "\n";
		}
		if(substr($line, 0, strlen("keywords:")) === "keywords:"){
			echo '<meta name="keywords" content="'.str_replace("keywords: ","", $line).'">';
			echo "\n";
		}

		//echo "<br />"."\n";
	}
	echo "</section>";
	css();
	echo "\n";
	//Output any javascript that may or may not be needed. (IF the file is blank, then nothing will be there.) **Jquery is already added from a CDN for ease of use as well.
    javascript();
	echo "</head>";
	echo "\n";
	echo '<body>';
	echo '<section id="bodyContents">';
	$pageString = file_get_contents($page_id);
	//Remove first four lines of the page array (Not the page file) so we do not output it to the page in HTML.
	$pageString = implode("\n", array_slice(explode("\n", $pageString), 4));
	//Push page through the ParsedownExtra class
	echo $pd->text($pageString);
	$filename = $page_id;
	if (file_exists($filename)) {
    	echo '<p style="font-size: 12px;"><i>This page was last modified: '.date ("F d Y H:i:s.", filemtime($filename)).'</i></p>';
    	if($filename != "./pages/404.md"){
    		//If page is not the 404 page, then detect if Public edits is on.
    		if($AllowPublicEdit === true){
    			echo'<p><a href="#">edit this page</a></p>';
    		}
    	}
	}
	echo "</section>";
	
	echo "</div>"."\n";
	aside();
	echo "</div>";
	
	echo "</body>";
	echo "\n";
}
/*
Simple Menu function to get a basic menu, file is in: ./application/menu.md 
Use this file to build the top menu.
*/
function menu(){
	$pd = new ParsedownExtra();
	$page_id = "./application/menu.md";
	$lines = file($page_id);
	echo "<nav>"."\n";
	echo "<ul>"."\n";
	//We put the menu in a for loop to separate the links into the nav properly.
	foreach($lines as $line_num => $line){
		$line2 = $pd->text($line);
		echo "<li>".$line2."</li>"."\n";
	}
	echo "</ul>"."\n";
	echo "</nav>"."\n";
}
/*
The aside function is used to build a sidebar of sorts, very basic, but supports all your HTML and javascript easily. This file is: ./application/aside.md
This file is not put through a for loop so that everything in the file is put right next to each other (Not in looks, but in code.)
*/
function aside(){
	$pd = new ParsedownExtra();
	$page_id = "./application/aside.md";
	echo "<aside>";
	echo $pd->text(file_get_contents($page_id));
	echo "</aside>";
}
//CSS - Self-explanitory, Parses the ./css/style.css into the html (As opposeed to a seprate file) This function is only temporary because of an htacceess bug I am working on.
function css(){
	echo "<style>";
	echo "\n";
	echo file_get_contents('./css/style.css');
	echo "</style>";
	echo "\n";
}

//Javascript - Parses the ./js/index.js file into the HTML.
function javascript(){
	echo '';
	?>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
	<?php
	echo "<script>";
	echo "\n";
	echo file_get_contents('./js/index.js');
	echo "</script>";
	echo "\n";
}
//Footer - You may not change this due to TOS and Copyright laws.
function footer(){
	echo '';
	?>
	<footer>
		<div>
			<section id="about">
				<header>
					<h3>About</h3>
				</header>
				<p>Copyright &copy; 2016 URGERO.ORG & SquWiki<br>This Wiki script is completely open source, and is available to download through github. Links are to the right.</p>
			</section>
			<section id="blogroll">
				<header>
					<h3>Links to resources used</h3>
				</header>
				<ul>
					<li><a href="https://urgero.org/">URGERO.ORG</a></li>
					<li><a href="http://parsedown.org/">Parsedown</a></li>
					<li><a href="https://github.com/mitchellurgero/SquWiki">GitHub</a></li>
				</ul>
			</section>
			<section id="popular">
				<header>
					<h3>Author</h3>
				</header>
				<p>Coded with &#10084; by Mitchell Urgero</p>
			</section>
		</div>
	</footer>
	
	<?php
	
	
}

?>