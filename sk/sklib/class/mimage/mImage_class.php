<?php
/***************************************************************
*        Class by Matthew Malenski  18Oct2002                  *
*        http://www.malenski.com                               *
*                                                              *
* NOTE: netpbm 1.1 must be installed, This class uses a subset *
* of netpbm library (giftopnm  jpegtopnm  pnmscale  ppmtojpeg) *
* which takes about 1.2 Mb of space                            *
*                                                              *
***************************************************************/

define(NETPBM_DIR , INCLUDE_PATH."netpbm/");

//define(TH_MAX_HEIGHT ,    "100");
//define(TH_MAX_WIDTH  ,    "100");

//define(IMG_MAX_HEIGHT,    "350");
//define(IMG_MAX_WIDTH, "350");

define(MIMAGE_TEST,     FALSE);

class mImage{

    var $filename;
    var $tmp_file;
    var $th_tmp_file;
    var $th_tmp_fileq;
    var $factor;
    var $type;
    var $new_file;
    var $thumbnail;
    var $height;
    var $width;

    function mImage($filename="-1"){
        /*********************************************************************/
        // Test to see if filename was given in object creation
        /*********************************************************************/
        if($filename == "-1"){
            $GLOBALS[messages].="ERROR: You must use a filename in new mImage()\n";
            //exit;
        }

        /*********************************************************************/
        // Test filename type only accept jpeg and gif
        /*********************************************************************/
        if( !(eregi("\.(jpg|jpeg|gif|png)$",$filename)) ){
            $GLOBALS[messages].="file[$filename] is an invalid type.\n";
            //exit;
        }

        /*********************************************************************/
        // Test existence
        /*********************************************************************/
        if(!(file_exists($filename))){
            $GLOBALS[messages].="file[$filename] does not exist.\n";
            //exit;
        }

        /*********************************************************************/
        // Get Size of Image
        /*********************************************************************/
        $sizes = getimagesize($filename);
        $this->width    = $sizes[0];
        $this->height   = $sizes[1];

        if(($this->width<=0) || ($this->height<=0)){
            $GLOBALS[messages].="ERROR: could not get size of imape[$filename]\n";
            //exit;
        }

        
        /*********************************************************************/
        // Random number is used to create temporary filenames
        /*********************************************************************/
        srand((double) microtime() * 1000000);
        $random = rand();

        $this->filename = $filename;
        $parts          = split("\.",$filename);
        $this->type     = $parts[(count($parts)-1)];
        
        /*********************************************************************/
        // Split Filename apart     (ex: /home/guest/images/me.gif)
        // set $fname == filename   (ex: me.gif)
        // set $dname == directory  (ex: /home/guest/images
        /*********************************************************************/
        $parts          = split("/",$filename);     
        for($i=1; $i<count($parts); $i++){
            ($i==(count($parts)-1)) ? $fname = $parts[$i] :$dname .= "/".$parts[$i];
        }
        
        $this->thumbnail    =  $dname . "/th_".$fname;
        $this->tmp_file     =  $dname . "/". $random . ".pnm";
        $this->th_tmp_file  =  $dname . "/th_". $random . ".pnm";
        $this->th_tmp_fileq =  $dname . "/thq_". $random . ".pnm";
        
        if(MIMAGE_TEST){
            echo "filename[".$this->filename."]\n";
            echo "directory = $dname\n";
            echo "filename  = $fname<br>\n";
            echo "thumbnail[".$this->thumbnail."]\n";
            echo "tmp_file[".$this->tmp_file."]\n";
            echo "th_tmp_file[".$this->th_tmp_file."]\n";
            echo "type[".$this->type."]\n";
            echo "IMG_MAX_HEIGHT:".IMG_MAX_HEIGHT."\n";
            echo "TH_MAX_HEIGHT:".TH_MAX_HEIGHT."\n";
            
        }
    }

