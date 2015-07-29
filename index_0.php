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

    <div id="main">
      <ul class="tabs">
        <li class="selected">
          <a href="./index.php?page=Main+Page">View</a>
        </li>
        <li class="">
          <a href="./index.php?page=Main+Page&amp;edit=true">Edit</a>
        </li>
        <li class="">
          <a href="./index.php?page=Main+Page&amp;delete=true">Delete</a>
        </li>
      </ul>
      <div class="content">
<?

   $dir = "/home/csee1/rbirky1/www-data/read-write/wiki/";
   $page = "Main Page";
   $path = $dir.$page;

   echo "<h1>$page</h1>";

   $f = fopen($path,'r');
   $p = fread($f, 25000);
/*   $pattern = "/'{3}([^'{3}].*)'{3}/";
   $replace = "<i>$1</i>";
   $d = preg_replace ($pattern,$replace,$p);
   echo nl2br($d);
*/
   $d = replaceWiki($p);
   echo nl2br($d);

function replaceWiki($str){
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

   return $htmlstr;
}

?>
      </div>
    </div>

  </body>
</html>
