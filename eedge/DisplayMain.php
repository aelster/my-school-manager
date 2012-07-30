<?php
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

	echo "<input type=submit name=action value=Refresh>";
	
	echo "<br><br>";

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
?>