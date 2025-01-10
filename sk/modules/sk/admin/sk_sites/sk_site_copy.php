<? 
/***************************************
** Title........: sk_site_copy
** Filename.....: sk_site_copy.php
** Author.......: Edgar Bueltemeyer
** Version......: 0.9
** Notes........:
** Last changed.: 24/Feb/2005
** Last change..:
**
** Copies whole sites and re-arranges links
************************************** */

session_start();
$docrelpath="../../../../../";
$title1 = "Site-Copy";
$title2 = "Site-Copy";
$titleimg = "icon.gif";
require_once("../admin_top.inc.php");
if(!isset($_SESSION["sess_hash"])) header("Location:../index.php");


?>
<table cellpadding="0" cellspacing="0" width="100%" border="0"  class="thingrid">

<tr>
    <td  valign="top" align="left" height="1">&nbsp;</td>
    <td  valign="top" align="left" height="1">Site-Copy&nbsp;&nbsp;version 0.9<hr></td>
    <td  valign="top" align="left" height="1">&nbsp;</td>
    <td  valign="top" align="left" height="1">&nbsp;</td>
    <td  valign="top" align="left" height="1">&nbsp;</td>
</tr>
<tr>
    <td  valign="top" align="left" height="1">&nbsp;</td>
    <td  valign="top" align="left" height="1">
    current-site:&nbsp;<?
    echo "(".$current_site->attributes[site_id].")  ".$current_site->attributes["name"];
    if(!$_POST[name]) $new_sitename=$current_site->attributes["name"]."_copy";
    else $new_sitename=$_POST[name];
    ?>
    <form action="sk_site_copy.php" method=post>
    new site-name:<input type="text" class="text" size="35" 
             maxlength="150" 
             name="name"  
             value="<?php echo $new_sitename;?>">
             <br>
    <input type="hidden" name="action" value="copy">
    <input type="submit" value="OK">         
    </form>
    <form action="sk_site_copy.php" method=post>
    Special actions with former copied objects and content_areas:<br>
    <b>WARNING!!</b> Please select fresh copied site!
             <br>
    <SELECT NAME="action">
    <OPTION VALUE="getids">1. view object and content ids in db
    <OPTION VALUE="changelinks">2. change link objects in current site
    <OPTION VALUE="delids">3. delete oject and content ids from db
    </SELECT>

    <input type="submit" value="OK">         
    </form>
    <?
    switch($action){
    case "copy":
    ini_set("max_execution_time","300");
    $new_site=new sksite;
    $new_site->attributes["name"]=$_POST[name];
    define(SITE_PATH,ROOT_PATH.$current_site->attributes["dirname"]);
    define(CONTENT_PATH,SITE_PATH.'content/');
    echo "Starting....copy_from(".$current_site->attributes[site_id].");<br>";
    $new_site->copy_from($current_site->attributes[site_id]);
    echo "New Site:".print_array($new_site->attributes);
    break;
    
    case "getids":
    
     echo "Stored object-ids:<br>";
     $obj_id_store=new skvarstore('obj_id_array');
     if($obj_id_store->attributes[vardata] > '') $obj_id_array=unserialize($obj_id_store->attributes[vardata]);
     echo print_array($obj_id_array);
     echo "Stored content-ids:<br>";
     $ca_id_store=new skvarstore('ca_id_array');
     if($ca_id_store->attributes[vardata] > '') $ca_id_array=unserialize($ca_id_store->attributes[vardata]);
     echo print_array($ca_id_array);
     break;
    
    case "changelinks":
    
     echo "change link objects:<br>";
     $edit_site=new sksite($current_site->attributes[site_id]);
     $edit_site->change_links();
     echo "...done.  now clean up by using option \"3\" in menu above";
     break;
    
    case "delids":
    
     echo "Delete stored object-ids:<br>";
     $obj_id_store=new skvarstore('obj_id_array');
     $obj_id_store->delete();
     $ca_id_store=new skvarstore('ca_id_array');
     $ca_id_store->delete();
     echo "...done";

     break;
    
    
    }
    ?> </td>
    <td  valign="top" align="left" height="1">&nbsp;</td>
    <td  valign="top" align="left" height="1">&nbsp;</td>
    <td  valign="top" align="left" height="1">&nbsp;</td>
</tr>
<tr> 
    <td colspan="4" valign="top" align="left" bgcolor="#666666" height="1"><img src="<?echo SKRES_URL."img/";?>shim.gif" width="100" height="1"></td>
</tr>



    </table>

<? include(MODULE_PATH."sk/admin/admin_foot.inc.php");
?>