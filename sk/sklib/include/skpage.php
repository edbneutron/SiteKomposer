<?php
/*------------------------------------------------------------------------------*/
/* SITE-Komposer  PHP-Content Management System                                    */
/*------------------------------------------------------------------------------*/

/***************************************
** Title........: Site-Komposer-Page-Include
** Filename.....: skpage.php
** Author.......: Edgar Bueltemeyer
***************************************/

if(!isset($cacheable)) $cacheable=0;


// PAGE-QUERY
$pageqry = "SELECT * FROM sk_nav_tree WHERE id = '$mid'";
DEBUG_out(3,"debug3","Page query: ".$pageqry);
  $result = $skdb->Execute($pageqry);
  if ($result === false || $result->fields['id']==NULL) {
    DEBUG_out(4,"debug4","Page queryresult: ".var_export($result,true));
     //Page not found Error 404
     $pageqry = "SELECT * FROM sk_nav_tree WHERE linkname = '#404'";
     $result = $skdb->Execute($pageqry);
     $mid=$result->fields['id'];
   if (!$result || $result == false || $result == EOF){
       die("Allgemeiner Datenbankfehler!".$skdb->Errormsg());
       
       }
  }
  /* Check if Page belongs to Realm (Login) */
  $PageRequiresLogin=TRUE;
  
  $PageElement = new sknavtree($mid);
  $PageRealms = skgroup::getgroups(0,0,0,"nav_tree",$mid,"R");
  
  if($PageRealms) {
  	DEBUG_out(1,"debug1","Page requires Login! ");
  	$PageRequiresLogin=TRUE;
  	$UserId=$_SESSION['sess_uid'];
  	if($UserId>0)
  	$UserPageRealms = skgroup::getgroups($UserId,0,0,"nav_tree",$mid,"R");
  	
  	if($UserPageRealms || isset($_SESSION['sess_hash'])) {
  		$UserViewPageAllowed=TRUE;
  		DEBUG_out(1,"debug1","User is allowed to view page.");
  	}else{
  		$UserViewPageAllowed=FALSE;
  		DEBUG_out(1,"debug1","User is NOT allowed to view page! Redirect to login.");
  		
  		$RequestedPageId=$mid;
  		$pageqry = "SELECT * FROM sk_nav_tree WHERE linkname = '#Login' AND site_id='".$PageElement->attributes['site_id']."'";
     	$result = $skdb->Execute($pageqry);
     	$mid=$result->fields['id'];
     	$LoginPageId=$mid;
     	
   		if (!$result || $result == false || $result == EOF){
       		die("Fehler: Login Seite nicht gefunden!  Error: Login-Page not found!".$skdb->Errormsg());
       	exit;
        }
        DEBUG_out(1,"debug1","Login-Page-ID:".$mid);
  	}
  }
  
  $document_title = $result->fields["title"];
  $current_site_id = $result->fields["site_id"];
  $current_site = new sksite($current_site_id);
  define("SITE_PATH",ROOT_PATH.$current_site->attributes['dirname']);
  define("CONTENT_PATH",SITE_PATH.'content/');
  define("RES_PATH",SITE_PATH.'res/');
  define("SITE_URL",$current_site->attributes['site_url']);
  
  $styles="";$edit_scripts="";
  $Message="";
  
  $edit=0;$form_window=0;$editable=0;  
  if(isset($GLOBALS['edit']) && $GLOBALS['edit']==1) {$edit=1; $editable=1;}
  if(isset($_REQUEST['edit']) && $_REQUEST['edit']==1) {$edit=1; $editable=1;}
  if(isset($_REQUEST['form_window']) && $_REQUEST['form_window']==1) $form_window=1;
  
  /*
   * Statistic Module
   */
  if (STAT_MODULE==1 AND !$form_window==1 AND !$edit==1 AND !isset($action)){
  	require(MODULE_PATH."webanalyse/include/stats_main.inc");
  	$stats = new init(true, MODULE_PATH.'webanalyse/',$document_title,CONTENT_PATH); // 2 params. true/false for activate or not, and the path of webanalyse.
  }
  DEBUG_out(1,"bench","time:".timer($time_start)." Page queried+Stats");
  DEBUG_out(1,"debug1","SITE_URL:".SITE_URL." edit:".$edit);

  
