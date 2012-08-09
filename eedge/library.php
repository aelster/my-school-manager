<?php
function eeBuildAll() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "BuildAll()" );
		Logger();
	}
	$arg = func_get_arg( 0 );
	if( $arg == "New" ) {
		include( 'aa_rebuild_1_admin.php' );
		include( 'aa_rebuild_2_grades.php' );
		include( 'aa_rebuild_3_families.php' );
		
	} elseif( $arg == "Update" ) {
		include( 'bb_rebuild_1_admin.php' );
		include( 'bb_rebuild_2_grades.php' );
		include( 'cc_rebuild_3_families.php' );
		
	}	if( $gTrace ) {
		array_push( $gFunction, "BuildAll($prefix)" );
		Logger();
	}
	
	if( $gTrace ) array_pop( $gFunction );
}

function eeBuildMain() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "BuildMain()" );
		Logger();
	}
	
	$acts = array();
	$acts[] = "MySetValue('feature','eedge')";
	$acts[] = "MySetValue('func','buildNew')";
	$acts[] = "MyAddAction('rebuild')";
	echo sprintf( "<input type=button onClick=\"%s\" value=\"Rebuild from Scratch\">", join(';', $acts ) );

	$acts = array();
	$acts[] = "MySetValue('feature','eedge')";
	$acts[] = "MySetValue('func','buildUpdate')";
	$acts[] = "MyAddAction('rebuild')";
	echo sprintf( "<input type=button onClick=\"%s\" value=\"Update Existing\">", join(';', $acts ) );

	if( $gTrace ) array_pop( $gFunction );
}

function eeDirectorBuild() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "DirectorBuild()" );
		Logger();
	}
   
   $func = $_POST['func'];
	if( $func == "main" ) BuildMain();
   if( $func == "buildNew" ) BuildAll("New");
   if( $func == "buildUpdate" ) BuildAll("Update");;
   
   if( $gTrace ) array_pop( $gFunction );
}

function eeDisplayAddress() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "DisplayAddress()" );
		Logger();
	}

	echo "<h2>Education Edge: Bad/Missing Address</h2>";
	echo "<div class=CommonV2>";
	echo "<table>";
	echo "<tr>";
	echo "  <th>Name</th>";
	echo "  <th>Parent ID</th>";
	echo "  <th>Address</th>";
	echo "  <th>City</th>";
	echo "  <th>State</th>";
	echo "  <th>Zip</th>";
	echo "</tr>";

	$opts = array();
	$opts[] = 'length(address) = 0';
	$opts[] = 'length(city) = 0';
	$opts[] = 'length(state) = 0';
	$opts[] = 'zip = 0';
	$query = "select * from parents where " . join( ' or ', $opts ) . " order by last_name asc";
	DoQuery( $query );
	
	while( $row = mysql_fetch_assoc( $gResult ) ) {
		echo "<tr>";
		echo sprintf( "  <td class=c>%s, %s</td>", addslashes( $row['last_name'] ), addslashes( $row['first_name'] ) );
		echo sprintf( "  <td class=c>%s</td>", addslashes( $row['id'] ) );
		echo sprintf( "  <td class=c>%s</td>", addslashes( $row['address'] ) );
		echo sprintf( "  <td class=c>%s</td>", addslashes( $row['city'] ) );
		echo sprintf( "  <td class=c>%s</td>", addslashes( $row['state'] ) );
		echo sprintf( "  <td class=c>%s</td>", addslashes( $row['zip'] ) );
		echo "</tr>";
	}

	echo "</table>";
	echo "</div>";   
	if( $gTrace ) array_pop( $gFunction );
}

