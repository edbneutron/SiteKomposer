<?php

/**
*    class Image2Thumbnail
*   Thumbnail creation with PHP4
*
*
*   @author     Andreas Martens <heyn@plautdietsch.de>
*    @version    0.9
*/

class Img2Thumb    {

/**
*   Constructor - requires following vars:
*    
*    @param string $filename            image path
*
*    These are additional vars:
*
*    @param int $newxsize            new maximum image width
*    @param int $newysize            new maximum image height
*    @param string $fileout            output image path
*
*/
    function Img2Thumb($filename, $newxsize=60,$newysize=60, $fileout='')    {
        global $HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_COOKIE_VARS;
    
        if (isset($HTTP_COOKIE_VARS)) $httpvars = $HTTP_COOKIE_VARS;
        else if (isset($HTTP_POST_VARS)) $httpvars =  $HTTP_POST_VARS;
           else if (isset($HTTP_GET_VARS)) $httpvars =  $HTTP_GET_VARS;
        
        $this -> NewImgCreate($filename,$newxsize,$newysize,$fileout);

    }
    
/**
*  
*    private function - do not call
*
*/
    function NewImgCreate($filename,$newxsize,$newysize,$fileout)    {
        $type = $this->GetImgType($filename);

        if ($type=="png")    {
             $orig_img = imagecreatefrompng($filename);
        }
        if ($type=="jpg")    {
             $orig_img = imagecreatefromjpeg($filename);
        }
        if ($type=="gif")    {
             $orig_img = imagecreatefromgif($filename);
        }
    
        $new_img =$this->NewImgResize($orig_img,$newxsize,$newysize,$filename);
        
        if (!empty($fileout))    {
             $this-> NewImgSave($new_img,$fileout,$type);
        }
        else    {
             $this->NewImgShow($new_img,$type);
        }

        ImageDestroy($new_img);
        ImageDestroy($orig_img);
    }
    
    /**
*  
*    private function - do not call
*
*/
    function NewImgResize($orig_img,$newxsize,$newysize,$filename)    {

        $orig_size = getimagesize($filename);

        if ($orig_size[0]<$orig_size[1]){
            $newxsize = $newysize * ($orig_size[0]/$orig_size[1])-1;
        }
        else{
            $newysize = $newxsize / ($orig_size[0]/$orig_size[1])-1;
        }
        
        $im_out = imagecreatetruecolor($newxsize,$newysize);
        ImageCopyResized($im_out, $orig_img, 0, 0, 0, 0, $newxsize, $newysize, $orig_size[0], $orig_size[1]);    
        return $im_out;
    }
    
    /**
*  
*    private function - do not call
*
*/
    function NewImgSave($new_img,$fileout,$type)    {
        if ($type=="png")    {
            if (substr($fileout,strlen($fileout)-4,4)!=".png")
                $fileout .= ".png";
             return imagepng($new_img,$fileout);
        }
        if ($type=="jpg")    {
            if (substr($fileout,strlen($fileout)-4,4)!=".jpg")
                $fileout .= ".jpg";
             return imagejpeg($new_img,$fileout);
        }
        if ($type=="gif")    {
            if (substr($fileout,strlen($fileout)-4,4)!=".gif")
                $fileout .= ".gif";
             return imagegif($new_img,$fileout);
        }
    }
    
    /**
*  
*    private function - do not call
*
*/
    function NewImgShow($new_img,$type)    {
        if ($type=="png")    {
            header ("Content-type: image/png");
             return imagepng($new_img);
        }
        if ($type=="jpg")    {
            header ("Content-type: image/jpeg");
             return imagejpeg($new_img);
        }
        if ($type=="gif")    {
            header ("Content-type: image/gif");
             return imagegif($new_img);
        }
    }
    
    /**
*  
*    private function - do not call
*
*/
    function GetImgType($filename)    {
        $size = getimagesize($filename);
        if($size[2]==2) return "jpg";
        elseif($size[2]==3) return "png";
        elseif($size[2]==1) return "gif";
        
    }
    
}

?>