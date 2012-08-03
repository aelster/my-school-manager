<?php
include( "globals.php" );
DoQuery( "truncate table grades" );

echo "Rebuild Grades (New):  ";

$default_grades = array( "K", 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 );
$i = 0;
foreach( $default_grades as $gr ) {
	if( preg_match( '/^K/', $gr ) ) {
		$level = 0;
	} else {
		$level = $gr;
	}
	if( $level < 6 ) {
		foreach( array( "", "A", "B", "C") as $sect ) {
			$query = "insert into grades set label = '$gr$sect', level = '$level', `order` = '$i'";
			DoQuery( $query );
			$i++;
		}
	} else {
		$query = "insert into grades set label = '$gr', level = '$level', `order` = '$i'";
		DoQuery( $query );
		$i++;
	}

}

if( ! empty( $gTables['zz_ls_homerooms'] ) ) {
	DoQuery( "select distinct homeroom from zz_ls_homerooms order by homeroom asc" );
	
	$hrooms = array();
	
	while( list( $hr ) = mysql_fetch_array( $result ) ) {
		preg_match( '/(.)(.)/', $hr, $matches );
		if( $matches[1] == 'K' ) {
			$skey = sprintf( "%02d_%s", 0, $matches[2] );
		} else {
			$skey = sprintf( "%02d_%s", $matches[1], $matches[2] );
		}
		$hrooms[$skey] = $hr;
	}
	for( $i = 6; $i <= 12; $i++ ) {
		$skey = sprintf( "%02d_A", $i );
		$hr = "$i";
		$hrooms[$skey] = $hr;
	}
	ksort( $hrooms );
	foreach( $hrooms as $skey => $hr ) {
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
		}
	}
}
DoQuery( "select * from grades" );
echo "Created $gNumRows grade/sections<br>";
?>