<?php
   include('globals.php');
   
   if( $gLogoImage ) {
      echo <<< END
<a href="$gLogoURL"><img src="/images/$gLogoImage"></a>
END;
   }
   echo "<hr>";
   
   SessionStuff('start');
   
   UserManager('login');
   echo "left panel";
?>