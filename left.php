<?php   
   if( $gLogoImage ) {
      echo <<<END
<a href="$gLogoURL"><img src="/images/$gLogoImage"></a>\n
END;
   }
   echo "<hr>\n";
   
   SessionStuff('start');
   
   if( empty( $_SESSION['userid'] ) ) {
      echo "Please log in";
   }
   echo "<br>Left panel";

?>