<?php
   if( empty( $_SESSION['userid'] ) && $gManager ) {
      UserManager('login');
   }
   echo "<br>Right panel";
?>