function eeDisplayAdmin() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "DisplayAdmin()" );
		Logger();
	}
	echo "<h2>Education Edge: Faculty/Admin</h2>";
	echo "<p>Columns with a \"&bull;\" are sortable</p>";

	echo "<input type=hidden name=from value=DisplayAdmin>";

	echo "<div class=CommonV2>";
	echo "<table class=sortable>";
	echo "<tr>";
	echo "  <th>&bull; ID</th>";
	echo "  <th>&bull; School</th>";
	echo "  <th>&bull; Last</th>";
	echo "  <th>&bull; First</th>";
	echo "  <th>&bull; Title</th>";
	echo "</tr>";
	
	$query = "select id, school, lastname, firstname, title from admin order by school, lastname";
	DoQuery( $query );
	
	while( list( $id, $sch, $last, $first, $title ) = mysql_fetch_array( $gResult ) ) {
		echo "<tr>";
		echo sprintf( "  <td class=c>%s</td>", $id );
		if( $sch == "LS" ) {
			$skey = sprintf( "%d_%s_%s", 1, $last, $first );
		} elseif( $sch == "MS" ) {
			$skey = sprintf( "%d_%s_%s", 2, $last, $first );
		} else {
			$skey = sprintf( "%d_%s_%s", 3, $last, $first );
		}
		echo sprintf( "  <td sorttable_customkey=\"%s\">%s</td>", $skey, $sch );
		echo sprintf( "  <td>%s</td>", addslashes( $last ) );
		echo sprintf( "  <td>%s</td>", addslashes( $first ) );
		echo sprintf( "  <td>%s</td>", addslashes( $title ) );
		echo "</tr>";
	}
	
	echo "</table>";
	echo "</div>";

	if( $gTrace ) array_pop( $gFunction );
}

function eeDisplayEmail1() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "DisplayEmail1()" );
		Logger();
	}

	echo "<h2>Education Edge: Parent w/o E-mail</h2>";
	echo "<div class=CommonV2>";

	echo "<table>";
	echo "<tr>";
	echo "  <th>Name</th>";
	echo "  <th>Parent ID</th>";
	echo "  <th>Family ID</th>";
	echo "</tr>";

	DoQuery( "select * from parents where length(email) = 0 order by last_name asc");
	while( $row = mysql_fetch_assoc( $gResult ) ) {
		echo "<tr>";
		echo sprintf( "  <td class=c>%s, %s</td>", addslashes( $row['last_name'] ), addslashes( $row['first_name'] ) );
		echo sprintf( "  <td class=c>%s</td>", addslashes( $row['id'] ) );
		echo sprintf( "  <td class=c>%d</td>", $row['family_id'] );
		echo "</tr>";
	}

	echo "</table>";
	echo "</div>";   
	if( $gTrace ) array_pop( $gFunction );
}

function eeDisplayEmail2() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "DisplayEmail2()" );
		Logger();
	}

	echo "<h2>Education Edge: Family w/o E-mail</h2>";
	echo "<div class=CommonV2>";
	echo "<table>";
	echo "<tr>";
	echo "  <th>Family ID</th>";
	echo "  <th>Parent ID</th>";
	echo "  <th>Name</th>";
	echo "</tr>";

	$i = 0;
	DoQuery( "select distinct family_id from parents order by family_id asc");
	$outer = $gResult;
	while( list( $fid ) = mysql_fetch_array( $outer ) ) {
		DoQuery( "select * from parents where length(`email`) = 0 and family_id = '$fid'");
		if( $gNumRows != 2 ) continue;
		$cls = ( $i % 2 == 0 ) ? "class=c" : "class=c2";
		while( $row = mysql_fetch_assoc( $gResult ) ) {
			echo "<tr>";
			echo "  <td $cls>$fid</td>";
			echo sprintf( "  <td $cls>%s</td>", addslashes( $row['id'] ) );
			echo sprintf( "  <td $cls>%s, %s</td>", addslashes( $row['last_name'] ), addslashes( $row['first_name'] ) );
			echo "</tr>";
		}
		$i++;
	}

	echo "</table>";
	echo "</div>";   
	if( $gTrace ) array_pop( $gFunction );
}

