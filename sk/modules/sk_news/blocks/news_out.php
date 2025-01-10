
<? if (isset($news_id)):
global $newsimgrelpath;
$section_id=$this->attributes_vars[section_id];
/* ****************** SQL-Selects to fill data********************* */
	$sql_select = "select *, DATE_FORMAT(ndate, '%d-%m-%Y') as ndate from sk_news  WHERE section_id=$section_id AND id=$news_id ORDER by ndate desc";
	$result = $skdb->Execute($sql_select);
	if ($result == false):
	  echo "News-DB-select failed! ".$skdb->Errormsg()."<br>";
	ENDIF;
    $output.='
	<table width="85%">
		<tr><td align="left" valign="top" class="content"> 
      <SPAN class="newsdate">'.$result->fields["ndate"].'</SPAN><br>
	  <SPAN class="newstitle">'.$result->fields["title"].'</SPAN><br>
	  <SPAN class="newslead">'.$result->fields["lead"].'</SPAN>
	  <p><SPAN class="newstext">'.nl2br($result->fields["newstext"]).'</SPAN></p>
      </td>
	  <td align="right" valign="top">';
   if ($result->fields["image"]>""): $output.='<img src="'.$newsimgrelpath.$result->fields["image"].'" border="0" alt="">';ENDIF;
   if ($result->fields["file1"]>"" || $result->fields["file2"]>""):
	  $output.='Dateien:
	  <a href="http://'.SERVER_ADDRESS.'content/news/'.'files/'.$result->fields["file1"].'">'.$result->fields["file1"].'</a><br>
	  <a href="http://'.SERVER_ADDRESS.'content/news/'.'files/'.$result->fields["file2"].'">'.$result->fields["file2"].'</a>';
   endif;
	  $output.='</td></tr></table>';
ENDIF;


