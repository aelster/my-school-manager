<?php
   switch( $gActionRight ) {
      case 'blank':
         echo "Please select an area from the left";
         break;
      
      default:
         UserManager($gActionRight);
         break;
   }
   echo "<br>Right panel";
?>