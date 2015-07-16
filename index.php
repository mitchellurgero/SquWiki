<?php
require_once('config.php');
require_once('parsedown.php');
require_once('parsedownextra.php');

$request  = str_replace($pageFolder, "", $_SERVER['REQUEST_URI']);
// $params = end(explode("/"), $request);
$params = split("/", $request);
$safe_pages = scandir("./pages");
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
	header("Location: ./404");
  }
	footer();


//functions
function readPage($page){
	$pd = new ParsedownExtra();
	$page_id = "./pages/".$page.".md";
	$lines = file($page_id, FILE_IGNORE_NEW_LINES);
	echo "<head>";
	echo "\n";
	echo'<header>'."\n";
	global $SiteName, $Description;
	echo "<h3>".$SiteName."</h3>"."\n";
	echo $Description."<br />"."\n";
	echo'</header>'."\n";
	menu();
	echo '<div id="content">';
    echo '<div id="mainContent">';
	echo '<section id="intro">';
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
    javascript();
	echo "</head>";
	echo "\n";
	echo '<body>';
	echo "<section>";
	$pageString = file_get_contents($page_id);
	$pageString = implode("\n", array_slice(explode("\n", $pageString), 4));
	echo $pd->text($pageString);
	$filename = $page_id;
	if (file_exists($filename)) {
    	echo '<p style="font-size: 12px;"><i>This page was last modified: '.date ("F d Y H:i:s.", filemtime($filename)).'</i></p>';
	}
	echo "</section>";
	
	echo "</div>"."\n";
	aside();
	echo "</div>";
	
	echo "</body>";
	echo "\n";
}
function menu(){
	$pd = new ParsedownExtra();
	$page_id = "./application/menu.md";
	$lines = file($page_id);
	echo "<nav>"."\n";
	echo "<ul>"."\n";
	foreach($lines as $line_num => $line){
		$line2 = $pd->text($line);
		echo "<li>".$line2."</li>"."\n";
	}
	echo "</ul>"."\n";
	echo "</nav>"."\n";
}
function aside(){
	$pd = new ParsedownExtra();
	$page_id = "./application/aside.md";
	echo "<aside>";
	echo $pd->text(file_get_contents($page_id));
	echo "</aside>";
}
//CSS
function css(){
	echo "<style>";
	echo "\n";
	echo file_get_contents('./css/style.css');
	echo "</style>";
	echo "\n";
}

//Javascript
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