function eeDisplayGrades() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "DisplayGrades()" );
		Logger();
	}

	echo "<h2>Education Edge: Grades</h2>";
	echo "<div class=CommonV2>";
	
	echo "<br>";
	echo "<table>";
	echo "<tr>";
	echo "  <th rowspan=2>Grade</th>";
	echo "  <th rowspan=2>#<br>Students</th>";
	echo "  <th colspan=4>Section</th>";
	echo "</tr>";
	echo "<tr>";
	echo "  <th>&nbsp;</th><th>A</th><th>B</th><th>C</th>";
	echo "</tr>";

	$sum = 0;
	$gsum = 0;
	
	for( $level = 0; $level <= 12; $level++ ) {
		$grades = array();
		$sum = 0;
		DoQuery( "select id, label from grades where level = '$level' order by label asc" );
		$outer = $gResult;
		while( list( $id, $label ) = mysql_fetch_array( $outer ) ) {
			DoQuery( "select count(id) from students where grade_id = '$id' and active > 0" );
			list($num) = mysql_fetch_array( $gResult );
			$grades[$label] = $num;
			$sum += $num;
		}
		echo "<tr>";
		if( $level == 0 ) {
			echo "<td class=c>K</td>";
		} else {
			echo "<td class=c>$level</td>";
		}
		echo "<td class=c>$sum</td>";
		if( count( $grades ) > 1 ) {
			foreach( $grades as $label => $count ) {
				echo "<td class=c>$count</td>";
			}
		} else {
			echo "<td colspan=4>&nbsp;</td>";
		}
		echo "</tr>";
		$gsum += $sum;
	}

	echo "<tr>";
	echo "<th>Total</th>";
	echo "<th class=c>$gsum</th>";
	echo "<th colspan=4>&nbsp;</th>";
	echo "</tr>";
	
	echo "</table>";
	echo "</div>";
	
	if( $gTrace ) array_pop( $gFunction );
}

function eeDisplayMain() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "DisplayMain()" );
		Logger();
	}

   $gDb = $gDbEEdge;
   
   echo <<<END