//**********************************************
// Perform action (Mail,...)
//**********************************************
if(isset($action))
 switch($action){
      case "formmail":
           require_once(CLASS_PATH."mimemail/class.html.mime.mail.inc");
           require_once(INCLUDE_PATH."skformmail.php");
           exit;
      break;

      case "getfile":
           require_once(INCLUDE_PATH."getfile.php");
           exit;
      break;
      
      case "xmlsitemap":
      		require_once(CLASS_PATH."class.skmenu.inc.php");
      		$XmlSitemap = new skmenu();
			$XmlSitemap->getfromdb();
			echo $XmlSitemap->xml_sitemap();
			exit;
      break;
      
      case "resfile":
      	   $fpath=$_REQUEST['filename'];
		   $fext=  substr($fpath, strrpos($fpath, '.') + 1);
		   //generate headers
		   switch($fext) {
			case "css":header("Content-type: text/css");break;
			case "js":header("Content-type: text/javascript");break;
			case "pdf":header("Content-type: application/pdf");break;
			case "html":case "htm":header("Content-type: text/html");break;
			case "jpg":case "jpeg":case"jpe":header("Content-type: image/jpeg");break;
			case "png":header("Content-type: image/png");break;
			case "gif":header("Content-type: image/gif");break;
			default:
				header("Content-type: text/plain");
			break;   		   
		   }
           echo file_get_contents(SKRES_PATH.$_REQUEST['filename']);
           exit;
      break;

      default:
          if(strstr($action,"xmlobj")) {
           $object_id=substr($action,7,10);
           require_once(INCLUDE_PATH."skxmlobj.php");
           exit;
           }

      break;

}

//**********************************************
// Create Form-Window or  editable Page
//**********************************************

if ($GLOBALS[edit]==1 OR $_REQUEST[edit]==1 OR $_REQUEST[form_window]==1){
  session_start();
  DEBUG_out(3,"debug3","SESSION_VARS:".print_array($_SESSION));
  if (isset($_SESSION)) {
        reset($_SESSION);
        while ( list($var, $val) = each($_SESSION) ) {
            $$var=$val;
        }
    }
  //include Files Part 2
  require_once(INCLUDE_PATH."skincludes2.php");

  $cacheable=0;
  $edit_on="edit=1";
  $url_par="&amp;edit=1";
  $formtarget=basename($PHP_SELF);
  require_once(INCLUDE_PATH."skedit.inc.php");
  DEBUG_out(1,"bench","time:".timer($time_start)." Edit-Window created");  
  if(isset($_REQUEST['form_window']) && $_REQUEST['form_window']==1){ DEBUG_window();  exit;}
}



//**********************************************
// Not a form-window -- Display PAGE
//**********************************************
if (!$form_window==1 ):

  $parent = $result->fields["p"];
  $template = $result->fields["template"];
  $depth = $result->fields["depth"];
  if(isset($_REQUEST["page_tpl"])) $template = $_REQUEST["page_tpl"];
  
  //************Load template********************
  $tpl = new sktemplate; 
  $tpl->set_cache_dir(ROOT_PATH."cache");
            //$start = $tpl->utime(); // benchmarking
          $tpl->debug=$debug;
          $tpl->load_file('complete', ROOT_PATH.$current_site->attributes['dirname'].'/'.'res/tpl/page/'.$template);
          DEBUG_out(1,"bench","time: ".timer($time_start)." loaded template");
          if(!isset($news_id)) $news_id="";
          $important_vars = array(
                             "mid" => $mid,
                             "news_id" => $news_id);
          $tpl->use_vars($important_vars, "in_name");

          if(isset($GLOBALS['delcache']) && $GLOBALS['delcache']==1){
           $tpl->flush_from_cache();
           $messages.="..flushed cache..";
           DEBUG_out(1,"debug1","time: ".timer($time_start)." flushed cache");
          }
          if ($cacheable==1 && $tpl->valid_cache_file()) 
          {
          $cachedmessage=" <B>..from CACHE</B>\n<BR>";
          $tpl->read_from_cache2tpl("",'complete');
          //parse News-Content
          //$tpl->sk_news_parse('complete');
          $tpl->print_file('complete');
          // output the page
          DEBUG_out(1,"bench","time: ".timer($time_start)." <b>FINISHED! CACHED</b> page created"); // benchmarking
          // show debug Window
      DEBUG_window();
          
          gzdocout();
          exit;
          }else{
          //include Files Part 2
          require_once(INCLUDE_PATH."skincludes2.php");
          
		  /* Stylesheet Configuration */
          $styles.= $current_site->attributes['meta']."\n";
          /* Mediabox Styles */
          $styles.= "<link rel=\"stylesheet\" href=\"".SKRES_URL."/css/mediabox/mediaboxAdvBlack.css\" type=\"text/css\" media=\"screen\" />";
          
          /* Javascript Files */
          $scripts = "\n<script src=\"".SKRES_URL."js/standard.js\" type=\"text/javascript\"> </script>";
          
          /* Mootools Configuration */
          $scripts.= "\n<script src=\"".SKRES_URL."js/mootools/mootools-1.2.2-core-yc.js\" type=\"text/javascript\"> </script>" .
          			 "\n<script src=\"".SKRES_URL."js/mootools/mootools-1.2.2.2-more-yc.js\" type=\"text/javascript\"> </script>" .
          			 "\n<script src=\"".SKRES_URL."js/mediabox/mediaboxadv.js\" type=\"text/javascript\"> </script>";
          }
endif;

?>