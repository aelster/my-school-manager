<?php   
   if( $gLogoImage ) {
      echo <<<END
<a href="$gLogoURL"><img src="/images/$gLogoImage"></a>\n
END;
   }
   echo "<hr>";
   
   if( $gManager ) {
      printf( "User: %s<br>", $gUser['username'] );
      echo "<hr>";
   }
   
   if( $gActionLeft == 'menu' ) {
      echo <<<END
<input type=button onclick="MyAddAction('Logout');" value=Logout><br>
<input type=button onclick="MyAddAction('New Password');" value="New Password"><br>
END;
      if( UserManagerAuthorized('control') ) {
         echo "<input type=button onclick=\"MyAddAction('Users');\" value=Users><br>";
         echo "<input type=button onclick=\"MyAddAction('Levels');\" value=Levels><br>";
         echo "<input type=button onclick=\"MyAddAction('Privileges');\" value=Privileges><br>";
         echo "<input type=button onclick=\"MyAddAction('Features');\" value=Features><br>";
      }
   }
   echo "<br>Left panel";

?>