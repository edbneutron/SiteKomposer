<?php
/*------------------------------------------------------------------------------*/
/* SITE-Komposer  PHP-Content Management System                                  */
/*------------------------------------------------------------------------------*/

/***************************************
** Title........: Site-Komposer-Page-Output-Include
** Filename.....: skpageout.php
** Author.......: Edgar Bueltemeyer
***************************************/

      //IF editable
          $tpl->editable=$editable;
	  
	  if($GLOBALS['debug']>0)$t_timer1=timer($time_start);
	  DEBUG_out(1,"bench","time:".$t_timer1." start template parsing.....");

      //parse menu
      $tpl->sk_menu_parse('complete');
	  if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
	  DEBUG_out(2,"bench2","menu parse completed:".($t_timer2-$t_timer1)."ms");
	  $t_timer1=$t_timer2;
	  
	  //parse Variables
      $tpl->parse('complete');
	  if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
	  DEBUG_out(2,"bench2","Variables parse completed:".($t_timer2-$t_timer1)."ms");
	  $t_timer1=$t_timer2;  
	  
      //parse content
      $tpl->sk_parse('complete');
      if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
	  DEBUG_out(2,"bench2","sk_parse (Content) completed:".($t_timer2-$t_timer1)."ms");
	  $t_timer1=$t_timer2;

	  // get parsed document
          $data = $tpl->getParsedDoc('complete');
          // write the file to the cache dir
          
          if ($cacheable==1) $tpl->write_to_cache($data);
          // parse News-content
          $tpl->sk_news_parse('complete');

          // output the page
          $tpl->print_file('complete');
          if($GLOBALS['debug']>0)$t_timer2=timer($time_start);
	  DEBUG_out(1,"bench2","Template parsing completed:".($t_timer2-$t_timer1)."ms");
	  DEBUG_out(1,"bench2","time: ".timer($time_start)." <b>FINISHED!</b> ...page created");
          // show debug Window
      DEBUG_window();
      $skdb->Close();    
if($GLOBALS['post_redirect']==1 && $GLOBALS['debug']==0) header("Location: ".SITE_URL."index.php?mid=".$mid."&edit=1");
else gzdocout();

?>