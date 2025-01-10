<?php
 
// this script will output the queries need to change all fields/tables to a different collation
// it is HIGHLY suggested you take a MySQL dump prior to running any of the generated
// this code is provided as is and without any warranty
 
//die("Make a backup of your MySQL database then remove this line");
 
set_time_limit(0);
 
// collation you want to change:
$convert_from = 'latin1_swedish_ci';
 
// collation you want to change it to:
$convert_to   = 'utf8_general_ci';
 
// character set of new collation:
$character_set= 'utf8';
 
$show_alter_table = true;
$show_alter_field = true;
 
// DB login information
$username = 'd0136a20';
$password = 'ullidb';
$database = 'd0136a20';
$host     = 'localhost';
 
$mysqli = new mysqli($host, $username, $password, $database);
 
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
 
$result = $mysqli->query(" SHOW TABLES ");
 
print '<pre>';
while ($row_tables = $result->fetch_row()) {
    $table = $mysqli->real_escape_string($row_tables[0]);
    
    // Alter table collation
    // ALTER TABLE `account` DEFAULT CHARACTER SET utf8
    if ($show_alter_table) {
        echo("ALTER TABLE `$table` DEFAULT CHARACTER SET $character_set;\r\n");
    }
 
    $result = $mysqli->query(" SHOW FULL FIELDS FROM `$table` ");
    while ($row = $result->fetch_assoc()) {
        
        if ($row['Collation']!=$convert_from)
            continue;
 
        // Is the field allowed to be null?
        if ($row['Null']=='YES') {
            $nullable = ' NULL ';
        } else {
            $nullable = ' NOT NULL';
        }
 
        // Does the field default to null, a string, or nothing?
        if ($row['Default']=='NULL') {
            $default = " DEFAULT NULL";
        } else if ($row['Default']!='') {
            $default = " DEFAULT '". $mysqli->real_escape_string($row['Default'])."'";
        } else {
            $default = '';
        }
 
        // Alter field collation:
        // ALTER TABLE `account` CHANGE `email` `email` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
        if ($show_alter_field) {
            $field = $mysqli->real_escape_string($row['Field']);
            echo "ALTER TABLE `$table` CHANGE `$field` `$field` $row[Type] CHARACTER SET $character_set COLLATE $convert_to $nullable $default; \r\n";
        }
    }
}
 
?>
