<?php

/***************************************
** Title........: SK-Formhandler template
** Filename.....: sk_formhandler.php
** Author.......: Edgar Bueltemeyer
** Version......: 0.5
** Notes........:
** Last changed.: 11.Mar.2005
** Last change..: Image resize function
***************************************/
include(CLASS_PATH."sdimageuploading/class.SDImageUploading.php");
if($GLOBALS['imlib']==1){
	include(CLASS_PATH."image2thumbnail/class.img2thumb.inc");
       DEBUG_out(3,"debug3","<b>FORM-HANDLER:IMAGE-LIB GD</b>");

    }else{
    DEBUG_out(3,"debug3","<b>FORM-HANDLER:IMAGE-LIB NETPBM</b>");
    include(CLASS_PATH."mimage/mImage_class.php");
}


DEBUG_out(3,"debug3","<b>FORM-HANDLER:</b><br>".print_array($_POST).print_array($_FILES));

$contentpath=ROOT_PATH.$current_site->attributes['dirname']."content/";
IF(isset($action)){

// Build new Object
$edit_object = new skobject(NULL,0);
//Object Tag-Attributes
    if(isset($_POST['attributes_vars'])){
	    while (list ($key, $val) = each ($_POST['attributes_vars'])){
	            $edit_object->attributes_vars[$key] = $val;
	        }
    }


if ($type=="text_image")
     {$filepath=$contentpath."image/";}
elseif($edit_object->attributes_vars[clsid]=="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" || $edit_object->attributes_vars[clsid]=="clsid:166B1BCA-3F9C-11CF-8075-444553540000")
     {$filepath=$contentpath."flash/";}
elseif($edit_object->attributes_vars[clsid]>"") {$filepath=$contentpath."video/";}
else {$filepath=$contentpath.$type."/";}

if($_POST[income_file]>"") {

   $income_ext=strrchr(strtolower($_POST[income_file]),".");
   switch($income_ext){
       case '.jpg':
            $income_type = 'image/jpeg';
       break; 
       case '.gif':
           $income_type = 'image/gif';
       break;  
       case '.png':
           $income_type = 'image/png';
       break;
            }
   $_FILES[file]['name']=$_POST[income_file];
   $_FILES[file]['tmp_name']=$_POST[income_path].$_POST[income_file];
   $_FILES[file][error]=0;
   $_FILES[file]['type']=$income_type;
   $HTTP_POST_FILES[file]['name']=$_POST[income_file];
   $HTTP_POST_FILES[file]['tmp_name']=$_POST[income_path].$_POST[income_file];
   $HTTP_POST_FILES[file][error]=0;
   $HTTP_POST_FILES[file]['type']=$income_type;

   DEBUG_out(4,"debug4",print_array($_FILES).print_array($HTTP_POST_FILES));
}
DEBUG_out(3,"debug3","<b>filetype:</b>".$type."<b>filepath:</b>".$filepath." ->".$_FILES[file][name]."<br>");
$newfilename=$oldfilename;




        
            
   /*****************************/
   /* upload file or Image      */
   if ($_FILES[file][name] > '') 
   if($_FILES[file][error]==0){

   switch($type) {

   case ("image"):
   case ("text_image"):
        DEBUG_out(3,"debug3","<b>IMAGE-Hanlder</b>");
   //if ($type=="image" || $type=="text_image"){
              IF(!isset($attributes_vars[thumbnail])) $attributes_vars[thumbnail]=0;
              IF ($oldfilename>'' AND file_exists($filepath.$oldfilename)) unlink($filepath.$oldfilename);
              if ($oldfilename>'' AND file_exists($filepath."thumbnails/".$oldfilename)) unlink($filepath."thumbnails/".$oldfilename);
           $iu = new SDImageUploading();
           $uploaded=$iu->doUpload($filepath,$HTTP_POST_FILES[file],$HTTP_POST_FILES[file][name]);
           if(!$uploaded){
                 $messages.=$iu->error;echo $iu->error;
                 $action="skip";
           }else{
                $messages.="Image uploaded:";
                $newfilename=$iu->uploaded_file;
                $messages.=$newfilename;
                //change permissions
                chmod($filepath.$newfilename,0666);
                //if($iu->uimage_extension==".gif") $edit_object->attributes_vars[thumbnail]=0;// no gif-Thumbnail-supported
                // DELETE File from income-dir
                //if($_POST[income_file]>"") unlink($_FILES[file]['tmp_name']);
           }// !uploaded
   break;
   case ("sound")://Sound-File
        DEBUG_out(3,"debug3","<b>SOUND-Handler</b>");
               $newfilename = skfile($HTTP_POST_FILES[file][tmp_name],$HTTP_POST_FILES[file][name],$filepath,$oldfilename);
               include(INCLUDE_PATH."id3.php");
   break;

   default: // normal file
       DEBUG_out(3,"debug3","<b>DEFAULT-Handler</b>");
               $newfilename = skfile($HTTP_POST_FILES[file][tmp_name],$HTTP_POST_FILES[file][name],$filepath,$oldfilename);
   break;
       }// switch(type)

   // File uploaded. get size
   $edit_object->attributes_vars['filesize']=filesize($filepath.$newfilename);
   }else{ // error messages
       $action="skip";
   switch($_FILES[file][error]){
   
   case 1:
   case 2:
       $messages.="<font color=red>Upload ERROR!</font><br>File is too BIG!";
   break;
   case 3:
   case 4:
       $messages.="<font color=red>Upload ERROR!</font><br>File not uploaded correctly!";
   break;
   
   }
   
   
   } //if file_error==0
   
   //********************************************************
   // Make Image-Adjustments
   //********************************************************
   if(($type=="image" || $type=="text_image") && $action!="skip" && is_file($filepath.$newfilename)){
        
       // CREATE Thumbnail
                define(MIMAGE_TEST,"1");
                IF($attributes_vars[thumbnail]==1){
                    if(isset($_POST[thumbsize])) {$thumbw=$_POST[thumbsize];$thumbh=$_POST[thumbsize];}
                       $thumbfile = $filepath."thumbnails/".$newfilename;
                    define(TH_MAX_HEIGHT ,    $thumbh);
                    define(TH_MAX_WIDTH  ,    $thumbw);
                    if($GLOBALS['imlib']==1){
                       $thumbnail = new Img2Thumb($filepath.$newfilename,$thumbw,$thumbh,$thumbfile);
                    }
                    else{
                       $myImage = new mImage($filepath.$newfilename);   // location of image
                       $myImage->create_thumbnail($thumbfile);    // create new file "/home/mypath/th_someimage.gif"

                    }

                    $messages.="<br>Thumbnail created.";
                    }
         
      // Resize Image
                
                IF($attributes_vars[resize]==1){
                    if(isset($_POST[imgsize])) {$imgw=$_POST[imgsize];$imgh=$_POST[imgsize];}
            
                     $imgfile_ori = $filepath."ori_".$newfilename;
             $imgfile_new = $filepath.$newfilename;
             
                    define(IMG_MAX_HEIGHT,    $imgh);
                    define(IMG_MAX_WIDTH,    $imgw);
                    if($GLOBALS['imlib']){
               //rename old_image, but keep it
               rename($filepath.$newfilename,$filepath."ori_".$newfilename);
                       $thumbnail = new Img2Thumb($imgfile_ori,$imgw,$imgh,$imgfile_new);
               unlink($imgfile_ori); //delete old image
                    }
                    else{
                       $myImage = new mImage($imgfile_new);   // location of image
                       $myImage->resize($imgfile_new);    // create new file "/home/mypath/th_someimage.gif"

                    }

                    $messages.="<br>Image resized.";
                    }
            
            
            //  get Image-size and thumbnail-size
                $imgsize = GetImagesize($filepath.$newfilename);
                //print_array($imgsize);
                $edit_object->attributes_vars[width]=$imgsize[0];
                $edit_object->attributes_vars[height]=$imgsize[1];
                IF($attributes_vars[thumbnail]==1){
                  $thumbfile = $filepath."thumbnails/".$newfilename;
                  $timgsize = GetImagesize($thumbfile);
                  $edit_object->attributes_vars[twidth]=$timgsize[0];
                  $edit_object->attributes_vars[theight]=$timgsize[1];
                  //change permissions
				  $chmod_cmd='chmod 0666 '.$filepath.'thumbnails/'.$newfilename;
				  $chmod_cmd2='chmod 0666 '.$filepath.$newfilename;
				  if($GLOBALS['wrap_exec']==1) {
				  		get_exec($chmod_cmd);
						get_exec($chmod_cmd2);			         
			        }else{
			            $junk = `$chmod_cmd`;
						$junk = `$chmod_cmd2`;			
			        }                 
                  }

   }// type image     
          
 
 IF ($action == "skip" && $type=="image"){$action = "skip";
 }ELSEIF ($object_id == 0){$action="insert";
 }ELSEIF ($action != "delete"){$action = "update";
 }

DEBUG_out(3,"debug3","<b>form-action:</b>".$action."<br>");


IF (!isset($sort_nr)) $sort_nr=1;



    //Object - Attributes
    IF (isset($object_id)) $edit_object->attributes['object_id']=$object_id;
    IF (isset($content_id)) $edit_object->attributes['content_id']=$content_id;
    IF (isset($type)) $edit_object->attributes['type']=$type;
    $edit_object->attributes['file']=$newfilename;

    if(isset($EditorValue)) $edit_object->attributes['objtext']=$EditorValue;
    elseif (isset($objtext)) $edit_object->attributes['objtext']=$objtext;
    
    IF (isset($sort_nr)) $edit_object->attributes['sort_nr']=$sort_nr;
    
    IF (isset($edit_object->attributes_vars['title'])) $edit_object->attributes_vars['title']=str_replace(" ","&nbsp;",$edit_object->attributes_vars['title']);
    
    $edit_object->vars2attributes();

    DEBUG_out(3,"debug3","<b>object-attributes:</b>".print_array($edit_object->attributes,1)."<br>");
//derive action
IF (isset($action)){
//$messages.="<br>action:$action";
$GLOBALS['delcache']=1;
 
 IF ($action == "update"){
   // Update:
   $edit_object->update();
   $messages.="<br>->object updated";
   $post_redirect=1;
 }ELSEIF ($action == "insert"){
    // Insert:
    if($edit_object->insert()==true) {$messages.="<br>->object inserted";}
    else {$messages.="<br>-><b>Fehler!</b>";}
    $post_redirect=1;
 }ELSEIF ($action == "delete"){
   // Delete:
   IF ($oldfilename>'' AND file_exists($filepath.$oldfilename)) unlink($filepath.$oldfilename);
   if ($oldfilename>'' AND file_exists($filepath."thumbnails/".$oldfilename)) unlink($filepath."thumbnails/".$oldfilename);
   $edit_object->delete();
   $messages.="<br>->object deleted";
   $post_redirect=1;
 }
}




}

?>