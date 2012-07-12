<?php

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
   echo "<title>$title</title>";
?>
<meta name="cache-control" content="no-cache">
<meta name="pragma" content="no-cache">
<!-- overLIB 4.21 (c) Erik Bosrup -->
<?php
   $scripts = array();
   $scripts[] = "/overlib/overlib.js";
   $scripts[] = "/overlib/overlib_hideform.js";
   foreach ( $scripts as $file ) {
      echo sprintf( "<script type=\"text/javascript\" src=\"%s\"></script>\n", $file );
   }
?>
<link type="text/css" href="styles.css" rel="stylesheet">
</head>
<body>
<?php
   if( $gTrace ) {
      $gFunction[] = "WriteHeader()";
      Logger();
   }
   AddOverlib();

   if( $gTrace ) array_pop( $gFunction );
}

?>