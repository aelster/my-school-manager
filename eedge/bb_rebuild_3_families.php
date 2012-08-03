<?php
include( "globals.php" );

echo "Rebuild Families (Update):  ";

$gLabelToId = array();
DoQuery( "select id, label from grades" );
while( list( $id, $label ) = mysql_fetch_array( $result ) ) {
	$gLabelToId[ $label ] = $id;
}

#========================================================================================================
# Create an easy lookup table for a number of items to reduce the number of queries
#========================================================================================================

$maxFamilyId = 0;
$pid2familyId = array();
DoQuery( "select id, family_id from parents" );
while( list( $id, $familyId ) = mysql_fetch_array( $result ) ) {
	$pid2familyId[$id] = $familyId;
	if( $familyId > $maxFamilyId ) $maxFamilyId = $familyId;
}

#========================================================================================================
# Walk to new imported file to look for changes/updates
#========================================================================================================

$numParentNew = 0;
$numParentUpdates = 0;
$numStudentNew = 0;
$numStudentUpdates = 0;

DoQuery( "select * from zz_parent_students" );
$outer = $result;
while( $row = mysql_fetch_assoc( $outer ) ) {
	foreach( $row as $field => $val ) {
		$$field = $val;
	}

#========================================================================================================
# Verify that each family/parent ID exists in the current database
#========================================================================================================

	$newFamily = 0;
	if( ! empty( $p1id ) && empty( $pid2familyId[$p1id] ) ) {
		printf( "New Parent %s %s<br>", $p1first, $p1last );
		$newFamily = 1;
	}
	if( ! empty( $p2id ) && empty( $pid2familyId[$p2id] ) ) {
		printf( "New Parent %s %s<br>", $p2first, $p2last );
		$newFamily = 1;
	}
	if( $newFamily ) {
#========================================================================================================
# Create the family and add the parents
#========================================================================================================
		if( empty( $pid2familyId[$p1id] ) && empty( $pid2familyId[$p2id] ) ) {
			$familyId = $maxFamilyId + 1;
			$maxFamilyId = $familyId;
		} elseif( empty( $pid2fmailyId[$p1id] ) ) {
			$familyId = $pid2familyId[$p2id];
		} else {
			$familyId = $pid2familyId[$p1id];
		}
		for( $i = 1; $i <= 2; $i++ ) {
			$fld = "p${i}id";
			if( ! empty( $pid2familyId[$$fld] ) ) continue;
			$id = $$fld;
			if( ! empty( $id ) ) {
				$fld = "p${i}home";
				$phone = preg_replace("/[^0-9]/", "", $$fld );
				if( $phone == 0 ) {
					$fld = "p${i}cell";
					$phone = preg_replace("/[^0-9]/", "", $ffld );
				}

				$opts = array();
				$opts[] = sprintf( "id = '%s'", $id );
				$fld = "p${i}last"; $opts[] = sprintf( "last_name = '%s'", addslashes($$fld) );	
				$fld = "p${i}first"; $opts[] = sprintf( "first_name = '%s'", addslashes($$fld) );	
				$fld = "p${i}address"; $opts[] = sprintf( "address = '%s'", $$fld );	
				$fld = "p${i}city"; $opts[] = sprintf( "city = '%s'", $$fld );	
				$fld = "p${i}state"; $opts[] = sprintf( "state = '%s'", $$fld );	
				$fld = "p${i}zip"; $opts[] = sprintf( "zip = '%s'", $$fld );	
				$opts[] = sprintf( "phone = '%s'", $phone );	
				$fld = "p${i}email"; $opts[] = sprintf( "email = '%s'", $$fld );	
				$opts[] = sprintf( "family_id = '%d'", $familyId );
				$query = "insert into parents set " . join( ", ", $opts );
				DoQuery( $query );
				$numParentNew++;
				$pid2familyId[$id] = $familyId;
			}
		}
		
	} else {
#========================================================================================================
# We have an existing family.  Check all fields for updates
#========================================================================================================
		
		$xformation = array(
			'last' => 'last_name',
			'first' => 'first_name',
			'address' => 'address',
			'city' => 'city',
			'state' => 'state',
			'zip' => 'zip',
			'home' => 'phone',
			'email' => 'email'
			);
		
		$familyId = 0;
		
		for( $i = 1; $i <= 2; $i++ ) {
			$fld = "p${i}id";
			$id = $$fld;
			if( ! empty( $id ) ) {
				
				if( $familyId == 0 ) $familyId = $pid2familyId[$id];

				DoQuery( "select * from parents where id = '$id'" );
				$prow = mysql_fetch_assoc( $result );
				
				$opts = array();
				
				foreach( $xformation as $ifld => $pfld ) {
					$fld = "p${i}$ifld";
					if( empty( $$fld ) ) $$fld = "";
					if( $ifld == 'home' ) {
						$$fld = preg_replace("/[^0-9]/", "", $$fld );
						if( empty( $$fld ) ) {
							$tfld = "p${i}cell";
							$ffld = preg_replace("/[^0-9]/", "", $tfld );
						}
						if( empty( $ffld ) ) $$fld = 0;
					} elseif( $ifld == 'zip' ) {
						if( empty( $$fld ) ) $$fld = 0;
					} elseif( $ifld == 'home' ) {
						if( empty( $$fld ) ) $$fld = 0;
					}
					if( $$fld != $prow[$pfld] ) {
						$opts[]  = sprintf( "%s = '%s'", $pfld, $$fld );
					}
				}
				if( empty( $opts ) ) continue;

				$query = "update parents set " . join( ", ", $opts ) . " where id = '$id'";
				DoQuery( $query );
				$numParentUpdates++;
			}
		}
	}

#========================================================================================================
# Now check the students
#========================================================================================================
	$sid = $row['studentid'];
	DoQuery( "select homeroom from zz_ls_homerooms where studentid = '$sid'" );
	if( $gNumRows ) {
		list( $hr ) = mysql_fetch_array( $result );
	} else {
		$hr = preg_replace( "/^0+/", "", $row['grade'] );	
	}	
	$gradeId = $gLabel2GradeId[ $hr ];
	
	DoQuery( "select * from students where id = '$sid'" );
	if( $gNumRows ) {
#========================================================================================================
# We have an existing student.  Check all fields for updates
#========================================================================================================
		$srow = mysql_fetch_assoc( $result );
		foreach( $srow as $field => $val ) {
			$$field = $val;
		}
		
		$xformation = array(
			'slast' => 'last_name',
			'sfirst' => 'first_name',
			'p1id' => 'parent1_id',
			'p2id' => 'parent2_id',
			);
		
		$familyId = $srow['family_id'];
		
		$opts = array();
				
		foreach( $xformation as $ifld => $sfld ) {
			if( $row[$ifld] != $$sfld ) {
				$opts[]  = sprintf( "%s = '%s'", $sfld, $row[$ifld] );
			} 
		}
		if( $gradeId != $srow['grade_id'] ) {
			$opts[] = sprintf( "grade_id = '%d'", $gradeId );
		}
		if( empty( $opts ) ) continue;

		$query = "update students set " . join( ", ", $opts ) . " where id = '$id'";
		DoQuery( $query );
		$numStudentUpdates++;

	} else {
#========================================================================================================
# We have a new student.  
#========================================================================================================

		$grade = preg_replace( "/^0+/", "", $row['grade'] );
		printf( "New Student %s %s<br>", $row['sfirst'], $row['slast'] );

		$opts = array();
		$opts[] = sprintf( "id = '%s'", $sid );
		$opts[] = sprintf( "last_name = '%s'", $row['slast']);
		$opts[] = sprintf( "first_name = '%s'", $row['sfirst']);
		$opts[] = sprintf( "parent1_id = '%s'", $row['p1id']);
		$opts[] = sprintf( "parent2_id = '%s'", $row['p2id']);
		$opts[] = sprintf( "grade_id = '%s'", $gradeId );
		$opts[] = sprintf( "family_id = '%s'", $familyId );
		$opts[] = sprintf( "active = '1'" );
		$query = "insert into students set " . join( ", ", $opts );
		DoQuery( $query );
		$numStudentNew++;
	}
	
}

printf( "# new parents: %d<br>", $numParentNew );
printf( "# parent updates: %d<br>", $numParentUpdates );
printf( "# new Students: %d<br>", $numStudentNew );
printf( "# Student updates: %d<br>", $numStudentUpdates );

?>