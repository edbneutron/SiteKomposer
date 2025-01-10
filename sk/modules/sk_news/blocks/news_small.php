
 <?
  global $current_page;
  global $PHP_SELF;
  global $QUERY_STRING;
  global $REQUEST_URI;
  global $edit;
  
  if ($edit==1) $editon="&edit=1"; else $editon="";
  $detail_link=$this->attributes_vars[detail_link];
  $section_id=$this->attributes_vars[section_id];
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


