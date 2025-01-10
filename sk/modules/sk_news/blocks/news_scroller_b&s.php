<?
  global $current_page;
  global $PHP_SELF;
  global $QUERY_STRING;
  global $REQUEST_URI;
  global $edit;
  if ($edit==1) $editurl="&edit=1"; else $editurl="";
  $detail_link=$this->attributes_vars[detail_link];
  $section_id=$this->attributes_vars[section_id];
  
  
$output.='
<script language="JavaScript1.2">
<!-- Dimensions of the scroll box, height, length etc. --->
var scrollerwidth=300; 
var scrollerheight=15;
var scrollerbgcolor=\'#FFFFFF\';
var scrollerspeed=2000;

//configure the below variable to change the contents of the scroller
var messages=new Array();

';

	/* ****************** SQL-Selects to fill data********************* */
	$curr_page = $current_page;
	$num_of_rows_per_page=2;
	if (empty($curr_page)) $curr_page = 1;
	$section_crit="";
	if ($section_id!="%") $section_crit="AND section_id=$section_id";
	$sql_select = "SELECT sk_news.id, title, lead,uid, ndate AS sortdate, DATE_FORMAT(ndate, '%d-%m-%Y') as ndate, name, site_id ".
 				  "FROM sk_news INNER JOIN sk_newssections ON sk_news.section_id = sk_newssections.id ".
	              "WHERE site_id=".$GLOBALS[current_site_id]."  ".$section_crit." order by sortdate desc";
	$result1 = $skdb->Execute($sql_select);
	if (!$result1->EOF):
	$result = $skdb->PageExecute($sql_select, $num_of_rows_per_page, $curr_page);
		if ($result == false) $GLOBALS[DEBUG_OUTPUT].= $sql_select."<br>News-DB-select failed! ".$skdb->Errormsg()."<br>";
	 ENDIF;
	 $mess_nr=0;
	while (!$result->EOF AND !$result == false) {
	$author= new skuser($result->fields[uid]);
	$output.='messages['.$mess_nr.']="<div class=\'newstext\'>--- '.$result->fields["name"].' ---</div>";
	';
	$mess_nr++;
	$output.='messages['.$mess_nr.']="<a href=\''.$detail_link.'&news_id='.$result->fields["id"].$editurl.'\' class=\'newslink\'><i>'.$result->fields["ndate"].'</i>&nbsp;<b>'.$result->fields["title"].'</b></a>";
	';
	unset($author);
	$mess_nr++;
 $result->MoveNext();
  }

$output.='//messages['.$mess_nr.']="<div class=\'newstext\'>--- NEWS ---</div>";
if (messages.length>1)
	news_nr=2;
else
	news_nr=0;

function move1(whichlayer){
	tlayer=eval(whichlayer);
	if (tlayer.top>0&&tlayer.top<=1){
		tlayer.top=0;
		setTimeout("move1(tlayer)",scrollerspeed); 
		setTimeout("move2(document.newsmain.document.second)",scrollerspeed);
		return;
	}
	if (tlayer.top>=tlayer.document.height*-1){
		tlayer.top-=1;
		setTimeout("move1(tlayer)",65);
	} else{
		tlayer.top=scrollerheight;
		tlayer.document.write(messages[news_nr]);
		tlayer.document.close();
		if (news_nr==messages.length-1)
			news_nr=0;
		else
			news_nr++;
	}
}

function move2(whichlayer){
	tlayer2=eval(whichlayer);
	if (tlayer2.top>0&&tlayer2.top<=1){
		tlayer2.top=0;
		setTimeout("move2(tlayer2)",scrollerspeed);
		setTimeout("move1(document.newsmain.document.first)",scrollerspeed);
		return;
	}
	if (tlayer2.top>=tlayer2.document.height*-1){
		tlayer2.top-=1;
		setTimeout("move2(tlayer2)",65);
	}  else {
		tlayer2.top=scrollerheight;
		tlayer2.document.write(messages[news_nr]);
		tlayer2.document.close();
		if (news_nr==messages.length-1)
			news_nr=0;
		else
			news_nr++;
	}
}

function move3(whichdiv) {
	tdiv=eval(whichdiv);
	if (tdiv.style.pixelTop>0&&tdiv.style.pixelTop<=1){
		tdiv.style.pixelTop=0;
		setTimeout("move3(tdiv)",scrollerspeed);
		setTimeout("move4(second2)",scrollerspeed);
		return;
	}
	if (tdiv.style.pixelTop>=tdiv.offsetHeight*-1) {
		tdiv.style.pixelTop-=1;
		setTimeout("move3(tdiv)",65);
	}  else {
		tdiv.style.pixelTop=scrollerheight;
		tdiv.innerHTML=messages[news_nr];
		if (news_nr==messages.length-1)
			news_nr=0;
		else
			news_nr++;
	}
}

function move4(whichdiv){
	tdiv2=eval(whichdiv);
	if (tdiv2.style.pixelTop>0&&tdiv2.style.pixelTop<=1){
		tdiv2.style.pixelTop=0;
		setTimeout("move4(tdiv2)",scrollerspeed);
		setTimeout("move3(first2)",scrollerspeed);
		return;
	}
	if (tdiv2.style.pixelTop>=tdiv2.offsetHeight*-1){
		tdiv2.style.pixelTop-=1;
		setTimeout("move4(second2)",65);
	}  else  {
		tdiv2.style.pixelTop=scrollerheight;
		tdiv2.innerHTML=messages[news_nr];
	if (news_nr==messages.length-1)
		news_nr=0;
	else
		news_nr++;
	}
}

function startscroll(){
	if (document.all){
		move3(first2);
		second2.style.top=scrollerheight;
	}  else if (document.layers){
		move1(document.newsmain.document.first);
		document.newsmain.document.second.top=scrollerheight+5;
		document.newsmain.document.second.visibility=\'show\';
	}
}

window.onload=startscroll

</script>

';

$output.='<div class=\'newstitle\'>Die letzten 10 Artikel:</div><br>
<ilayer id="newsmain" width=&{scrollerwidth}; height=&{scrollerheight}; bgColor=&{scrollerbgcolor};>
<layer id="first" left=0 top=1 width=&{scrollerwidth};>
<script language="JavaScript1.2">
if (document.layers)
	document.write(messages[0]);
</script>
</layer>
<layer id="second" left=0 top=0 width=&{scrollerwidth}; visibility=hide>
<script language="JavaScript1.2">
if (document.layers)
	document.write(messages[1]);
</script>
</layer>
</ilayer>

<script language="JavaScript1.2">
if (document.all){
	document.writeln(\'<span id="newsmain2" style="position:relative;width:\'+scrollerwidth+\';height:\'+scrollerheight+\';overflow:hiden;background-color:\'+scrollerbgcolor+\'">\');
	document.writeln(\'<div style="position:absolute;width:\'+scrollerwidth+\';height:\'+scrollerheight+\';clip:rect(0 \'+scrollerwidth+\' \'+scrollerheight+\' 0);left:0;top:0">\');
	document.writeln(\'<div id="first2" style="position:absolute;width:\'+scrollerwidth+\';left:0;top:1;">\');
	document.write(messages[0]);
	document.writeln(\'</div>\');
	document.writeln(\'<div id="second2" style="position:absolute;width:\'+scrollerwidth+\';left:0;top:0">\');
	document.write(messages[1]);
	document.writeln(\'</div>\');
	document.writeln(\'</div>\');
	document.writeln(\'</span>\');
}
</script>
';



