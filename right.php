<?php
   if( $gManager ) {
      if( $gUserVerified ) {
         if( $gUser['pwdchanged'] == '0000-00-00 00:00:00' ) {
            UserManager('newpassword');
            
         } elseif( $gAction == 'Levels' ) {
            UserManager( 'levels' );
            
         } elseif( $gAction == 'Users' ) {
            UserManager( 'control' );
            
         }
      } else {
         if( $gAction == 'Reset Password' ) {
            UserManager('reset');
            
         } elseif( $gAction == 'Resend' ) {
            UserManager('resend');
         
         } else {
            UserManager('login');
         }
      }
   } else {
      
   }
/*   
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
*/
   echo "<br>Right panel";
?>