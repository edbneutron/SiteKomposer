
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
	$num_of_rows_per_page=2;
	$today = getdate(); 
	$nowdate = mktime(0,0,0,$today['mon'],$today['mday'],$today['year']);
	if (empty($curr_page)) $curr_page = 1;
	
	$sql_select = "select *, ndate AS sortdate, DATE_FORMAT(ndate, '%d-%m-%Y') as ndate
	 			   FROM sk_news  
					WHERE section_id=$section_id AND (UNIX_TIMESTAMP(duedate) > ".$nowdate.")
					ORDER by sortdate desc";
	DEBUG_out(1,"query","NEWS-SQL:".$sql_select);
	$result1 = $skdb->Execute($sql_select);
	
	if (!$result1->EOF):
	$result = $skdb->PageExecute($sql_select, $num_of_rows_per_page, $curr_page);
		if ($result == false):
	  	$output1.= "News-DB-select failed! ".$skdb->Errormsg()."<br>";
		ENDIF;
	 ENDIF;
$output1.= '<link rel="stylesheet" href="res/css/reiturlaub.css" type="text/css">';
	while (!$result->EOF AND !$result == false) {
$output1.='
      <!--<a href="'.$detail_link.'&news_id='.$result->fields["id"].$editon.'" class="newslink"><b>'.$result->fields["title"].'</b></a><br>-->
	  <SPAN class="newstitle"><em>'.$result->fields["title"].'</em></SPAN>&nbsp;&nbsp;&nbsp;<SPAN class="newsdate"><em>'.$result->fields["ndate"].'</em></SPAN>
	  <p><SPAN class="newslead">'.$result->fields["lead"].'</SPAN></p>
	  <p><SPAN class="newstext">'.nl2br($result->fields["newstext"]).'</SPAN></p>
	  <!--<a href="'.$detail_link.'&news_id='.$result->fields["id"].$editon.'" class="newslink"><strong>Details</strong></a><br>-->
	  &nbsp;<br>
      ';
 $result->MoveNext();
  }

if (!$result === false)://-----------Page-Navigation---------------

  if (!$result->AtFirstPage() || !$result->AtLastPage()) :

	$output1.='<table width="100%" border=0>
		<tr><td width="50%" >';
	    if (!$result->atFirstPage()) {
		$output1.='<a href="'.$PHP_SELF.'?'.$QUERY_STRING.'&current_page='.($result->AbsolutePage() - 1).'" class="newslink"><- zur&uuml;ck</a>';
		}
		$output1.='</td>
		<td width="50%" align="right" >';
		if (!$result->atLastPage()) {
		$output1.='<a href="'.$PHP_SELF.'?'.$QUERY_STRING.'&current_page='.($result->AbsolutePage() + 1).'" class="newslink">weiter -></a>';
		}
        $output1.='&nbsp;
		</td></tr>
	</table>';
ELSE:
	$output1.='<SPAN class="small">&nbsp;</SPAN>';
ENDIF;

ENDIF;// Page - Navigation

if (!$result == false && $result->RecordCount()>0){
	$output.="<script language=\"JavaScript\">
			var popup='".ereg_replace("'","\"",ereg_replace("(\r\n|\n|\r)", "<br>", $output1))."';
			openPopup('News','LastMinute',popup,'width=350,height=400,left=0,top=50,status=yes,scrollbars=yes','');
			</script>";
	}
