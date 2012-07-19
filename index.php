<?php
include('globals.php');
include('library.php');

require_once('SiteLoader.php');

SiteLoader('Common');

require('local-control.php');
$gDbControl = OpenDb();

require('local-eedge.php');
$gDbEEdge = OpenDb();

require( 'local-my-school-manager.php' );
$gDb = OpenDb();

$test = isset( $_REQUEST['bozo'] ) ? 1 : 0;
$gDebug = $test;
$gTrace = $test;

if( empty($gManager) ) $gManager = 0;

WriteHeader();
DisplayMain();
WriteFooter();

?>