<?php

function DisplayMain() {
   include('globals.php');
   if( $gTrace ) {
      $gFunction[] = "DisplayMain()";
      logger();
   }
   
   echo <<<END
<div id=page>
   <form name=fMain method="post" action="$gSourceCode">
END;

   $hidden_fields = array( 'action', 'area', 'authid', 'fields', 'func', 'gid', 'id', 'monthid', 'next', 'sid' );
   foreach( $hidden_fields as $field ) {
      printf( "<input type=hidden name=%s id=%s>", $field, $field );
   }
   
   echo "<div id=left>";
   include( 'left.php' );
   echo "</div>";
   
   echo "<div id=right>";
   include( 'right.php' );
   echo "</div>";
   
   echo "</div>"; # page

   if( $gTrace ) array_pop( $gFunction );
   
}
?>