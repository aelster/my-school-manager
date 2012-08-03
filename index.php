<?php
require( "lib/swift_required.php" );

include('globals.php');
include('library.php');

require_once('SiteLoader.php');
SiteLoader('Common');

require('local-control.php');
$gDbControl = OpenDb();

require('local-eedge.php');
$gDbEEdge = OpenDb();

require( 'local-my-school-manager.php' );
#$gDb = OpenDb();

$test = isset( $_REQUEST['bozo'] ) ? 1 : 0;
$gDebug = $test;
$gTrace = $test;
$gFunction = array('index.php');

if( empty($gManager) ) $gManager = 0;

LocalInit();
WriteHeader();

if( $gDebug ) {
   echo <<<END
<script type="text/javascript">MyCreateDebugWindow()</script>
END;
   DumpPostVars();
}

$gAction = ( isset( $_POST[ "action" ] ) ) ? $_POST[ "action" ] : "Start";
$gFrom = ( isset( $_POST[ 'from' ] ) ? $_POST[ 'from' ] : "" );
if( ! empty( $_POST['feature'] ) ) $gFeature = $_POST['feature'];

SessionStuff('start');
PrepareForAction();
DisplayMain();
WriteFooter();

?>