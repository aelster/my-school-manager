<?php
   if( $gAction == 'Reset Password' ) {
      UserManager('reset');
      
   } elseif( $gUser['pwdchanged'] == '0000-00-00 00:00:00' ) {
		UserManager('newpassword');
	
   } elseif( $gAction == 'Resend' ) {
      UserManager('resend');

   } elseif( $gAction == 'Logout' ) {
      echo "<h3>You have been sucessfully logged out</h3>";

   } elseif( empty( $_SESSION['userid'] ) && $gManager ) {
      UserManager('login');
   }
   echo "<br>Right panel";
?>