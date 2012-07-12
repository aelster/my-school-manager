<?php
function AddOverlib()
{
	include( "globals.php" );
	if( $gTrace > 0 ) Logger( "Func: AddOverlib" );
?>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<?php
}
?>