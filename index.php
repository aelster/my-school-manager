<?php
include( "globals.php" );
require_once( "MyLoader.php" );

SiteLoad( "library" );

include( 'local-my-school-manager.php' );
$gDb = OpenDb();

WriteHeader();
DisplayMain();
WriteFooter();

?>