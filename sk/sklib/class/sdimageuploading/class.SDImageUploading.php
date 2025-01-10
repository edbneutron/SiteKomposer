<?
/*----------------------------------------------------------------------------------
| Class SDImageUploading image uploading                                            |
| Example:                                                                          |
| Copyright © 2001 SexDev.com! Inc. All rights reserved.
| < ?                                                                               |
|   $iu = new SDImageUploading();                                                   |
|   // 'newname' - optional field, leave it                                         |
|   //empty if you want to use original file name                                   |
|   $iu->doUpload('/usr/html/images/',$HTTP_POST_FILES["img"],'newname');           |
|   //return uploaded image name (include server path) or false                     |
|                                                                                   |
|   //Also you can print error code (if false result returned)                      |
|   echo $iu->error                                                                 |
| ? >                                                                               |
-----------------------------------------------------------------------------------*/
class SDImageUploading {
    var $disk_path;         //disk path where new image will be uploaded
    var $new_uimage_name;   //new image name
    var $uimage_extension;  //new image extension
    var $uimage;            //uploaded image
    var $error;             //error code
    var $uploaded_file;     //succesfully uploaded file name
    
    //check image type function
    function addcheckImgType(){ 
        if((strcmp($this->uimage['type'],'image/jpeg')==0)||(strcmp($this->uimage['type'],'image/gif')==0)|| (strcmp($this->uimage['type'],'image/pjpeg')==0)||(strcmp($this->uimage['type'],'image/jpg')==0)||(strcmp($this->uimage['type'],'image/x-png')==0)||(strcmp($this->uimage['type'],'image/png')==0)){
            switch($this->uimage['type']){ 
                case 'image/jpg': 
                    $this->uimage_extension = '.jpg'; 
                break; 
                case 'image/jpeg': 
                    $this->uimage_extension = '.jpg'; 
                break; 
                case 'image/pjpeg': 
                    $this->uimage_extension = '.jpg'; 
                break; 
                case 'image/gif': 
                    $this->uimage_extension = '.gif'; 
                break;  
                case 'image/x-png':
                    $this->uimage_extension = '.png';
                break;
                case 'image/png':
                    $this->uimage_extension = '.png';
                break;
            }
            return true; 
        }else{ 
            $this->error .= '<div style="font-size: 10px; color: #cc0000;">Falsches Bildformat </div>'.$this->uimage['type'];
            return false;
        } 
    } 
    
    //start upload and check image type
    function doUpload($new_disk_path,$new_uimage,$new_uimage_name=''){
        $this->disk_path = $new_disk_path;
        $this->uimage = $new_uimage;
        $this->uploaded_file = '';
        
        if($new_uimage_name != ''){
            $this->new_uimage_name = noaccent(strtolower($new_uimage_name));
        }else{
            $this->new_uimage_name = noaccent(strtolower($this->uimage_name));
        }
        /*if($new_uimage_name != ''){
            $this->new_uimage_name = $new_uimage_name;
        }else{
            $this->new_uimage_name = $this->uimage_name;
        }*/
        $extension=strrchr ($this->new_uimage_name, ".");
        $basename=str_replace(" ","_",substr ($this->new_uimage_name, 0, - strlen($extension)));
        $tempname=$basename;
        $i=1;
        WHILE (file_exists($this->disk_path.$tempname.$extension)) {
          $tempname=$basename."$i";
          $i++;
        }
        $this->new_uimage_name=$tempname;
        $this->addcheckImgType();
        
        if($this->uimage_extension){
            $uimageFinal = $this->disk_path.$this->new_uimage_name.$this->uimage_extension;
            DEBUG_out(3,"debug3","<b>image uploading:</b>".$this->uimage['tmp_name'].">>".$uimageFinal);
            if(copy($this->uimage['tmp_name'], $uimageFinal)){
                $this->uploaded_file = $this->new_uimage_name.$this->uimage_extension;
                return $uimageFinal;
            }else{
                $this->error .= '<br>Cannot copy image to '.$uimageFinal.'. Check chmod and server path '.$this->disk_path;
                return false;
            }
        }else{
            $this->error .= '<br>Can not get image extension '.$this->uimage_extension;
            return false;
        }
    }
}
?>