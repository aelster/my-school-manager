<?php

function DisplayMain() {
   include('globals.php');
   if($gTrace) {
      $gFunction[] = "DisplayMain()";
      Logger();
   }
   
   echo <<<END
<div id=page>
   <form name=fMain method="post" action="$gSourceCode">
END;

   $hidden_fields = array( 'action', 'area', 'fields', 'func', 'id' );
   foreach( $hidden_fields as $field ) {
      printf( "<input type=hidden name=%s id=%s>\n", $field, $field );
   }
   
   if( $gTrace ) Logger("left ($gActionLeft):" );
   echo "<div id=left>";
   include( 'left.php' );
   echo "</div>";
   
   if( $gTrace ) Logger("right ($gActionRight):");
   echo "<div id=right>";
   include( 'right.php' );
   echo "</div>";
   
   echo "</div>"; # page

   if($gTrace) array_pop($gFunction);
}

function LocalInit() {
   include('globals.php');
   
   $gSourceCode = str_replace( "index.php", "", $_SERVER["PHP_SELF"] );
   $qs = preg_split( '/&/', $_SERVER['QUERY_STRING'] );
   $nqs = array();
   foreach( $qs as $q ) {
      if( ! preg_match( '/^pwd/', $q ) ) {
         if( ! empty( $q ) ) $nqs[] = $q;
      }
   }
   if( count( $nqs ) ) $gSourceCode .= '?' . join('&',$nqs );
}

function PrepareForAction() {
   include('globals.php');
   if( $gTrace ) {
      $gFunction[] = "PrepareForAction()";
      Logger();
   }
   
   if( $gManager ) $gActionLeft = 'menu';
   $gActionRight = 'blank';
   
   switch( $gAction ) {
      case 'Continue':
         if( $gManager && $gFrom == 'UserManagerResend' ) $gActionRight = 'login';
         break;
      
      case 'Features':
         $gActionRight = 'features';
         break;
      
      case 'Levels':
         $gActionRight = 'levels';
         break;
      
      case 'Login':
         UserManager('verify');
         if( $gUserVerified ) {
            if( $gUser['pwdchanged'] == '0000-00-00 00:00:00' ) {
               $gActionLeft = "blank";
               $gActionRight = 'newpassword';
            } else {
               $gActionRight = 'blank';
            }
         } else {
            $gActionLeft = "blank";
            $gActionRight = "login";
         }
         break;
      
      case 'Logout':
         UserManager('logout');
         if( $gManager ) $gActionRight = 'login';
         break;
      
      case 'New Password':
         if( $gManager ) $gActionRight = 'newpassword';
         break;
      
      case 'Privileges':
         if( $gManager ) $gActionRight = 'privileges';
         break;
      
      case 'Resend':
         if( $gManager ) $gActionRight = 'resend';
         break;
      
      case 'Reset Password':
         if( $gManager ) $gActionRight = 'reset';
         break;
      
      case 'Start':
         if( $gManager ) $gActionRight = 'login';
         break;
      
      case 'Update':
         $area = $_POST['area'];
         switch( $area ) {
            case 'features':
               UserManager('update');
               $gActionRight = 'features';
               break;
            
            case 'levels':
               UserManager('update');
               $gActionRight = 'levels';
               break;
            
            case 'newpass':
               UserManager('update');
               $gActionRight = 'blank';
               break;
            
            case 'privileges':
               UserManager('update');
               $gActionRight = 'privileges';
               break;
            
            case 'users':
               UserManager('update');
               $gActionRight = 'control';
               break;
            
         }
         UserManagerInit();
         UserManager('load', $gUserId );
         break;
      
      case 'Users':
         $gActionRight = 'control';
         break;
   }

   if( $gTrace ) array_pop( $gFunction );
}   
   
function WriteFooter() {
   include('globals.php');
   if( $gTrace ) {
      $gFunction[] = "WriteFooter()";
      Logger();
   }
   
   echo <<<END
</div>
</form>
</body>
</html>
END;
   if($gTrace ) array_pop( $gFunction );
}

function WriteHeader() {
   include( "globals.php" );
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
   $title = "$gTitle";
   $title .= isset( $_REQUEST['bozo'] ) ? " - Debug" : "";
   $title .= " ($gSiteName)";

   echo <<<END
<title>$title</title>
<meta name="cache-control" content="no-cache">
<meta name="pragma" content="no-cache">
<!-- overLIB 4.21 (c) Erik Bosrup -->
END;

   $scripts = array();
   $scripts[] = "/overlib/overlib.js";
   $scripts[] = "/overlib/overlib_hideform.js";
   $scripts[] = "/scripts/MyUtilities.js";
   $scripts[] = "/scripts/sha256.js";
   foreach ( $scripts as $file ) {
      echo sprintf( "<script type=\"text/javascript\" src=\"%s\"></script>\n", $file );
   }

   echo <<<END
<link type="text/css" href="/css/parent_portal.css" rel="stylesheet">
</head>
<body>
END;

   AddOverlib();
}

?>