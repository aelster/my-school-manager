<?php
include( "globals.php" );
require_once( "MyLoader.php" );

SiteLoad( "library" );

include( 'local_tvt2.php' );

WriteHeader();
DisplayMain();
WriteFooter();

?>