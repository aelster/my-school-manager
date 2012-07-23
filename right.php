<?php
   switch( $gActionRight ) {
      case 'blank':
         echo "Please select an area from the left";
         break;
      
      case 'control':
         UserManager('control');
         break;
      
      case 'get-new-password':
         UserManager('newpassword');
         break;
      
      case 'levels':
         UserManager('levels');
         break;
      
      case 'login':
         UserManager('login');
         break;
      
      case 'resend':
         UserManager('resend');
         break;

      case 'reset':
         UserManager('reset');
         break;
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