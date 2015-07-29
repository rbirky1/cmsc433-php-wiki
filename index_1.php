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
       echo "<div id='main'>
      <ul class='tabs'>
        <li class='selected'>
          <a href='./index.php?page={$page}'>View</a>
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
	
   } else {

   if ($_GET['edit']){
    
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

   } elseif ($_GET['delete']){

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
   echo "<input type='submit' value='Delete' /> or <a href='./index.php?page={$page}'>Cancel</a></form>";

   } else {

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
    $f = fopen($path,'r');
    $t = date("M j g:i a", filemtime($path));
    $p = fread($f, 25000);
    $d = replaceWiki($p,$t);
    echo nl2br($d);
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

?>
      </div>
    </div>

  </body>
</html>
