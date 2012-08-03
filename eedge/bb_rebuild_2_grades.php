<?php
include( "globals.php" );

echo "Rebuild Grades (Update):  ";
$new_grades = 0;

if( ! empty( $gTables['zz_ls_homerooms'] ) ) {
	DoQuery( "select distinct homeroom from zz_ls_homerooms order by homeroom asc" );
	$outer = $result;

	$hrooms = array();
	while( list( $hr ) = mysql_fetch_array( $outer ) ) {
		DoQuery( "select * from grades where label = '$hr'" );
		if( $gNumRows == 0 ) {
			if( preg_match( '/^K/', $hr ) ) {
				$level = 0;
			} elseif( preg_match( '/Z$/', $hr ) ) {
				$level = substr( $gr, 0, 1 );
			} else {
				$level = $hr;
			}
			DoQuery( "insert into grades set label = '$hr', level = '$level'" );
			$new_grades++;
		}
	}
}
echo "Created $new_grades grade/sections<br>";

?>