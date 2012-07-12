<?php
function WriteFooter() {
   include( "globals.php" );
   if( $gTrace ) {
      $gFunction[] = "WriteFooter";
      Logger();
   }
?>
</div>
</form>
</body>
</html>
<?php
   if($gTrace ) array_pop( $gFunction );
}

?>