<script type='text/javascript'>MySetValue('feature','eedge');</script>
END;
   echo "<div class=CommonV2>";
	echo "<h2>Education Edge Database</h2>";
	
	if( UserManager( 'authorized', 'control' ) ) {
		$acts = array();
		$acts[] = "MySetValue('func','main')";
		$acts[] = "MyAddAction('rebuild')";
		echo sprintf("<input type=button onClick=\"%s\" value=\"Rebuild\">", join( ';', $acts ) );
	}

	echo "<table>";
	echo "<tr>";
	echo "  <th>Item</th>";
	echo "  <th># Records</th>";
	echo "  <th>Details</th>";
	echo "</tr>";
	
	DoQuery( "select id from parents where active > 0" );
	$active = $gNumRows;
	DoQuery( "select id from parents" );
	$total = $gNumRows;
	echo "<tr>";
	echo "  <td>Parents</td>";
	echo "  <td class=c>$active/$total</td>";
	echo "  <td class=c>";
	$acts = array();
	$acts[] = "MySetValue('func','parents')";
	$acts[] = "MyAddAction('display')";
	echo sprintf( "<input type=button onClick=\"%s\" value=\"Parents\">", join( ';', $acts ) );
	echo "  </td>";
	echo "</tr>";
	
	DoQuery( "select id from students where active > 0" );
	$active = $gNumRows;
	DoQuery( "select id from students" );
	echo "<tr>";
	$total = $gNumRows;
	echo "  <td>Students</td>";
	echo "  <td class=c>$active/$total</td>";
	echo "  <td class=c>";
	$acts = array();
	$acts[] = "MySetValue('func','students')";
	$acts[] = "MyAddAction('display')";
	echo sprintf( "<input type=button onClick=\"%s\" value=\"Students\">", join( ';', $acts ) );
	echo "  </td>";
	echo "</tr>";
	
	DoQuery( "select id from grades" );
	echo "<tr>";
	echo "  <td>Grades</td>";
	echo "  <td class=c>$gNumRows</td>";
	echo "  <td class=c>";
	$acts = array();
	$acts[] = "MySetValue('func','grades')";
	$acts[] = "MyAddAction('display')";
	echo sprintf( "<input type=button onClick=\"%s\" value=\"Grades\">", join( ';', $acts ) );
	echo "  </td>";
	echo "</tr>";
	
	DoQuery( "select distinct id from admin" );
	echo "<tr>";
	echo "  <td>Faculty/Admin</td>";
	echo "  <td class=c>$gNumRows</td>";
	echo "  <td class=c>";
	$acts = array();
	$acts[] = "MySetValue('func','admin')";
	$acts[] = "MyAddAction('display')";
	echo sprintf( "<input type=button onClick=\"%s\" value=\"Admin\">", join( ';', $acts ) );
	echo "  </td>";
	echo "</tr>";
	
	$total = 0;
	DoQuery( "select id from parents order by id asc" );
	$outer = $gResult;
	while( list( $id ) = mysql_fetch_array( $outer ) ) {
		$id = addslashes( $id );
		DoQuery( "select id from students where parent1_id = '$id'" );
		$n1 = $gNumRows;
		DoQuery( "select id from students where parent2_id = '$id'" );
		$n2 = $gNumRows;

		if( $n1 && $n2 ) $total++;
	}
	echo "<tr>";
	echo "  <td>Swapped IDs</td>";
	echo "  <td class=c>$total</td>";
	echo "  <td class=c>";
	$acts = array();
	$acts[] = "MySetValue('func','swaps')";
	$acts[] = "MyAddAction('display')";
	echo sprintf( "<input type=button onClick=\"%s\" value=\"Swaps\">", join( ';', $acts ) );
	echo "  </td>";
	echo "</tr>";

   if( 1 == 2 ) {
      DoQuery( "select distinct(p2id) from zz_parent_students where p2id is not null and p1id is null" );
      echo "<tr>";
      echo "  <td>No Parent1 ID</td>";
      echo "  <td class=c>$gNumRows</td>";
      echo "  <td class=c>";
      $acts = array();
      $acts[] = "setValue('func','nop1id')";
      $acts[] = "addAction('display')";
      echo sprintf( "<input type=button onClick=\"%s\" value=\"No Parent1\">", join( ';', $acts ) );
      echo "  </td>";
      echo "</tr>";
      
      DoQuery( "select distinct(p1id) from zz_parent_students where p1id is not null and p2id is null" );
      echo "<tr>";
      echo "  <td>No Parent2 ID</td>";
      echo "  <td class=c>$gNumRows</td>";
      echo "  <td class=c>";
      $acts = array();
      $acts[] = "setValue('func','nop2id')";
      $acts[] = "addAction('display')";
      echo sprintf( "<input type=button onClick=\"%s\" value=\"No Parent2\">", join( ';', $acts ) );
      echo "  </td>";
      echo "</tr>";
   }
   
	$total = 0;
	DoQuery( "select * from parents where length(email) = 0 order by family_id asc");
	echo "<tr>";
	echo "  <td>No Parent Email</td>";
	echo "  <td class=c>$gNumRows</td>";
	echo "  <td class=c>";
	$acts = array();
	$acts[] = "MySetValue('func','email1')";
	$acts[] = "MyAddAction('display')";
	echo sprintf( "<input type=button onClick=\"%s\" value=\"No Email\">", join( ';', $acts ) );
	echo "  </td>";
	echo "</tr>";
	
	$total = 0;
	DoQuery( "select distinct family_id from parents order by family_id asc");
	$outer = $gResult;
	while( list( $fid ) = mysql_fetch_array( $outer ) ) {
		$query = sprintf( "select * from parents where length(`email`) = 0 and family_id = '%s'", addslashes($fid) );
		DoQuery( $query );
		if( $gNumRows == 2 ) {
			$total++;
		}
	}
	echo "<tr>";
	echo "  <td>No Family Email</td>";
	echo "  <td class=c>$total</td>";
	echo "  <td class=c>";
	$acts = array();
	$acts[] = "MySetValue('func','email2')";
	$acts[] = "MyAddAction('display')";
	echo sprintf( "<input type=button onClick=\"%s\" value=\"No Email\">", join( ';', $acts ) );
	echo "  </td>";
	echo "</tr>";
	
	DoQuery( "select * from parents where length(phone) < 10");
	echo "<tr>";
	echo "  <td>Bad Phone #</td>";
	echo "  <td class=c>$gNumRows</td>";
	echo "  <td class=c>";
	$acts = array();
	$acts[] = "MySetValue('func','phone')";
	$acts[] = "MyAddAction('display')";
	echo sprintf( "<input type=button onClick=\"%s\" value=\"Phone\">", join( ';', $acts ) );
	echo "  </td>";
	echo "</tr>";
	
	$opts = array();
	$opts[] = 'length(address) = 0';
	$opts[] = 'length(city) = 0';
	$opts[] = 'length(state) = 0';
	$opts[] = 'zip = 0';
	$query = "select * from parents where " . join( ' or ', $opts );
	DoQuery( $query );
	
	echo "<tr>";
	echo "  <td>Incomplete Address</td>";
	echo "  <td class=c>$gNumRows</td>";
	echo "  <td class=c>";
	$acts = array();
	$acts[] = "MySetValue('func','address')";
	$acts[] = "MyAddAction('display')";
	echo sprintf( "<input type=button onClick=\"%s\" value=\"Address\">", join( ';', $acts ) );
	echo "  </td>";
	echo "</tr>";
	
	echo "</table>";
	echo "</div>";
   echo "</div>";
}

