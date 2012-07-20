<?php   
   if( $gLogoImage ) {
      echo <<<END
<a href="$gLogoURL"><img src="/images/$gLogoImage"></a>\n
END;
   }
   echo "<hr>\n";
   
   if( empty( $_SESSION['userid'] ) ) {
      echo "Please log in";
   } elseif( $gUserVerified ) {
      echo <<<END
<input type=button onclick="MyAddAction('Logout');" value=Logout>
END;

   }
   echo "<br>Left panel";

?>