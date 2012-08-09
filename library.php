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

   $hidden_fields = array( 'action', 'area', 'feature', 'fields', 'func', 'id' );
   foreach( $hidden_fields as $field ) {
      printf( "<input type=hidden name=%s id=%s>\n", $field, $field );
   }
   
   echo "<div id=left>";
   include( 'left.php' );
   echo "</div>";
   
   Logger("gActionRight: $gActionRight" );
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
      $gFunction[] = "PrepareForAction($gFeature)";
      Logger();
   }
   
   if( $gManager && $gUserVerified ) $gActionLeft = 'menu';
   $gActionRight = 'blank';

   if( $gFeature == 'control' ) { 
      switch( $gAction ) {
         case 'Continue':
            if( $gManager && $gFrom == 'UserManagerResend' ) $gActionRight = 'login';
            break;
         
         case 'Features':
            $gActionRight = 'features';
            $gFeature = "control";
            break;
         
         case 'Levels':
            $gActionRight = 'levels';
            $gFeature = "control";
            break;
         
         case 'Login':
            UserManager('verify');
            $gFeature = "control";
            if( $gUserVerified ) {
               if( $gUser['pwdchanged'] == '0000-00-00 00:00:00' ) {
                  $gActionLeft = "blank";
                  $gActionRight = 'newpassword';
               } else {
                  $gActionLeft = 'menu';
                  $gActionRight = 'blank';
               }
            } else {
               $gActionLeft = "blank";
               $gActionRight = "login";
            }
            break;
         
         case 'Logout':
            UserManager('logout');
            if( $gManager ) {
               $gFeature = "control";
               $gActionLeft = 'blank';
               $gActionRight = 'login';
            }
            break;
         
         case 'New Password':
            if( $gManager ) {
               $gActionRight = 'newpassword';
               $gFeature = "control";
            }
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
   } elseif( $gFeature == 'eedge' ) {
      switch( $gAction ) {
         case 'display':
            $disp_funcs['admin'] = 1;
            $disp_funcs['address'] = 1;
            $disp_funcs['email1'] = 1;
            $disp_funcs['email2'] = 1;
            $disp_funcs['grades'] = 1;
            $disp_funcs['parents'] = 1;
            $disp_funcs['phone'] = 1;
            $disp_funcs['students'] = 1;
            $disp_funcs['swaps'] = 1;
            $func = $_POST['func'];
            if( ! empty( $disp_funcs[$func] ) ) {
               $tag = ucfirst($func);
               $gActionRight = "Display" . $tag;
            }
            break;
            
         case 'eedge':
            $gActionRight = 'DisplayMain';
            $gFeature = 'eedge';
            break;
 
         case 'Privileges':
            if( $gManager ) $gActionRight = 'privileges';
            break;
         
         case 'rebuild':
            $gActionRight = 'DirectorBuild';
            break;
         
         case 'Refresh':
            $gActionRight = 'DisplayMain';
            break;
         
         case 'Update':
            $area = $_POST['area'];
            switch( $area ) {
               case 'privileges':
                  UserManager('update');
                  $gActionRight = 'privileges';
                  break;
            }
            UserManagerInit();
            UserManager('load', $gUserId );
            break;
      }      
   } elseif( $gFeature == 'lunches' ) {
      $gDb = $gDbLunches;
      switch( $gAction ) {
         case 'lunches':
            $gActionRight = 'DisplayMain';
            break;

         case 'Privileges':
            if( $gManager ) $gActionRight = 'privileges';
            break;

         case 'Update':
            $area = $_POST['area'];
            switch( $area ) {
               case 'privileges':
                  UserManager('update');
                  $gActionRight = 'privileges';
                  break;
            }
            UserManagerInit();
            UserManager('load', $gUserId );
            break;
      }
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
   $scripts[] = "/scripts/sorttable.js";
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