function eeDisplayParents() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "DisplayParents()" );
		Logger();
	}
	echo "<h2 id=top>Education Edge: Parents</h2>";
	echo "<p>Columns with a \"&bull;\" are sortable</p>";

	echo "<input type=hidden name=from value=DisplayParents>";
	
	$query = "select * from parents order by last_name asc";
	DoQuery( $query );
	
	$num_per_tranche = 15;
	$pdb = array();
	$keys = array();
	$i = 0;
	while( $row = mysql_fetch_row( $gResult ) ) {
		if( $i % $num_per_tranche == 0 ) $keys[] = $row[1];		
		$pdb[$i++] = $row;
	}

	$i = 0;
	foreach( $keys as $ln ) {
		printf( "<a href=#$ln>%s</a>&nbsp;&nbsp;&nbsp;", substr( $ln, 0, 3 ) );
		$i++;
		if( $i % 20 == 0 ) echo "<br>";
	}
	echo "<br><br>";
	
	echo "<div class=CommonV2>";
	echo "<table class=sortable>";
	echo "<tr>";
	echo "  <th>&bull; FID</th>";
	echo "  <th>&bull; Active</th>";
	echo "  <th>&bull; Last</th>";
	echo "  <th>&bull; First</th>";
	echo "  <th>&bull; ID</th>";
	echo "  <th>&bull; Address</th>";
	echo "  <th>&bull; City</th>";
	echo "  <th>&bull; State</th>";
	echo "  <th>&bull; Zip</th>";
	echo "  <th>&bull; Phone</th>";
	echo "  <th>&bull; E-mail</th>";
	echo "</tr>";

	$j = 0;
