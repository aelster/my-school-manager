<?php
include( "globals.php" );
require_once( "MyLoader.php" );

SiteLoad( "library" );

include( 'local-my-school-manager.php' );

WriteHeader();
DisplayMain();
WriteFooter();

?>