<?php
include( "globals.php" );
require_once( "MyLoader.php" );

SiteLoad( "library" );

include( 'local-my-school-manager.php' );
$gDb = OpenDb();

$test = isset( $_REQUEST['bozo'] ) ? 1 : 0;
$gDebug = $test;
$gTrace = $test;

WriteHeader();
DisplayMain();
WriteFooter();

?>