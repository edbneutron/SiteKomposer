<?php
/***************************************
** Title........: SK Edit-window include_file
** Filename.....: skedit.inc.php
** Author.......: Edgar Bueltemeyer
** Notes........:
** Last changed.: 15/04/2005
***************************************/

/****************************************
** decide if this will be a Form-Window
** or normal Page
** and check if username is set in session
******************************************/

if ($form_window==1 AND isset($sess_username)){ // make form-window
	DEBUG_out(1,"debug1","Creating Form-Window..");
  $current_user=new skuser;
  define("CONTENT_PATH",SITE_PATH."content/");  // Path to Site-Content
  $edit_object = new skobject(NULL,0);

    if ($object_id!=0){      
      $edit_object->attributes['object_id']=$object_id;
      $edit_object->get();
      $parent_node=$edit_object->attributes['parent_node'];
      $edit_object->attributes2vars();
    }elseif (isset($identifier)){
    
      $edit_ca = new skcontent_area($identifier,$parent_node);
      $edit_object->attributes['content_id']=$edit_ca->get_id();
      $edit_object->attributes['sort_nr']=$sort_nr;
    }
	// get max upload file size
    $IntMaxUploadSize= ini_get('upload_max_filesize');

// Choose the include file
$this_form="";
//DEBUG_out(1,"debug1",print_array($GLOBALS));

//get predefined form-template
  if (isset($form_template))
  {
      DEBUG_out(1,"debug1","Form-Template: ".$form_template);
      if(is_file($forms_tpl_path.$form_template)) $this_form=$forms_tpl_path.$form_template;
       else $this_form="FATAL ERROR!! form-template not found!!!";
  }
//get form-template for NEW module-block
   elseif (isset($_REQUEST['dirname']) && $_REQUEST['type']=='moduleblock')
  {
      DEBUG_out(1,"debug1","New Module-Block: ".$_REQUEST['dirname']);
      $type=$_REQUEST['type'];
      $dirname=$_REQUEST['dirname'];
      $this_form=SK_PATH.'modules/'.$_REQUEST['dirname'].'/blocks.php';
  }
  
//get form-template for module-block OBJECT
   elseif (isset($edit_object->attributes['type']) && $edit_object->attributes['type']=='moduleblock')
  {
      DEBUG_out(1,"debug1","Edit Module-Block: ".$edit_object->attributes_vars['dirname']);
      $type=$edit_object->attributes['type'];
      $dirname=$edit_object->attributes_vars['dirname'];
      $this_form=SK_PATH.'modules/'.$edit_object->attributes_vars['dirname'].'/blocks.php';
  }
  
//get form-template for cms-object
  elseif (isset($edit_object->attributes['type']) && $edit_object->attributes['type']!='moduleblock')
  {
      $type=$edit_object->attributes['type'];
      $this_form=$forms_tpl_path.$type.".php";
  }
   
  else{
   $this_form=$forms_tpl_path."new.php";
   $type="New Object";
   DEBUG_out(1,"debug1","New Object.");
   }

//** Include Form-Template *************//
require_once($forms_tpl_path.'form_top.php');

if(!strstr($this_form,"ERROR"))include($this_form);else echo $this_form;

require_once($forms_tpl_path.'form_bottom.php');

//***************************************//

}else{      // display page and check security

    if (isset($parent_node)) $mid=$parent_node;
    $styles.="<link rel=\"stylesheet\" href=\"".SKRES_URL.'skinz/'.$theme."/css/admin.css\">";
    //---------------------------------------------------------
    // if no user is defined in session redirect page
    //---------------------------------------------------------
    if(!isset($_SESSION['sess_username'])){ 

     //? ><HTML><BODY>< ? echo $ not_logged_in; ? ></BODY></HTML>< ?
     echo $not_logged_in;
      header("Location:index.php?mid=$mid");
    //---------------------------------------------------------
    // if user is an admin enable editing
    // and
    // check user's permission to edit page
    //---------------------------------------------------------
    }else{
      $current_user=new skuser;
      $group = new skgroup;
      if ($group->getgroups($current_user->attributes["uid"],1,0,"nav_tree",$mid,"A") || $current_site->attributes['user_id']==$current_user->attributes["uid"]){
          $editable=1;
        include(INCLUDE_PATH."skformhandler.inc.php");

        $edit_scripts = "<script  language=\"JavaScript\" type=\"text/javaScript\">window.name='skpage';</script>".
        "<script src=\"".SKRES_URL."js/edit_scripts.js\" language=\"JavaScript\" type=\"text/javaScript\"> </script>";
	DEBUG_out(1,"debug1","Editable Page");
      }else{
          $editable=0;
	  DEBUG_out(1,"debug1","Page not editable!");
      }
      
     //---------------------------------------------------------
    // display Admin-Mode Menu.
    //---------------------------------------------------------
    $adminmenu = new adminmenu();
    $edit_scripts.=$adminmenu->toolbar(8,40,12,1,$messages,"absolute",1);  
    }//if(!isset($sess_username)
    


} // if ($form_window==1 AND isset($sess_username))
?>