    /*********************************************************************/
    // resize() - resize the file if the image is larger then the globals 
    // IMG_MAX_WIDTH and IMG_MAX_HEIGHT
    /*********************************************************************/
    function resize(){
        
        /*********************************************************************/
        // If image is smaller then max size there is nothing to do
        /*********************************************************************/
        if( ($this->width <= IMG_MAX_WIDTH) && ($this->height <= IMG_MAX_HEIGHT) ){
            return;
        }

        /*********************************************************************/
        // Determine the proper netbpm command to use given the $this->type of image 
        /*********************************************************************/
        switch ($this->type) {
        
        case "jpg":
        case "jpeg":
            $pnm_cmd = "jpegtopnm";
            $pnm_cmd2 = "ppmtojpeg";
        break;
        case "gif":
            $pnm_cmd = "giftopnm";
            $pnm_cmd2 = "ppmtogif";
        break;
        case "png":
            $pnm_cmd = "pngtopnm";
            $pnm_cmd2 = "pnmtopng";
        break;
        }
        //(eregi("(jpeg|jpg)",$this->type))? $pnm_cmd = "jpegtopnm" :   $pnm_cmd = "giftopnm";
        $tmp_cmd = NETPBM_DIR . $pnm_cmd . " " . $this->filename . " > ". $this->tmp_file;
    
        /*********************************************************************/
        // If we resize using the largest dimension is the NEW smaller dimension
        // still too big?
        // no  - then resize using the largest dimension
        // yes - then resize using the smaller dimension 
        /*********************************************************************/
        if($this->width > $this->height){
            $new_height = (IMG_MAX_WIDTH/$this->width) * $this->height;
            if($new_height > IMG_MAX_HEIGHT){
                $resize_cmd  = NETPBM_DIR. "pnmscale -height=" . IMG_MAX_HEIGHT ." ".$this->tmp_file."  > ".$this->th_tmp_file;
            }else{
                $resize_cmd  = NETPBM_DIR. "pnmscale -width=". IMG_MAX_WIDTH ." ".$this->tmp_file."  >  ".$this->th_tmp_file;
            }
        }else{
            $new_width = (IMG_MAX_HEIGHT/$this->height) * $this->width;
            if($new_width > IMG_MAX_WIDTH){
                $resize_cmd  = NETPBM_DIR. "pnmscale -width=". IMG_MAX_WIDTH ." ".$this->tmp_file."  >  ".$this->th_tmp_file;           
            }else{
                $resize_cmd  = NETPBM_DIR. "pnmscale -height=" . IMG_MAX_HEIGHT ." ".$this->tmp_file."  > ".$this->th_tmp_file;
            }
        }

	
	if($GLOBALS[wrap_exec]==1) {
            get_exec($tmp_cmd);
            get_exec($resize_cmd);
        }else{
            $junk = `$tmp_cmd`;
            $junk = `$resize_cmd`;

        }
	
	if ($this->type == "gif" || $this->type == "png"){
            $quant_cmd = NETPBM_DIR."ppmquant 256 ".$this->th_tmp_file." > ".$this->th_tmp_fileq;
	    //echo $quant_cmd."<br>";
            if($GLOBALS[wrap_exec]==1) {
                get_exec($quant_cmd);
            }else{
                $junk = `$quant_cmd`;
            }
            unlink($this->th_tmp_file);
            $this->th_tmp_file=$this->th_tmp_fileq;
        }
	
	
        $last_cmd = NETPBM_DIR. $pnm_cmd2." ". $this->th_tmp_file ." > ".$this->filename;
	//echo $last_cmd;
	
	if($GLOBALS[wrap_exec]==1) {
	    get_exec($last_cmd);
        }else{
	    $junk = `$last_cmd`;

        }
        
        /*********************************************************************/
        // Re-calculate width and height properties
        /*********************************************************************/
        $sizes = getimagesize($this->filename);
        $this->width    = $sizes[0];
        $this->height   = $sizes[1];
        
        
        if(file_exists($this->tmp_file)){
            unlink($this->tmp_file);
        }

        if(file_exists($this->th_tmp_file)){
            unlink($this->th_tmp_file);
        }       
        
    }
    /*********************************************************************/
    // create_thumbnail($thumbnail_filename)
    // $thumbnail_filename is optional 
    // If its passed in it should be the name of the thumbnail that will be created
    // If its not used the default thumbail name is th_($this->filename)
    /*********************************************************************/
    function create_thumbnail($thumbnail="-1"){
        if($thumbnail != "-1"){
            $this->thumbnail = $thumbnail;
        }       
    
        /*****************************************************************/
        // if image is already smaller then the a thumbnail just copy it
        /*****************************************************************/
        if( ($this->width <= TH_MAX_WIDTH) && ($this->height <= TH_MAX_HEIGHT) ){
            $resize_cmd = "/bin/cp ".$this->filename." ".$this->thumbnail;
            get_exec($resize_cmd);
            return;
        }
        
        /*********************************************************************/
        // Determine the proper netbpm command to use given the $this->type of image 
        /*********************************************************************/
        switch ($this->type) {
        
        case "jpg":
        case "jpeg":
            $pnm_cmd = "jpegtopnm";
            $pnm_cmd2 = "ppmtojpeg";
        break;
        case "gif":
            $pnm_cmd = "giftopnm";
            $pnm_cmd2 = "ppmtogif";
        break;
        case "png":
            $pnm_cmd = "pngtopnm";
            $pnm_cmd2 = "pnmtopng";
        break;
        }
        //(eregi("(jpeg|jpg)",$this->type))? $pnm_cmd = "jpegtopnm" :   $pnm_cmd = "giftopnm";  
        $tmp_cmd = NETPBM_DIR . $pnm_cmd . " " . $this->filename . " > ". $this->tmp_file;


        /*********************************************************************/
        // If we resize using the largest dimension is the NEW smaller dimension
        // still too big?
        // no  - then resize using the largest dimension
        // yes - then resize using the smaller dimension 
        /*********************************************************************/
        if($this->width > $this->height){
            $new_height = (TH_MAX_WIDTH/$this->width) * $this->height;
            if($new_height > TH_MAX_HEIGHT){
                $resize_cmd  = NETPBM_DIR. "pnmscale -height=" . TH_MAX_HEIGHT ." ".$this->tmp_file."  > ".$this->th_tmp_file;
            }else{
                $resize_cmd  = NETPBM_DIR. "pnmscale -width=". TH_MAX_WIDTH ." ".$this->tmp_file."  >   ".$this->th_tmp_file;
            }
        }else{
            $new_width = (TH_MAX_HEIGHT/$this->height) * $this->width;
            if($new_width > TH_MAX_WIDTH){
                $resize_cmd  = NETPBM_DIR. "pnmscale -width=". TH_MAX_WIDTH ." ".$this->tmp_file."  >   ".$this->th_tmp_file;           
            }else{
                $resize_cmd  = NETPBM_DIR. "pnmscale -height=" . TH_MAX_HEIGHT ." ".$this->tmp_file."  > ".$this->th_tmp_file;
            }
        }
        DEBUG_out(3,"debug3","<b>resize-cmd:</b>".$resize_cmd."<br>");
        
        if($GLOBALS[wrap_exec]==1) {
            get_exec($tmp_cmd);
            get_exec($resize_cmd);
        }else{
            $junk = `$tmp_cmd`;
            $junk = `$resize_cmd`;

        }


        if ($this->type == "gif" || $this->type == "png"){
            $quant_cmd = NETPBM_DIR."ppmquant 256 ".$this->th_tmp_file." > ".$this->th_tmp_fileq;
            if($GLOBALS[wrap_exec]==1) {
                get_exec($quant_cmd);
            }else{
                $junk = `$quant_cmd`;
            }
            unlink($this->th_tmp_file);
            $this->th_tmp_file=$this->th_tmp_fileq;
        }   
        $thumb_cmd = NETPBM_DIR.$pnm_cmd2." ". $this->th_tmp_file ." > ".$this->thumbnail;
        //echo $thumb_cmd;
        DEBUG_out(3,"debug3","<b>thumb_cmd:</b>".$thumb_cmd."<br>");
        if($GLOBALS[wrap_exec]==1) {
                get_exec($thumb_cmd);
            }else{
                $junk = `$thumb_cmd`;
            }

        if(file_exists($this->tmp_file)){
            unlink($this->tmp_file);
        }
        if(file_exists($this->th_tmp_file)){
            unlink($this->th_tmp_file);
        }
    }
}
?>