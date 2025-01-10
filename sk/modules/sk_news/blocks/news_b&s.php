<?
  global $current_page;
  global $PHP_SELF;
  global $QUERY_STRING;
  global $REQUEST_URI;
  global $edit;
  if ($edit==1) $editurl="&edit=1"; else $editurl="";
  $detail_link=$this->attributes_vars[detail_link];
  $section_id=$this->attributes_vars[section_id];
if (isset($news_id)){ // display detail of News-Message
global $newsimgrelpath;
/* ****************** SQL-Selects to fill data********************* */
    $sql_select = "select sk_news.*, DATE_FORMAT(ndate, '%d-%m-%Y') as ndate FROM sk_news
                   WHERE id=$news_id ORDER by ndate desc";
    $result = $skdb->Execute($sql_select);
    if ($result == false){ $GLOBALS[DEBUG_OUTPUT].= "News-DB-select failed! ".$sql_select."<br>".$skdb->Errormsg()."<br>";
    }else{
    $author= new skuser($result->fields[uid]);
    $editor= new skuser($result->fields[by_user]);
    $output.='

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="newsbox">
  <tr> 
    <td width="69%" align="left" class="newsboxheader"><b>'.$result->fields["title"].'</b></td>
    <td width="31%" align="right" class="newsboxcontent"><span class="newstext"><strong>'.$result->fields["ndate"].'</strong></span>&nbsp;<a href="'.$detail_link.$editurl.'" class="newslink"><strong>zur&uuml;ck</strong></a></td>
  </tr>
  <tr> 
    <td colspan="2"><img src="res/img/shim.gif" width="148" height="1"></td>
  </tr>
       <tr> 
        <td colspan="2"  class="newsboxcontent">
        
        <table width="100%" border="0">
        <tr> 
          <td colspan="2" align="left" valign="top" class="newscontent"><p class="newsleadtext">'.nl2br($result->fields["lead"]).'</p>';
          if($result->fields["image"]>"" || $result->fields["file1"]>"" || $result->fields["file2"]>""){
        $output.='
            <table border="0" align="right" cellpadding="2" cellspacing="0" class="newsimage">
              <tr> 
                <td  class="newsboxcontent">'; if ($result->fields["image"]>""){ 
                  $output.='<img src="'.$newsimgrelpath.$result->fields["image"].'" border="0" alt="">';} 
                  $output.=' </td>
              </tr>
              <tr> 
                <td  class="newsboxcontent">'; if ($result->fields["file1"]>"" 
                  || $result->fields["file2"]>""){ $output.=' 
                  <p>Dateien: <a href="'.CONTENT_REL_PATH.'news/files/'.$result->fields["file1"].'">'.$result->fields["file1"].'</a><br>
                    <a href="'.CONTENT_REL_PATH.'news/files/'.$result->fields["file2"].'">'.$result->fields["file2"].'</a>'; 
                    } else{ $output.='&nbsp;';} $output.='</td>
              </tr>
            </table>';
            }
        $output.='
            <span class="newstext">'.nl2br($result->fields["newstext"]).'</span> 
          </td>
        </tr>
      </table>
        </td>
       </tr>
   <tr> 
      <td colspan="2"><img src="res/img/shim.gif" width="148" height="1"></td>
   </tr>
   <tr> 
      <td class="newsboxcontent">geschrieben von <a href="#userview?uid='.$author->attributes[uid].'">'.$author->attributes[uname].'</a><br>
        letzte �nderung am '.date("d.m.y",$result->UnixTimeStamp($result->fields[last_mod])).' von '.$editor->attributes[uname].'</td>
      <td align="right"  class="newsboxcontent"><a href="'.$detail_link.$editurl.'" class="newslink"><strong>zur�ck</strong></a></td>
   </tr>
 </table>

    

    ';

    }//$result

      

}else{ // Display News-List



    /* ****************** SQL-Selects to fill data********************* */
    $curr_page = $current_page;
    $num_of_rows_per_page=2;
    if (empty($curr_page)) $curr_page = 1;
    $section_crit="";
    if ($section_id!="%") $section_crit="AND section_id=$section_id";
    $sql_select = "SELECT sk_news.id, title, lead,uid, ndate AS sortdate, DATE_FORMAT(ndate, '%d-%m-%Y') as ndate, site_id ".
                  "FROM sk_news INNER JOIN sk_newssections ON sk_news.section_id = sk_newssections.id ".
                  "WHERE site_id=".$GLOBALS[current_site_id]."  ".$section_crit." order by sortdate desc";
    $result1 = $skdb->Execute($sql_select);
    if (!$result1->EOF):
    $result = $skdb->PageExecute($sql_select, $num_of_rows_per_page, $curr_page);
        if ($result == false) $GLOBALS[DEBUG_OUTPUT].= $sql_select."<br>News-DB-select failed! ".$skdb->Errormsg()."<br>";
     ENDIF;
    while (!$result->EOF AND !$result == false) {
    $author= new skuser($result->fields[uid]);

$output.='
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="newsbox">
              <tr> 
                <td align="left" class="newsboxheader"><a href="'.$detail_link.'&news_id='.$result->fields["id"].$editurl.'" class="newstitle"><b>'.$result->fields["title"].'</b></a></td>
                <td width="163" align="right" class="newsboxheader">'.$result->fields["ndate"].'</td>
              </tr>
              <tr> 
                <td colspan="2"><img src="res/img/shim.gif" width="148" height="1"></td>
              </tr>
              <tr> 
                <td colspan="2" class="newsboxcontent"> <p class="newsleadtext">'.nl2br($result->fields["lead"]).'</p></td>
              </tr>
              <tr> 
                <td colspan="2"><img src="res/img/shim.gif" width="148" height="1"></td>
              </tr>
               <tr> 
                <td class="newsboxcontent"><a href="#userview?uid='.$author->attributes[uid].'">'.$author->attributes[uname].'</a></td>
                <td align="right" class="newsboxcontent"><a href="'.$detail_link.'&news_id='.$result->fields["id"].$editurl.'" class="newslink"><strong>Details</strong></a></td>
              </tr>
            </table>';
unset($author);
 $result->MoveNext();

  }
if (!$result == false){//-----------Page-Navigation---------------
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
}// Page - Navigation

} //if(isset($news_id