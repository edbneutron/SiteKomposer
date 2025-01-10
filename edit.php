<?
/************************************************
* Site-Kreator Edit-Relocator
* 
* by Edgar Bueltemeyer (c) 2005. Version 1
* 
* Description:
* Relocate to current Site - first page
***********************************************/

session_start();
$docrelpath="";//relative path to site-root
require_once($docrelpath."sk/sk.php"); //load Site Kreator Main-Include

if(!isset($_SESSION[sess_hash])) {header("Location:".SK_URL."modules/sk/admin/index.php");exit;}

$current_site = new sksite($_SESSION[sess_site_id]);

$pageqry = "SELECT * FROM sk_nav_tree WHERE site_id = ".$_SESSION[sess_site_id]." ORDER BY id,sort_nr,p LIMIT 0,1";

  $result = $skdb->Execute($pageqry);
  if ($result == false || $result->fields[id]==NULL) {
     //Page not found Error 404
     $pageqry = "SELECT * FROM sk_nav_tree WHERE linkname = '#404'";
     $result = $skdb->Execute($pageqry);
     $mid=$result->fields[id];
   if (!$result || $result == false || $result == EOF){
       die("Allgemeiner Datenbankfehler!".$skdb->Errormsg());
       exit;
       }
  }
$mid=$result->fields[id];
header("Location:index.php?mid=$mid&edit=1");
$GLOBALS[edit]=1;
//include("index.php");?>