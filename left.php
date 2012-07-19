<?php   
   if( $gLogoImage ) {
      echo <<< END
<a href="$gLogoURL"><img src="/images/$gLogoImage"></a>
END;
   }
   echo "<hr>";
   
   SessionStuff('start');
   
   if( empty( $_SESSION['userid'] ) ) {
      echo "Please log in";
   }
   echo "<br>Left panel";

?>