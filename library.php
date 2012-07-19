<?php

function DisplayMain() {
   include('globals.php');
   if($gTrace) {
      $gFunction[] = "DisplayMain()";
      Logger();
   }
   echo "gManager: $gManager<br>";
   if($gTrace) array_pop($gFunction);
}

function WriteFooter() {
   include('globals.php');
   if( $gTrace ) {
      $gFunction[] = "WriteFooter";
      Logger();
   }
   
   echo <<<END
</div>
</form>
</body>
</html>
END;
   if($gTrace ) array_pop( $gFunction );
}

function WriteHeader() {
   include( "globals.php" );
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
   $title = "$gTitle";
   $title .= isset( $_REQUEST['bozo'] ) ? " - Debug" : "";
   $title .= " ($gSiteName)";

   echo <<<END
<title>$title</title>
<meta name="cache-control" content="no-cache">
<meta name="pragma" content="no-cache">
<!-- overLIB 4.21 (c) Erik Bosrup -->
END;

   $scripts = array();
   $scripts[] = "/overlib/overlib.js";
   $scripts[] = "/overlib/overlib_hideform.js";
   foreach ( $scripts as $file ) {
      echo sprintf( "<script type=\"text/javascript\" src=\"%s\"></script>\n", $file );
   }

   echo <<<END
<link type="text/css" href="styles.css" rel="stylesheet">
</head>
<body>
END;

   if( $gTrace ) {
      $gFunction[] = "WriteHeader()";
      Logger();
   }
   AddOverlib();

   if( $gTrace ) array_pop( $gFunction );
}

?>