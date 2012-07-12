<?php
function DoQuery()
{
	include( 'commonv2_globals.php' );
	
	$num_args = func_num_args();
	$query = func_get_arg( 0 );
	$db = ( $num_args == 1 ) ? $GLOBALS[ 'gDb' ] : func_get_arg( 1 );
	$support = $GLOBALS['gSupport'];
	
	$debug = $GLOBALS[ 'gDebug' ];
	
	if( $debug ) $dmsg = "&nbsp;&nbsp;&nbsp;&nbsp;DoQuery: $query";
	
	$local_result = mysql_query( $query, $db );
	if( mysql_errno( $db ) != 0 )
	{
		if( ! $db ) { echo "  query: $query<br>\n"; }
		echo "  result: " . mysql_error( $db ) . "<br>\n";
		echo "I'm sorry but something unexpected occurred.  Please send all details<br>";
		echo "of what you were doing and any error messages to $support<br>";
	}
	else
	{
		if( preg_match( "/^select/i", $query ) )
		{
			$local_numrows = mysql_num_rows( $local_result );
		}
		else
		{
			$local_numrows = mysql_affected_rows( $db );
		}
		if( $debug ) $dmsg .= sprintf( ", # rows: %d", $local_numrows );
	}
	
 	if( $debug ) Logger( $dmsg );
 
	$GLOBALS[ 'gNumRows' ] = $local_numrows;
	$GLOBALS[ 'result' ] = $local_result;
}
?>