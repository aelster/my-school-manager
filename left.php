<?php   
   if( $gLogoImage ) {
      echo <<<END
<a href="$gLogoURL"><img src="/images/$gLogoImage"></a>\n
END;
   }
   echo "<hr>\n";
   
   if( $gActionLeft == 'menu' ) {
      echo <<<END
<input type=button onclick="MyAddAction('Logout');" value=Logout><br>
<input type=button onclick="MyAddAction('Users');" value=Users><br>
<input type=button onclick="MyAddAction('Levels');" value=Levels><br>

END;
   }
   echo "<br>Left panel";

?>