<?
/***************************************
** Title........: SK-VarStore class
** Filename.....: class.skvarstore.inc.php
** Author.......: Edgar Bueltemeyer
** Version......: 0.1
** Notes........:
** Last changed.: 23/Feb/2005
** Last change..:
**
** Stores variables in blob-fields
************************************** */


class skvarstore {
        var $debug = 0;
        var $name="sk_var_store";
        var $skdbadm;
        var $attributes = array();
        var $table="sk_var_store";
        var $db_id="id";
        //var $skdbadm;
        
function skvarstore($varname=''){
	$this->skdbadm=&$GLOBALS['skdbadm'];
        if($varname<>''){
        $this->attributes[varname]=$varname;
        $this->get();
        
        }
        
      } //function sksite
      

      
function get(){
              global $skdb;
              $GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;
              $navqry = "SELECT * FROM ".$this->table."
                          WHERE varname= '".$this->attributes[varname]."'";

              $result = $skdb->Execute($navqry);

              $GLOBALS['ADODB_FETCH_MODE'] = "";
              if ($result == false || $result->EOF ){ 
                DEBUG_out(2,"error2",$this->name." query failed ".$navqry.$skdb->Errormsg());
		$this->insert();
                }
              else
                {
              $this->init($result);
              }
       } //End function get


function init($result){
             while (list($index, $value) = each($result->fields) ) {
             $this->attributes[$index]=$value;
             }
             DEBUG_out(2,"debug2",$this->name." ".print_array($this->attributes));

       } //End function init
        
       
       
// Insert one item into DB
function insert(){


                  DEBUG_out(1,"debug1",$this->name." insert!:".print_array($this->attributes)."<br>");
                  connectadm();

                  $insertqry = "INSERT into ".$this->table."
                  (varname,vardata)
                          VALUES ('".$this->attributes[varname]."', '".$this->attributes[vardata]."')";
		  DEBUG_out(2,"query",$this->name." insert!:".$objqry."<br>");
                  $result = $this->skdbadm->Execute($insertqry);
                  if ($result == false){
                   DEBUG_out(1,"error1","DB-insert failed! ".$this->skdbadm->Errormsg()."<br>".$insertqry."<br>");
                   closeadm();
                   return false;
                  }else{

                  $new_id = $this->skdbadm->Insert_ID();
                  $this->attributes['id']=$new_id;
                  closeadm();
                  return $new_id;
                  }


       } //End function insert
       
 function update(){

                  connectadm();
                  $objqry = "UPDATE ".$this->table."
                          SET varname='".$this->attributes[varname]."',
                              vardata='".$this->attributes[vardata]."'
                              WHERE ".$this->db_id."= ".$this->attributes['id'];
			      
		  DEBUG_out(2,"query",$this->name." update!:".$objqry."<br>");
		  
                  $result = $this->skdbadm->Execute($objqry);
                  if ($result == false) DEBUG_out(1,"error1","DB-update failed! ".$this->skdbadm->Errormsg()."<br>");

                  closeadm();


       } //End function update
       
//function delete()
       //deletes object from db
       //file deletion is in include skformhandler.php
       function delete(){
        global $messages;
        global $sess_uid;
        
                connectadm();
        
                  $objqry = "DELETE FROM ".$this->table."
                             WHERE ".$this->db_id."= ".$this->attributes["$this->db_id"];

                  $result = $this->skdbadm->Execute($objqry);
                  DEBUG_out(1,"error1",$this->name." <b>deleted!</b><br>");
                  if ($result == false) DEBUG_out(1,"error1","DB-delete failed! ".$this->skdbadm->Errormsg()."<br>");
              closeadm();
       } //End function delete
       
       
} // end class 
