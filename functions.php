<?php

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
	echo "<article>";
	$pageString = file_get_contents($page_id);
	//Remove first four lines of the page array (Not the page file) so we do not output it to the page in HTML.
	$pageString = implode("\n", array_slice(explode("\n", $pageString), 4));
	//Push page through the ParsedownExtra class
	echo $pd->text($pageString);
	$filename = $page_id;
	if (file_exists($filename)) {
    	echo '<p style="font-size: 12px;"><i>This page was last modified: '.date ("F d Y H:i:s.", filemtime($filename)).'</i></p>';
    	$filename = str_replace("./pages/","",$filename);
    	$filename = str_replace(".md","",$filename);
    	if($filename != "404"){
    		if(!file_exists("./lock/".$filename.".lock")){
    		//If page is not the 404 page, then detect if Public edits is on.
    			if($AllowPublicEdit === true){
    				echo '<p id="pageValue" hidden>'.$filename.'</p>';
    				echo "<p><a href=\"#\" id=\"editButton\" onClick=\"getPage()\">edit this page</a></p>";
    			}
    		}
    	}
	}
	echo "</article>";
	echo "</section>";
	
	echo "</div>"."\n";
	editJavaScript($filename);
	aside();
	echo "</div>";
	
	echo "</body>";
	echo "\n";
	
}
function readPageEdit($page){
	$pd = new ParsedownExtra();
	$page_id = "./pages/".$page.".md";
	echo "<article>";
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
    			$filename = str_replace("./pages/","",$filename);
    			$filename = str_replace(".md","",$filename);
    			echo '<p id="pageValue" hidden>'.$filename.'</p>';
    			echo "<p><a href=\"#\" id=\"editButton\" onClick=\"getPage()\">edit this page</a></p>";
    		}
    	}
	}
	echo "</article>";
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
    <script src='https://code.jquery.com/jquery-2.1.4.min.js'></script>
	<?php
	global $AllowPublicEdit;
	echo "<script>";
	echo "\n";
	echo file_get_contents('./js/index.js');
	echo "</script>";
	echo "\n";
	
	
}
function editJavaScript($file){
	//$file = "./pages/".$file.".md";
	global $siteURL, $AllowPublicEdit;
	if ($AllowPublicEdit === true){
		echo '';
		?>
		<script>
		function getPage(){
    		 $.ajax({
  				type: "GET",
  				url: "<?php echo $siteURL; ?>/files.php",
  				data: "filename=<?php echo $file; ?>&op=edit",
  				success: function(msg){
  						//alert(msg + "success");
        				document.getElementById('bodyContents').innerHTML = '<textarea id="newContent" style="height:400px; width:900px;">' + msg + '</textarea><br /><input type="button" id="save" name="save" value="Save Page" onClick="savePage()" />';
  				},
  				error: function(XMLHttpRequest, textStatus, errorThrown) {
     					alert(XMLHttpRequest.responseText);
  				}
				});
    		
		}//);
		function savePage(){
    		 $.ajax({
  				type: "POST",
  				url: "<?php echo $siteURL; ?>/files.php",
  				data: {
  					filename: "<?php echo $file; ?>",
  					op: "save",
  					content: document.getElementById('newContent').value
  				},
  				success: function(msg){
  						//alert(msg + "success");
        				document.getElementById('bodyContents').innerHTML = msg;
  				},
  				error: function(XMLHttpRequest, textStatus, errorThrown) {
     					alert(XMLHttpRequest.responseText);
  				}
				});
    		
		}
		</script>
		<?php
	}
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