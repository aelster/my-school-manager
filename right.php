<?php
   if( $gAction == 'Reset Password' ) {
      UserManager('reset');
      
   } elseif( $gAction == 'Resend' ) {
      UserManager('resend');

   } elseif( empty( $_SESSION['userid'] ) && $gManager ) {
      UserManager('login');
   }
   echo "<br>Right panel";
?>