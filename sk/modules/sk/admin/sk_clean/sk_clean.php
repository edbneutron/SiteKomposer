<?php
$title1 = "Dateien s&auml;ubern";
$title2 = "Dateien s&auml;ubern";
$titleimg = "icon.gif";

require_once("../admin_top.inc.php");
/*SQL-Updates to update/insert/delete data*/
$table="sk_objects";
if ($_POST[current_type]) $current_type=$_POST[current_type];
//if (!isset($current_type)) $current_type='image';
// AND (sk_nav_tree.site_id='".$current_site->attributes[site_id]."')
if($current_type=="image") $where2=" OR (sk_objects.type='text_image')";
$objlistqry="SELECT sk_objects.id, sk_objects.file, sk_nav_tree.id AS mid
                FROM (sk_objects INNER JOIN sk_content ON sk_objects.content_id = sk_content.id) INNER JOIN sk_nav_tree ON sk_content.mid = sk_nav_tree.id
                WHERE (sk_objects.type='".$current_type."') $where2
		AND sk_objects.file > ' '
                ORDER BY sk_nav_tree.sort_nr, sk_nav_tree.p ASC, sk_objects.sort_nr";
				
$objresult= $skdb->Execute($objlistqry);

              if ($objresult == false || $objresult->EOF ){ 
			  	DEBUG_out(2,"error2","object list empty ".$objlistqry.$skdb->Errormsg());
				}	
			
$objlist=$objresult->GetArray();


$contentpath=ROOT_PATH.$current_site->attributes["dirname"]."content/";
$dirname=$contentpath.$current_type."/";
$obsoletedir=$contentpath."cleaned/".$current_type."/";

$filecount=0;

if ($dir = @opendir($dirname)) {
 while (($file = readdir($dir)) !== false) {
   if($file=="." or $file==".." or $file=="thumbnails") continue;
   $filecount++;
   $filearray[$filecount]=$file;
   
 }  
 closedir($dir);       
}
?>
<form target="_self" method="post"><?
echo "<div style=\"background-color:#eeeeee\"><b>Dateityp: ".$current_type."</b>";
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<select name=current_type size=1>
<option value="">w&auml;hlen
<option value="image" <?if($current_type=="image"||$current_type=="text_image")echo"selected";?>>image/text_image
<option value="file" <?if($current_type=="file")echo"selected";?>>file
<option value="sound" <?if($current_type=="sound")echo"selected";?>>sound
</select><input type="submit" value="OK" class="small">  </div>
<hr size="0">
</form><?

if ($current_type>""){

echo "Verzeichnis: ".$dirname."<br><br>";
echo count($objlist)." Dateien in DB benutzt<br>";
echo $filecount." Dateien im Verzeichnis gefunden<p>";

reset ($objlist);
$dbfilecount=0;
while (list($key, $value) = each ($objlist)) {
   $dbfilecount++;
   $dbfilearray[$dbfilecount]=$value[1];
   $dbfilearray2[$dbfilecount][1]=$value[2];
   $dbfilearray2[$dbfilecount][2]=$value[0];
}
if (is_array($filearray)&& is_array($dbfilearray)){
sort($filearray);
sort($dbfilearray);

$obsolete=array_diff($filearray,$dbfilearray);
$missing=array_diff($dbfilearray,$filearray);
}
?>
<form target="_self" method="post">
<table width="400" border="0" cellspacing="0" cellpadding="0">
<tr>
    <!--<td valign="top">gefundene Files</td>
    <td valign="top">benutzte Files</td>-->
	<td valign="top"><?=count($obsolete)?> &Uuml;berfl&uuml;ssige Files</td>
	<td valign="top"><?=count($missing)?> fehlende Files</td>
  </tr>
<tr>
    <!--<td valign="top">gefundene Files</td>
    <td valign="top">benutzte Files</td>-->
	<td valign="top"><?if(count($obsolete)>-0):?><div style="background-color:#eeeeee">Files in Clean_Verzeichnis schieben:</div><?endif;?></td>
	<td valign="top"><?if(count($obsolete)>-0):?><input type="submit" value="OK" class="small"><?endif;?><br></td>
</tr>
    <!--
<tr>
	<td valign="top"><?if(count($obsolete)>-0):?><div style="background-color:#eeeeee">Files in Clean_Verzeichnis schieben:</div><?endif;?></td>
	<td valign="top"><?if(count($obsolete)>-0):?><input type="submit" value="OK"><?endif;?></td>
</tr>          -->

  <tr>
    <!--<td valign="top"><? //echo print_array($filearray); ?></td>
    <td valign="top"><? //echo print_array($dbfilearray); ?></td>-->
	<td valign="top"><? echo print_array($obsolete); ?></td>
	<td valign="top"><? echo print_array($missing); ?></td>
  </tr>
</table>
<input type="hidden" name="current_type" value="<?=$current_type?>">
<input type="hidden" name="action" value="move">
</form>
<form target="_self" method="post">
<table width="400" border="0" cellspacing="0" cellpadding="0">
<tr>

	<td valign="top"><br><br></td>
	<td valign="top"></td>
  </tr>
<tr>
    <!--<td valign="top">gefundene Files</td>
    <td valign="top">benutzte Files</td>-->
	<td valign="top"><?if(count($filearray)>-0):?><div style="background-color:#eeeeee">change File-Permission to 0777</div><?endif;?></td>
	<td valign="top"><?if(count($filearray)>-0):?><input type="submit" value="OK" class="small"><?endif;?><br></td>
</tr>

</table>
<input type="hidden" name="current_type" value="<?=$current_type?>">
<input type="hidden" name="action" value="change_permission">
</form>
<?		
if($_POST["action"]=="move") {
/* move obsolete Files to cleaned-dir    */

foreach ($obsolete as $file){

	
	$extension=strrchr ($file, ".");
	$basename=str_replace(" ","_",substr ($file, 0, - strlen($extension)));
	$tempname=$basename;
	$i=1;
	WHILE (file_exists($obsoletedir.$tempname.$extension)) {
          $tempname=$basename."$i";
          $i++;
        }
    
        $newfile=$tempname.$extension;
        echo $dirname.$file."->".$obsoletedir.$newfile."";
	rename($dirname.$file,$obsoletedir.$file);
	if (($current_type=='image' || $current_type=='text_image') && is_file($dirname."thumbnails/".$file))
	   rename($dirname."thumbnails/".$file,$obsoletedir."thumbnails/".$newfile);
	echo "ok. <br>";

} // foreach
echo "<p><b>OK, Files verschoben.</b> <br>"; }

if($_POST["action"]=="change_permission") {
/* change File-permissions to 0777    */

foreach ($filearray as $file){

	
	$extension=strrchr ($file, ".");
	$basename=substr ($file, 0, - strlen($extension));
	$success=false;
        echo $dirname.$file."-> 0777";
	$success=chmod($dirname.$file,0777);
	if($success) echo "ok. <br>"; else echo"failed!!! <br>";
	$success=false;
	if (($current_type=='image' || $current_type=='text_image') && is_file($dirname."thumbnails/".$file)) 
	  {
		  $success=chmod($dirname."thumbnails/".$file,"0777");
		  echo $dirname."thumbnails/".$file."-> 0777";
		  if($success) echo "ok. <br>"; else echo"failed!!! <br>";
          }
	

} // foreach


}

}
include(MODULE_PATH."sk/admin/admin_foot.inc.php");
?>