#	while( list( $id, $ln, $fn, $addr, $city, $state, $zip, $phone, $email, $other ) = mysql_fetch_array( $gResult ) ) {
	foreach( $pdb as $row ) {
		list( $id, $ln, $fn, $addr, $city, $state, $zip, $phone, $email, $other, $active ) = $row;
		if( empty( $id ) ) continue;
		
		$chr = substr( $ln, 0, 1 );
		$i = ord($chr);
		if( $j % $num_per_tranche == 0 ) {
			$tag = "id=$ln";
			$top = "&nbsp;&nbsp;<a class=nav href=#top>top</a>";
			$j = 0;
		} else {
			$tag = "";
			$top = "";
		}
		$j++;
		$phone = FormatPhone( $phone );
		echo "<tr $tag>";
		echo sprintf( "  <td sorttable_customkey=\"$%s\">%s</td>", addslashes( $other ), addslashes( $other ) );
		echo "<td class=c>$active</td>";
		echo sprintf( "  <td sorttable_customkey=\"$%s\">%s$top</td>", addslashes( $ln ), addslashes( $ln ) );
		echo sprintf( "  <td sorttable_customkey=\"$%s\">%s</td>", addslashes( $fn ), addslashes( $fn ) );
		echo sprintf( "  <td sorttable_customkey=\"$%s\">%s</td>", addslashes( $id ), addslashes( $id ) );
		
		$num_matches = preg_match_all( "/(\d+) (.+$)/", $addr, $matches );
		if( $num_matches ) {
			$skey = $matches[2][0] . " " . $matches[1][0];
		} else {
			$skey = $addr;
		}
		echo sprintf( "  <td sorttable_customkey=\"$%s\">%s</td>", addslashes( $skey ), addslashes( $addr ) );
		echo sprintf( "  <td sorttable_customkey=\"$%s\">%s</td>", addslashes( $city ), addslashes( $city ) );
		echo sprintf( "  <td sorttable_customkey=\"$%s\">%s</td>", addslashes( $state ), addslashes( $state ) );
		echo sprintf( "  <td sorttable_customkey=\"$%s\">%s</td>", addslashes( $zip ), addslashes( $zip ) );
		echo sprintf( "  <td sorttable_customkey=\"$%s\">%s</td>", $phone, $phone );
		echo sprintf( "  <td sorttable_customkey=\"$%s\">%s</td>", addslashes( $email ), addslashes( $email ) );
		echo "</tr>";
	}
	
	echo "</table>";
	echo "</div>";
	
	if( $gTrace ) array_pop( $gFunction );
}

function eeDisplayPhone() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "DisplayPhone()" );
		Logger();
	}

	echo "<h2>Education Edge: Bad Phone #s</h2>";
	echo "<div class=CommonV2>";
	echo "<table>";
	echo "<tr>";
	echo "  <th>Parent ID</th>";
	echo "  <th>Name</th>";
	echo "  <th>Phone #</th>";
	echo "</tr>";

	DoQuery( "select * from parents where length(phone) < 10 order by last_name asc");
	while( $row = mysql_fetch_assoc( $gResult ) ) {
		echo "<tr>";
		echo sprintf( "  <td class=c>%s</td>", $row['id'] );
		echo sprintf( "  <td class=c>%s, %s</td>", addslashes( $row['last_name'] ), addslashes( $row['first_name'] ) );
		echo sprintf( "  <td class=c>%s</td>", addslashes( $row['phone'] ) );
		echo "</tr>";
	}

	echo "</table>";
	echo "</div>";   
	if( $gTrace ) array_pop( $gFunction );
}

