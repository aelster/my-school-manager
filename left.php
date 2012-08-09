<?php   
   if( $gTrace ) {
      $gFunction[] = "left(F:$gFeature, A:$gActionLeft)";
      Logger();
   }
   
   if( $gLogoImage ) {
      echo <<<END
<a href="$gLogoURL"><img src="/images/$gLogoImage"></a>\n
END;
   }
   echo "<hr>";
   
   if( $gManager ) {
      if( $gUserVerified ) {
         printf( "User: %s<br>", $gUser['username'] );
         echo "<hr>";
      }
   
      if( $gActionLeft == 'menu' ) {
         $feature = "MySetValue('feature','control')";
         echo <<<END
<input type=button onclick="$feature;MyAddAction('Logout');" value=Logout><br>
<input type=button onclick="$feature;MyAddAction('New Password');" value="New Password"><br>
END;
         if( UserManagerAuthorized('control') ) {
            $feature = "MySetValue('feature','control')";
            echo "<input type=button onclick=\"$feature;MyAddAction('Users');\" value=Users><br>";
            echo "<input type=button onclick=\"$feature;MyAddAction('Levels');\" value=Levels><br>";
            echo "<input type=button onclick=\"$feature;MyAddAction('Privileges');\" value=Privileges><br>";
            echo "<input type=button onclick=\"$feature;MyAddAction('Features');\" value=Features><br>";
         }
      }
      if( $gUserVerified ) {
         echo "<hr>";
         DoQuery( "select * from features where name != 'control'", $gDbControl );
         while( $row = mysql_fetch_assoc( $gResult ) ) {
            $feature = sprintf( "MySetValue('feature','%s')", $row['name'] );
            printf( "<input type=button onClick=\"$feature;MyAddAction('%s');\" value=\"%s\">", $row['name'], $row['name'] );
            echo "<input type=button onclick=\"$feature;MyAddAction('Privileges');\" value=Privileges><br>";
         }
      }
   } else {
      DoQuery( "select name from features where enabled > 0 order by name asc", $gDbControl );
      while( list($name) = mysql_fetch_array( $gResult ) ) {
         if( $name == 'control' ) continue;
         if( $name == 'eedge' ) continue;
         $feature = sprintf( "MySetValue('feature','%s')", $name );
         printf( "<input type=button onClick=\"$feature;MyAddAction('%s');\" value=\"%s\">", $name, $name );
      }
   }
   echo "<br>Left panel";
   if( $gTrace ) array_pop( $gFunction );
?>