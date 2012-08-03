<?php

$base = "/Users/andy/Downloads/lunchreports";

#
# To use this script do the following:
#
#  1.  Download the latest Excel files from Education Edge to $base
#  2.  Save the Parents and Students as Parents.txt, format:  Tab Delimited Text
#  3.  Save the Homeroom file as Homerooms.txt, format:  Tab Delimited Text
#
#

require( "lib/swift_required.php" );

chdir('..');
include('globals.php');
include('library.php');

require_once('SiteLoader.php');
SiteLoader('Common');

require('local-eedge.php');
$gDb = OpenDb();

$mysql_suffix = "";

$headers = array(
   "grade", "slast", "sfirst", "studentid",
   "p1last", "p1first", "p1id", "p1address", "p1city", "p1state", "p1zip", "p1home", "p1email", "p1cell",
   "p2last", "p2first", "p2id", "p2address", "p2city", "p2state", "p2zip", "p2home", "p2email", "p2cell"
);

$phone_cols = array();
for( $i = 0; $i<count($headers); $i++ ) {
   if( preg_match( '/home/', $headers[$i] ) ) $phone_cols[] = $i;
   if( preg_match( '/cell/', $headers[$i] ) ) $phone_cols[] = $i;
}

echo "Base:  $base\n";
$fname = "$base/Parents.txt";
echo "Reading " . basename($fname) . " ...";

$fp = fopen( $fname, "r" );
$line = fgets( $fp );
fclose( $fp );
echo " Done.  ";
$text = preg_split( '/\015/', $line );
$keys = array_shift( $text );

$cols = array();
foreach( $text as $str ) {
   $fields = preg_split( '/\011/', $str );
   for( $i=0; $i < count($fields); $i++ ) {
      if( ! isset( $cols[$i] ) ) $cols[$i] = 0;
      $fields[0] = intval( $fields[0] );
      foreach( $phone_cols as $j ) {
         $fields[$j] = preg_replace("/[^0-9]/", "", $fields[$j]);
      }
      $cols[$i] = max( $cols[$i], strlen($fields[$i]) );
   }
}

$tmp = array();
for( $i=0; $i < count($headers); $i++ ) {
   $tmp[] = sprintf( "`%s` varchar (%d) DEFAULT NULL", $headers[$i], $cols[$i] );
}

$table_name = 'zz_parent_students';

$fname = "$base/$table_name.sql";
echo "  Writing " . basename($fname) . " ...";
$fp = fopen( $fname, "w" );
fprintf( $fp, "drop table if exists `$table_name`;\n" );
$query = "create table if not exists `$table_name` (\n";
$query .= join( ",\n", $tmp );
$query .= "\n) engine=innodb default charset=utf8;";

fprintf( $fp, "$query\n" );

$num_headers = count( $headers );

foreach( $text as $str ) {
   $fields = preg_split( '/\011/', $str);
   $fields[0] = intval( $fields[0] );
   foreach( $phone_cols as $i ) {
      $fields[$i] = preg_replace("/[^0-9]/", "", $fields[$i]);
   }
   $tmp = array();
   foreach( $fields as $fld ) {
      $tmp[] = sprintf( "'%s'", addslashes($fld) );
   }
   $query = "insert into `$table_name` values (" . join( ',', $tmp ) . ");";
   fprintf( $fp, "$query\n" );
}
fclose( $fp );
printf( " Done (%d records).\n", count( $text ) );


$headers = array(
   "Current Grade", "Last name", "First name","Full name","studentid","Homeroom"
);

$fname = "$base/Homerooms.txt";
echo "Reading " . basename($fname) . " ...";
$fp = fopen( $fname, "r" );
$line = fgets( $fp );
fclose( $fp );
echo " Done.  ";

$text = preg_split( '/\015/', $line );
$keys = array_shift( $text );

$cols = array();
foreach( $text as $str ) {
   $fields = preg_split( '/\011/', $str );
   for( $i=0; $i < count($fields); $i++ ) {
      if( ! isset( $cols[$i] ) ) $cols[$i] = 0;
      $fields[0] = intval( $fields[0] );
      $cols[$i] = max( $cols[$i], strlen($fields[$i]) );
   }
}

$tmp = array();
for( $i=0; $i < count($headers); $i++ ) {
   $tmp[] = sprintf( "`%s` varchar (%d) DEFAULT NULL", $headers[$i], $cols[$i] );
}

$table_name = 'zz_ls_homerooms';
$fname = "$base/$table_name.sql";
echo "  Writing " . basename($fname) . " ...";

$fp = fopen( $fname, "w" );
fprintf( $fp, "drop table if exists `$table_name`;\n" );
$query = "create table if not exists `$table_name` (\n";
$query .= join( ",\n", $tmp );
$query .= "\n) engine=innodb default charset=utf8;";

fprintf( $fp, "$query\n" );

$num_headers = count( $headers );

foreach( $text as $str ) {
   $fields = preg_split( '/\011/', $str);
   $fields[0] = intval( $fields[0] );
   $tmp = array();
   foreach( $fields as $fld ) {
      $tmp[] = sprintf( "'%s'", addslashes($fld) );
   }
   $query = "insert into `$table_name` values (" . join( ',', $tmp ) . ");";
   fprintf( $fp, "$query\n" );
}
fclose( $fp );

printf( " Done (%d records).\n", count( $text ) );
?>