function eeDisplayStudents() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "DisplayStudents()" );
		Logger();
	}
	echo "<h2>Education Edge: Students</h2>";
	echo "<p>Columns with a \"&bull;\" are sortable</p>";

	echo "<input type=hidden name=from value=DisplayStudents>";

	$query = "select * from students order by last_name asc, parent1_id, first_name asc";
	DoQuery( $query );
	
	$num_per_tranche = 15;
	$sdb = array();
	$keys = array();
	$i = 0;
	while( $row = mysql_fetch_row( $gResult ) ) {
		if( $i % $num_per_tranche == 0 ) $keys[] = $row[1];		
		$sdb[$i++] = $row;
	}

	$i = 0;
	foreach( $keys as $ln ) {
		printf( "<a href=#$ln>%s</a>&nbsp;&nbsp;&nbsp;", substr( $ln, 0, 3 ) );
		$i++;
		if( $i % 20 == 0 ) echo "<br>";
	}
	echo "<br><br>";

	echo "<div class=CommonV2>";
	echo "<table class=sortable>";
	echo "<tr>";
	echo "  <th>&bull; ID</th>";
	echo "  <th>&bull; Last</th>";
	echo "  <th>&bull; First</th>";
	echo "  <th>&bull; Parent1 ID</th>";
	echo "  <th>&bull; Parent2 ID</th>";
	echo "  <th>&bull; Grade</th>";
	echo "  <th>&bull; Active</th>";
	echo "  <th>Edit</th>";
	echo "</tr>";
	
	$j = 0;
	foreach( $sdb as $row ) {
		list( $id, $ln, $fn, $p1, $p2, $gid, $fid, $active ) = $row;
		$chr = substr( $ln, 0, 1 );
		$i = ord($chr);
		if( $j % $num_per_tranche == 0 ) {
			$tag = "id=$ln";
			$top = "&nbsp;&nbsp;<a class=nav href=#top>top</a>";
			$j = 0;
		} else {
			$tag = "";
			$top = "";
		}
		$j++;
		echo "<tr>";
		printf( "  <td sorttable_customkey=\"%s\" $tag class=c>%s</td>", $id, $id );
		printf( "  <td sorttable_customkey=\"%s\">%s$top</td>", addslashes( $ln ), addslashes( $ln ) );
		printf( "  <td sorttable_customkey=\"%s\">%s</td>", addslashes( $fn ), addslashes( $fn ) );
		printf( "  <td sorttable_customkey=\"%s\">%s</td>", addslashes( $p1 ), addslashes( $p1 ) );
		printf( "  <td sorttable_customkey=\"%s\">%s</td>", addslashes( $p2 ), addslashes( $p2 ) );
		printf( "  <td sorttable_customkey=\"%d\" class=c>%s</td>", $gid, $gGradeIDToLabel[$gid] );
		printf( "  <td class=c>%d</td>", $active );
		$acts = array();
		$acts[] = "setValue('id',$id)";
		$acts[] = "addAction('edit')";
		printf( "  <td><input type=button onClick=\"%s\" value=\"Edit\"></td>", join( ';', $acts ) );
		echo "</tr>";
	}
	
	echo "</table>";
	echo "</div>";

	if( $gTrace ) array_pop( $gFunction );
}

function eeDisplaySwaps() {
	include( "globals.php" );
	if( $gTrace ) {
		array_push( $gFunction, "DisplaySwaps()" );
		Logger();
	}

	echo "<h2>Education Edge: Swapped Ids</h2>";
	echo "<div class=CommonV2>";

	echo "<table>";
	echo "<tr>";
	echo "  <th>Family ID</th>";
	echo "  <th>Parent ID</th>";
	echo "  <th>Count as Parent1</th>";
	echo "  <th>Count as Parent2</th>";
	echo "</tr>";
	DoQuery( "select id, family_id from parents order by family_id asc" );
	$outer = $gResult;
	while( list( $id, $fid ) = mysql_fetch_array( $outer ) ) {
		$id = addslashes( $id );
		DoQuery( "select id from students where parent1_id = '$id'" );
		$n1 = $gNumRows;
		DoQuery( "select id from students where parent2_id = '$id'" );
		$n2 = $gNumRows;

		if( $n1 && $n2 ) {
			echo "<tr><td class=c>$fid</td><td>$id</td><td class=c>$n1</td><td class=c>$n2</td></tr>";
		}
	}
	echo "</table>";
	echo "</div>";   
	if( $gTrace ) array_pop( $gFunction );
}

?>