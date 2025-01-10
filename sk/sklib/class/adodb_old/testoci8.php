<html>
<body>
<?php
error_reporting(63);
include("adodb.inc.php");
include("tohtml.inc.php");

if (0) {
$db = ADONewConnection('oci8');
$db->PConnect('','scott','tiger');
$db->debug = true;
$rs = &$db->Execute(
	'select * from adoxyz where firstname=:first and trim(lastname)=:last',
	array('first'=>'Caroline','last'=>'Miranda'));
if (!$rs) die("Empty RS");
if ($rs->EOF) die("EOF RS");
rs2html($rs);
}

$db = ADONewConnection('odbc_oracle');
if (!$db->PConnect('orajuris','scott','tiger','natsoft.domain')) die('fail connect');
$db->debug = true;
$rs = &$db->Execute(
	'select * from adoxyz where firstname=? and trim(lastname)=?',
	array('first'=>'Caroline','last'=>'Miranda'));
if (!$rs) die("Empty RS");
if ($rs->EOF) die("EOF RS");
rs2html($rs);
?>