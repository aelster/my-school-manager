<?php
include( "globals.php" );

$id = 0;
$recs = 0;

echo "Rebuild Admin (Update):  ";
if( ! empty( $gTables['zz_faculty_staff'] ) ) {
	DoQuery( "select * from zz_faculty_staff order by `last` ASC" );
	$outer = $result;
	
	while( $row = mysql_fetch_assoc( $outer ) ) {
		$id++;
		$school = $row['school'];
		
		$opts = array();
		$opts[] = "id = '$id'";
		$opts[] = sprintf( "lastname = '%s'", addslashes($row['last']) );
		$opts[] = sprintf( "firstname = '%s'", addslashes($row['first']) );
		$opts[] = sprintf( "title = '%s'", addslashes($row['title']) );
	
		foreach( array( "LS", "MS", "US" ) as $sch ) {
			if( preg_match( "/$sch/", $school ) ) {
				$query = sprintf( "insert into admin set school = '%s', %s", $sch, join( ',', $opts ) );
				DoQuery( $query );
				$recs++;
			}
		}
	}
}
echo "Loaded $id faculty names, $recs records</br>";
?>