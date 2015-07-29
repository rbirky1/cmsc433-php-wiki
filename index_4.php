<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="css/style.css" />
    <title>Wiki</title>
  </head>
  <body>

    <div id="sidebar">
      <h2>Navigation</h2>
      <div class="section">
        <ul>
          <li><a href="./index.php?page=Main+Page">Main Page</a></li>
          <li><a href="./index.php?page=Recent+Changes">Recent Changes</a></li>
          <li><a href="./index.php?page=Random+Page">Random Page</a></li>
          <li><a href="./index.php?page=All+Pages">All Pages</a></li>
        </ul>
      </div>
    </div>


<?

   if( !$_GET['page'] ) {
      $page = "Main Page";
   } else {
      $page = $_GET['page'];
   }

   $dir = "/home/csee1/rbirky1/www-data/read-write/wiki/";
   $path = $dir.$page;

   if ($_GET['page'] == "All Pages" ){
   
        getAll($dir);

   } elseif ($_GET['page'] == "Recent Changes"){

	getRecent($dir);

   }elseif ($_GET['page'] == "Random Page"){

       $dirc = $dir."*";
       $pages = glob($dirc);
       $n = rand(0,count($pages)-1);
       $p = basename($pages[$n]);
       viewPage($p,$dir);

   } else {

   if ($_GET['edit']){
	editPage($page, $dir);
   } elseif ($_GET['delete']){
	deletePage($page);
   } else {
	viewPage($page, $dir);
   }
}

function replaceWiki($str,$t){
   $htmlstr = $str;
   $regexs = array(
      "/'{3}(.*?)'{3}/" => "<b>$1</b>",
      "/'{2}(.*?)'{2}/" => "<i>$1</i>",
      "/={2}(.*?)={2}/" => "<h2>$1</h2>",
      "/\[{2}(.*?)[|](.*?)\]{2}/" => "<a href= './index.php?page=$1'>$2</a>",
      "/\[{2}(.*?)\]{2}/" => "<a href= './index.php?page=$1'>$1</a>",
      "/\[(http.*?)[|](.*?)\]/" => "<a href= '$1'>$2</a>",
      "/\[(http.*?)\]/" => "<a href= '$1'>$1</a>",
   );

   foreach ($regexs as $pattern => $replace){
      $htmlstr = preg_replace($pattern, $replace, $htmlstr);
   }

   $htmlstr .= "<div class='lastmod'>Last Modified: $t</div>";

   return $htmlstr;
}

function createPage($pageName){
    echo "<div id='main'>
      <ul class='tabs'>
        <li class='selected'>
          <a href='./index.php?page={$pageName}'>View</a>
        </li>
        <li class=''>
          <a href='./index.php?page={$pageName}&amp;edit=true'>Edit</a>
        </li>
      </ul>
      <div class='content'>";
	echo "<h1>$pageName</h1>";
	echo "$pageName does not exist. You can create it here.";
}

function editPage($page, $dir){
    echo "<div id='main'>
      <ul class='tabs'>
        <li class=''>
          <a href='./index.php?page={$page}'>View</a>
        </li>
        <li class='selected'>
          <a href='./index.php?page={$page}&amp;edit=true'>Edit</a>
        </li>
    <li class=''>
          <a href='./index.php?page={$page}&amp;delete=true'>Delete</a>
        </li>
      </ul>
    <div class='content'>";
    echo "<h1>Editing $page</h1>";
      $data = file_get_contents($dir.$page);
    echo "<textarea>$data</textarea>";
}

function deletePage($page){
    echo "<div id='main'>
      <ul class='tabs'>
        <li class=''>
          <a href='./index.php?page={$page}'>View</a>
        </li>
        <li class=''>
          <a href='./index.php?page={$page}&amp;edit=true'>Edit</a>
        </li>
        <li class='selected'>
          <a href='./index.php?page={$page}&amp;delete=true'>Delete</a>
        </li>
      </ul>
      <div class='content'>";

   echo "<form action='./index.php?page={$page}&amp;delete=true' method='post'>";
   echo "<h1>Delete $page</h1>";
   echo "<p>Are you sure you want to delete the article $page?</p>";
   echo "<input type='submit' value='Delete' name='data'/> or <a href='./index.php?page={$page}'>Cancel</a></form>";
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$d = $_GET['page'];
	unlink ($dir.$d);
	}

}

function viewPage($page, $dir){
if (file_exists($dir.$page)){
    echo "<div id='main'>
      <ul class='tabs'>
        <li class='selected'>
          <a href='./index.php?page={$page}'>View</a>
        </li>
        <li class=''>
          <a href='./index.php?page={$page}&amp;edit=true'>Edit</a>
        </li>
        <li class=''>
          <a href='./index.php?page={$page}&amp;delete=true'>Delete</a>
        </li>
      </ul>
      <div class='content'>";

    echo "<h1>$page</h1>";
    $f = fopen($dir.$page,'r');
    $t = date("M j g:i a", filemtime($dir.$path));
    $p = fread($f, 25000);
    $d = replaceWiki($p,$t);
    echo nl2br($d);
   } else { 
	createPage($page);
   }
}

function getRecent($dir){
     echo "<div id='main'>
      <ul class='tabs'>
        <li class='selected'>
          <a href='./index.php?page=Recent Changes'>View</a>
        </li>
      </ul>
      <div class='content'>";
	echo "<h1>Recent Changes</h1><ul>";
	  $dirc = $dir."*";
	  $modDates = array();
	  foreach (glob($dirc) as $file){
	    $n = basename($file);
	    $t = filemtime($file);
	    $modDates[$n] = $t;
	  }
	  arsort($modDates);
	  $i=0;
	  foreach($modDates as $name => $time){
            $timef = date("M j g:i a", $time);
	    echo "<li><a href= './index.php?page=$name'>$name</a> - $timef</li>";
	    $i+=1;
	    if ($i == 5) break;
	  }
       echo "</ul>";
}

function getAll($dir){
     echo "<div id='main'>
      <ul class='tabs'>
        <li class='selected'>
          <a href='./index.php?page=All Pages'>View</a>
        </li>
      </ul>
      <div class='content'>";
	echo "<h1>All pages</h1><ul>";
	    $dirc = $dir."*";
	  foreach (glob($dirc) as $file){
	    $n = basename($file);
	    $l = "./index.php?page={$n}";
	  echo "<li> <a href='$l'> $n </a></li>";
	  }
	  echo "</ul>";
}

?>
      </div>
    </div>

  </body>
</html>
