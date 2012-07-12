<?php
   include('globals.php');
   
   if( $gLogoImage ) {
      echo <<< END
<a href="$gLogoURL"><img src="/images/$gLogoImage"></a>
END;
   }
   echo "<hr>";
   echo "left panel";
?>