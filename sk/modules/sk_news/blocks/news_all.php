
<? 
  global $current_page;
  global $PHP_SELF;
  global $QUERY_STRING;
  global $REQUEST_URI;
  global $edit;
  if ($edit==1) $editon="&edit=1"; else $editon="";
  if($this->attributes_vars['detail_link']>"") {$detail_link=$this->attributes_vars[detail_link];} else {$detail_link=$PHP_SELF.'?'.$QUERY_STRING;}
  $section_id=$this->attributes_vars['section_id'];
  if ($news_id>""){
global $newsimgrelpath;

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
	  <a href="content/news/files/'.$result->fields["file1"].'">'.$result->fields["file1"].'</a><br>
	  <a href="content/news/files/'.$result->fields["file2"].'">'.$result->fields["file2"].'</a>';
   endif;
	  $output.='</td></tr>
	  <tr><td colspan="2"><a href="'.$PHP_SELF.'?mid='.$GLOBALS['mid'].$editon.'" class="newslink"><b>zur&uuml;ck</b></a><br></td></tr></table>';
  }else{


  

 	/* ****************** SQL-Selects to fill data********************* */
	$curr_page = $current_page;
	$num_of_rows_per_page=4;
	
	if (empty($curr_page)) $curr_page = 1;
	
	$sql_select = "select sk_news.id, title, lead, ndate AS sortdate, DATE_FORMAT(ndate, '%d-%m-%Y') as ndate from sk_news  WHERE section_id=$section_id order by sortdate desc";
	$result1 = $skdb->Execute($sql_select);
	
	if (!$result1->EOF):
	$result = $skdb->PageExecute($sql_select, $num_of_rows_per_page, $curr_page);
		if ($result == false):
	  	$output.= "News-DB-select failed! ".$skdb->Errormsg()."<br>";
		ENDIF;
	 ENDIF;

	while (!$result->EOF AND !$result == false) {
$output.='
      <a href="'.$detail_link.'&news_id='.$result->fields["id"].$editon.'" class="newslink"><b>'.$result->fields["title"].'</b></a><br>
	  <SPAN class="newsdate"><em>'.$result->fields["ndate"].'</em></SPAN><br><SPAN class="newstext">'.$result->fields["lead"].'&nbsp;</SPAN>
      <a href="'.$detail_link.'&news_id='.$result->fields["id"].$editon.'" class="newslink"><strong>Details</strong></a><br>
	  &nbsp;<br>
      ';
 $result->MoveNext();
  }

if (!$result === false)://-----------Page-Navigation---------------

  if (!$result->AtFirstPage() || !$result->AtLastPage()) :

	$output.='<table width="100%" border=0>
		<tr><td width="50%" >';
	    if (!$result->atFirstPage()) {
		$output.='<a href="'.$PHP_SELF.'?'.$QUERY_STRING.'&current_page='.($result->AbsolutePage() - 1).'" class="newslink"><- zur&uuml;ck</a>';
		}
		$output.='</td>
		<td width="50%" align="right" >';
		if (!$result->atLastPage()) {
		$output.='<a href="'.$PHP_SELF.'?'.$QUERY_STRING.'&current_page='.($result->AbsolutePage() + 1).'" class="newslink">weiter -></a>';
		}
        $output.='&nbsp;
		</td></tr>
	</table>';
ELSE:
	$output.='<SPAN class="small">&nbsp;</SPAN>';
ENDIF;

ENDIF;// Page - Navigation

  }
?>

