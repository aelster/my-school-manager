<?php
   if( $gTrace ) {
      $gFunction[] = "right(F:$gFeature, A:$gActionRight)";
      Logger();
   }
   if( $gFeature == 'control' ) {
      switch( $gActionRight ) {
         case 'blank':
            echo "Please select an area from the left";
            break;
         
         default:
            UserManager($gActionRight);
            break;
      }
   } elseif( $gFeature == 'eedge' ) {
      switch( $gActionRight ) {
         case 'privileges':
            UserManager('privileges');
            break;

         default:
            include("eedge/ee_library.php");
            $f = "ee$gActionRight";
            $f();
            break;
      }               
   }
   echo "<br>Right panel";
   if( $gTrace ) array_pop($gFunction);
?>