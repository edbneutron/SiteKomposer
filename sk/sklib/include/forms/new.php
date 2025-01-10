<?
//get cms-icons
$sql_select = "select * FROM sk_objecttypes WHERE admin_vis=1  AND objgroup='cms' ORDER by sort_nr";
    $result = $skdb->Execute($sql_select);
    if ($result == false):
      echo "SK-objekttypes-select failed! ".$skdb->Errormsg()."<br>";
      echo $sql_select;
    ENDIF;
  $count=$result->RecordCount();

  while (!$result->EOF AND !$result == false) {
   $cms_obj.='<li><a class="obj" href="'.$formtarget.'?type='.$result->fields[type].'&form_template='.$result->fields[template].'&sort_nr='.$sort_nr.'&identifier='.$identifier.'&parent_node='.$parent_node.'&form_window=1&window_title='.$result->fields["name"].'"><img src="'.SKRES_URL.'skinz/'.$GLOBALS['theme'].'/object_icons/'.$result->fields[icon].'" width="50" height="50" border="0"><br>
    '.$result->fields["name"].'</a>';
    $result->MoveNext();
  }

//get module-icons
unset($result);
$sql_select = "select * FROM sk_objecttypes WHERE admin_vis=1  AND objgroup='modules' ORDER by sort_nr";
    $result = $skdb->Execute($sql_select);
    if ($result == false):
      echo "SK-objekttypes-select failed! ".$skdb->Errormsg()."<br>";
    ENDIF;
  $count=$result->RecordCount();

  while (!$result->EOF AND !$result == false) {
   $modules_obj.='<li><a class="obj" href="'.$formtarget.'?type='.$result->fields[type].'&form_template='.$result->fields[template].'&sort_nr='.$sort_nr.'&identifier='.$identifier.'&parent_node='.$parent_node.'&form_window=1&window_title='.$result->fields["name"].'"><img src="'.SKRES_URL.'skinz/'.$GLOBALS['theme'].'/object_icons/'.$result->fields[icon].'" width="50" height="50" border="0"><br>
    '.$result->fields["name"].'</a>';
    $result->MoveNext();
  }

//get block selectors from modules
unset($result);
$sql_select = "SELECT  DISTINCTROW sk_modules .  * , sk_users.uname, sk_groups_modules_link.type, sk_groups_sites_link.site_id
                            FROM ( ( ( sk_groups
                            INNER  JOIN sk_groups_modules_link ON sk_groups.groupid = sk_groups_modules_link.groupid
                            INNER  JOIN sk_groups_sites_link ON sk_groups.groupid = sk_groups_sites_link.groupid )
                            INNER  JOIN sk_groups_users_link ON sk_groups.groupid = sk_groups_users_link.groupid )
                            INNER  JOIN sk_users ON sk_groups_users_link.uid = sk_users.uid )
                            INNER  JOIN sk_modules ON sk_groups_modules_link.mid = sk_modules.id
                            WHERE  ( sk_users.uid = ".$_SESSION["sess_uid"].") AND ( sk_groups_modules_link.type =  'A' ) 
                                   
                                  AND ( sk_groups_sites_link.site_id=".$_SESSION["sess_site_id"].")
                            ORDER  BY sort
                            LIMIT 0, 30";
    $result = $skdb->Execute($sql_select);
    if ($result == false):
      echo "SK-objekttypes-select failed! ".$skdb->Errormsg()."<br>";
    ENDIF;
  $count=$result->RecordCount();

  while (!$result->EOF AND !$result == false) {
	  
	  if($result->fields['settings']>'' && strstr($result->fields['settings'], 'blocks="1"'))
   $modules_obj.='<li><a class="obj" href="'.$formtarget.'?type=moduleblock&dirname='.$result->fields['dirname'].'&sort_nr='.$sort_nr.'&identifier='.$identifier.'&parent_node='.$parent_node.'&form_window=1&window_title='.$result->fields['name'].'"><img src="'.SK_URL.'modules/'.$result->fields['dirname'].'/'.$result->fields[icon].'_small.gif" width="50" height="50" border="0"><br>
    '.$result->fields["name"].'</a>';
    $result->MoveNext();
  }
  
//get special-icons  
unset($result);
$sql_select = "select * FROM sk_objecttypes WHERE admin_vis=1  AND objgroup='special' ORDER by sort_nr";
    $result = $skdb->Execute($sql_select);
    if ($result == false):
      echo "SK-objekttypes-select failed! ".$skdb->Errormsg()."<br>";
    ENDIF;
  $count=$result->RecordCount();

  while (!$result->EOF AND !$result == false) {
   $special_obj.='<li><a class="obj" href="'.$formtarget.'?type='.$result->fields[type].'&form_template='.$result->fields[template].'&sort_nr='.$sort_nr.'&identifier='.$identifier.'&parent_node='.$parent_node.'&form_window=1&window_title='.$result->fields["name"].'"><img src="'.SKRES_URL.'skinz/'.$GLOBALS['theme'].'/object_icons/'.$result->fields[icon].'" width="50" height="50" border="0"><br>
    '.$result->fields["name"].'</a>';
    $result->MoveNext();
  }


?>
<script type="text/javascript" language="Javascript">
<!--
//alert(document.getElementById('licms').className);
 function menuchange(menu) {
 
  switch (menu) {
     case "cms":
       document.getElementById('licms').className="active";
      document.getElementById('limod').className="";
     document.getElementById('lispec').className="";
     document.getElementById('divcms').style.display="inline";
     document.getElementById('divmodules').style.display="none";
     document.getElementById('divspecial').style.display="none";
     
   break;
     case "modules":
       document.getElementById('licms').className="";
      document.getElementById('limod').className="active";
     document.getElementById('lispec').className="";
     document.getElementById('divcms').style.display="none";
     document.getElementById('divmodules').style.display="inline";
     document.getElementById('divspecial').style.display="none";
   break;
   case "special":
        document.getElementById('licms').className="";
      document.getElementById('limod').className="";
     document.getElementById('lispec').className="active";
     document.getElementById('divcms').style.display="none";
     document.getElementById('divmodules').style.display="none";
     document.getElementById('divspecial').style.display="inline";
   break;
  
  }
 
 
 }// end menuchange
 -->
</script>

<div id="formmenu" style="margin:0px 0px 0px -15px; ">
<ul>
<li id="licms" class="active"><a href="#cms" onCLick="menuchange('cms');">CMS</a></li>
<li id="limod" class=""><?if($modules_obj){?><a href="#mod" onCLick="menuchange('modules');">Modules</a><?}?></li>
<li id="lispec" class=""><?if($special_obj){?><a href="#spec" onCLick="menuchange('special');">Special</a><?}?></li>
</ul>
<div class="clear"></div>
</div>

<div id="formbox" style="height:250px; width:350px; margin: 20px 0px 0px 100px; vertical-align:middle;" align="center">
<div id="divcms" style="display:inline;" >
<ul>
<?echo $cms_obj;?>
  </ul>
</div>
<div id="divmodules" style="display:none; ">
<ul>
<?echo $modules_obj;?>
  </ul>
</div>
<div id="divspecial" style="display:none; ">
<ul>
<?echo $special_obj;?>
  </ul>

</div>

</div> 


