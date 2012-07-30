<?php   
   if( $gLogoImage ) {
      echo <<<END
<a href="$gLogoURL"><img src="/images/$gLogoImage"></a>\n
END;
   }
   echo "<hr>";
   
   if( $gManager && $gUserVerified ) {
      printf( "User: %s<br>", $gUser['username'] );
      echo "<hr>";
   }
   
   if( $gActionLeft == 'menu' ) {
      echo <<<END
<input type=button onclick="MyAddAction('Logout');" value=Logout><br>
<input type=button onclick="MyAddAction('New Password');" value="New Password"><br>
END;
      if( UserManagerAuthorized('control') ) {
         $feature = "MySetValue('feature','control')";
         echo "<input type=button onclick=\"$feature;MyAddAction('Users');\" value=Users><br>";
         echo "<input type=button onclick=\"$feature;MyAddAction('Levels');\" value=Levels><br>";
         echo "<input type=button onclick=\"$feature;MyAddAction('Privileges');\" value=Privileges><br>";
         echo "<input type=button onclick=\"$feature;MyAddAction('Features');\" value=Features><br>";
      }
   }
   if( $gManager && $gUserVerified ) {
      echo "<hr>";
      DoQuery( "select * from features where name != 'control'", $gDbControl );
      while( $row = mysql_fetch_assoc( $gResult ) ) {
         $feature = sprintf( "MySetValue('feature','%s')", $row['name'] );
         printf( "<input type=button onClick=\"$feature;MyAddAction('%s');\" value=\"%s\">", $row['name'], $row['name'] );
         echo "<input type=button onclick=\"$feature;MyAddAction('Privileges');\" value=Privileges><br>";
      }
   }
   echo "<br>Left panel";

?>