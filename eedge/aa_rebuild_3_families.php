<?php
include( "globals.php" );

echo "Rebuild Families:  ";

$gLabelToId = array();
DoQuery( "select id, label from grades" );
while( list( $id, $label ) = mysql_fetch_array( $gResult ) ) {
	$gLabelToId[ $label ] = $id;
}

DoQuery( "truncate table `parents`" );
DoQuery( "truncate table `students`" );

$fid = 0;

DoQuery( "select * from zz_parent_students order by slast asc, sfirst asc");
$outer = $gResult;
while( $row = mysql_fetch_assoc( $outer ) ) {
	foreach( $row as $field => $val ) {
		$$field = $val;
	}
	$xx = 1;
	$p1home = preg_replace( '(\D+)', '', $p1home );
	$p2home = preg_replace( '(\D+)', '', $p2home );
	if( empty( $p1zip ) ) $p1zip = 0;
	if( empty( $p1home ) ) $p1home = 0;
	if( empty( $p2zip ) ) $p2zip = 0;
	if( empty( $p2home ) ) $p2home = 0;

	$query = sprintf( "select family_id from parents where id = '%s' or id = '%s'",
						  addslashes( $p1id ), addslashes( $p2id ) );
	DoQuery( $query );
	if( $gNumRows > 0 ) {
		list( $fid ) = mysql_fetch_array( $gResult );
		
	} else {
		DoQuery( "select max(family_id) from parents" );
		if( $gNumRows > 0 ) {
			list( $fid ) = mysql_fetch_array( $gResult );
		}
		$fid++;
	}
	
	if( ! empty( $p1id ) ) {
		$query = sprintf( "select * from parents where id = '%s'", addslashes( $p1id ) );
		DoQuery( $query );
		if( $gNumRows == 0 ) {
			$flds = array();
			$flds[] = sprintf( "id='%s'", addslashes( $p1id ) );
			$flds[] = sprintf( "last_name='%s'", addslashes( $p1last ) );
			$flds[] = sprintf( "first_name='%s'", addslashes( $p1first ) );
			$flds[] = sprintf( "address='%s'", $p1address );
			$flds[] = sprintf( "city='%s'", $p1city );
			$flds[] = sprintf( "state='%s'", $p1state );
			$flds[] = sprintf( "zip='%s'", $p1zip );
			$flds[] = sprintf( "phone='%s'", $p1home);
			$flds[] = sprintf( "email='%s'", $p1email);
			$flds[] = sprintf( "family_id='%s'", $fid );
			$query = sprintf( "insert into parents set %s", join( ',', $flds ) );
			DoQuery( $query );
		}
	}
	
	if( ! empty( $p2id ) ) {
		$query = sprintf( "select * from parents where id = '%s'", addslashes( $p2id ) );
		DoQuery( $query );
		if( $gNumRows == 0 ) {
			$flds = array();
			$flds[] = sprintf( "id='%s'", addslashes( $p2id ) );
			$flds[] = sprintf( "last_name='%s'", addslashes( $p2last ) );
			$flds[] = sprintf( "first_name='%s'", addslashes( $p2first ) );
			$flds[] = sprintf( "address='%s'", $p2address );
			$flds[] = sprintf( "city='%s'", $p2city );
			$flds[] = sprintf( "state='%s'", $p2state );
			$flds[] = sprintf( "zip='%s'", $p2zip );
			$flds[] = sprintf( "phone='%s'", $p2home);
			$flds[] = sprintf( "email='%s'", $p2email);
			$flds[] = sprintf( "family_id='%s'", $fid );
			$query = sprintf( "insert into parents set %s", join( ',', $flds ) );
			DoQuery( $query );
		}
	}

	if( ! empty( $gTables[ 'zz_ls_homerooms'] ) ) {
		DoQuery( "select homeroom from zz_ls_homerooms where studentid = '$studentid'" );
		if( $gNumRows > 0 ) {
			list( $label ) = mysql_fetch_array( $gResult );
		} else {
			$label = sprintf( "%d", $grade );
		}
	} else {
		$i = intval( $grade );
		if( $i == 0 ) {
			$label = "K";
		} else if( $i < 6 ) {
			$label = "$i";
		} else {
			$label = "$i";
		}
	}
	
	$flds = array();
	$flds[] = sprintf( "id = '%s'", $studentid );
	$flds[] = sprintf( "last_name = '%s'", addslashes( $slast ) );
	$flds[] = sprintf( "first_name = '%s'", addslashes( $sfirst ) );
	$flds[] = sprintf( "parent1_id = '%s'", addslashes( $p1id ) );
	$flds[] = sprintf( "parent2_id = '%s'", addslashes( $p2id ) );
	$flds[] = sprintf( "grade_id = '%s'", $gLabelToId[$label] );
	$flds[] = sprintf( "family_id = '%s'", $fid );
	$query = sprintf( "insert into students set %s", join( ',', $flds ) );
	DoQuery( $query );
}

DoQuery( "select * from parents" );
$np = $gNumRows;
DoQuery( "select * from students" );
$ns = $gNumRows;
echo "Loaded $np parents and $ns students\n";

?>