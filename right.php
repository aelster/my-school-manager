<?php
   switch( $gActionRight ) {
      case 'blank':
         echo "Please select an area from the left";
         break;
      
      case 'DisplayMain':
         include("eedge/$gActionRight" . ".php");
         break;
      
      default:
         UserManager($gActionRight);
         break;
   }
   echo "<br>Right panel";
?>