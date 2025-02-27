<?php
/*
V1.40 19 September 2001 (c) 2000, 2001 John Lim (jlim@natsoft.com.my). All rights reserved.  
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence.

  Latest version is available at http://php.weblogs.com/
  
  Interbase data driver. Requires interbase client. Works on Windows and Unix.

  3 Jan 2001 -- suggestions by Hans-Peter Oeri <kampfcaspar75@oeri.ch>
  	changed transaction handling and added experimental blob stuff
  
  Docs to interbase at the website
   http://www.synectics.co.za/php3/tutorial/IB_PHP3_API.html
   
  To use gen_id(), see
   http://www.volny.cz/iprenosil/interbase/ip_ib_code.htm#_code_creategen
   
   $rs = $conn->Execute('select gen_id(adodb,1) from rdb$database');
   $id = $rs->fields[0];
   $conn->Execute("insert into table (id, col1,...) values ($id, $val1,...)");
*/

class ADODB_ibase extends ADODBConnection {
    var $databaseType = "ibase";
    var $replaceQuote = "\'"; // string to use to replace quotes
    var $fmtDate = "'Y-m-d'";
    var $fmtTimeStamp = "'Y-m-d, H:i:s'";
    var $concat_operator='||';
    var $_transactionID;
	var $metaTablesSQL = "select rdb\$relation_name from rdb\$relations where rdb\$relation_name not like 'RDB\$%'";
	var $metaColumnsSQL = "select a.rdb\$field_name,b.rdb\$field_type,b.rdb\$field_length from rdb\$relation_fields a join rdb\$fields b on a.rdb\$field_source=b.rdb\$field_name where rdb\$relation_name ='%s'";
	var $ibasetrans = IBASE_DEFAULT;
	var $hasGenID = true;
	
	// note the generator starts from 0, unlike others which start from 1
	var $_genIDSQL = "SELECT Gen_ID(%s,1) FROM RDB\$DATABASE";
	var $_genSeqSQL = "INSERT INTO RDB\$GENERATORS (RDB\$GENERATOR_NAME) VALUES (UPPER('%s'))";

    function ADODB_ibase() 
	{
        ibase_timefmt('%Y-%m-%d');
  	}

    function BeginTrans()
	{      
		//return false; // currently not working properly
		
            $this->autoCommit = false;
     		$this->_transactionID = $this->_connectionID;//ibase_trans($this->ibasetrans, $this->_connectionID);
	      	return $this->_transactionID;
	}
	
	function CommitTrans()
	{
		$ret = false;
		$this->autoCommit = true;
		if ($this->_transactionID) {
               		//print ' commit ';
			$ret = ibase_commit($this->_transactionID);
		}
		$this->_transactionID = false;
		return $ret;
	}
	
	function RollbackTrans()
	{
		$ret = false;
		$this->autoCommit = true;
		if ($this->_transactionID) 
	              $ret = ibase_rollback($this->_transactionID);
		$this->_transactionID = false;   
		
		return $ret;
	}

        function SelectDB($dbName) {
               return false;
        }

	function _handleerror()
	{
		$this->_errorMsg = ibase_errmsg($this->_connectionID);
	}

        function ErrorNo() {
		if (preg_match('/error code = ([\-0-9]*)/i', $this->_errorMsg,$arr)) return (integer) $arr[1];
		else return 0;
        }
	
        function ErrorMsg() {
                return $this->_errorMsg;
        }
	
        // returns true or false
        function _connect($argHostname, $argUsername, $argPassword, $argDatabasename)
        {  
		if ($this->charSet)
			$this->_connectionID = ibase_connect($argHostname,$argUsername,$argPassword,$this->charSet);
	        else        
			$this->_connectionID = ibase_connect($argHostname,$argUsername,$argPassword);
              	
		if ($this->_connectionID === false) {
			$this->_handleerror();
			return false;
		}
		
                return true;
        }
        // returns true or false
        function _pconnect($argHostname, $argUsername, $argPassword, $argDatabasename)
        {
			if ($this->charSet)
				$this->_connectionID = ibase_pconnect($argHostname,$argUsername,$argPassword,$this->charSet);
	        else        
				$this->_connectionID = ibase_pconnect($argHostname,$argUsername,$argPassword);
          	     
	       	if ($this->_connectionID === false) {
				$this->_handleerror();
				return false;
			}
          
            return true;
        }

        // returns query ID if successful, otherwise false
        function _query($sql,$inputarr)
        { 
		if (!$this->autoCommit && $this->_transactionID) {
			$ret = ibase_query($this->_transactionID,$sql); 
		} else {
			$ret = ibase_query($this->_connectionID,$sql);
                	if ($ret === true) ibase_commit($this->_connectionID);
		}
		$this->_handleerror();
                return $ret;
        }

        // returns true or false
        function _close()
        {       
                if ($this->autoCommit) ibase_commit($this->_connectionID);
		else ibase_rollback($this->_connectionID);
		
                return @ibase_close($this->_connectionID);
        }
	
        // returns array of ADODBFieldObjects for current table
        function &MetaColumns($table) 
	{
	
		if ($this->metaColumnsSQL) {
		
			$rs = $this->Execute(sprintf($this->metaColumnsSQL,strtoupper($table)));
			if ($rs === false) return false;

			$retarr = array();
			while (!$rs->EOF) { //print_r($rs->fields);
				$fld = new ADODBFieldObject();
				$fld->name = $rs->fields[0];
				$tt = $rs->fields[1];
				switch($tt)
				{
				case 7:
				case 8:
				case 9:$tt = 'INTEGER'; break;
				case 10:
				case 27:
				case 11:$tt = 'FLOAT'; break;
				default:
				case 40:
				case 14:$tt = 'CHAR'; break;
				case 35:$tt = 'DATE'; break;
				case 37:$tt = 'VARCHAR'; break;
				case 261:$tt = 'BLOB'; break;
				case 13:
				case 35:$tt = 'TIMESTAMP'; break;
				}
				$fld->type = $tt;
				$fld->max_length = $rs->fields[2];
				$retarr[strtoupper($fld->name)] = $fld;	
				
				$rs->MoveNext();
			}
			$rs->Close();
			return $retarr;	
		}
		return false;
	}
	
	// warning - this code is experimental and might not be available in the future
	function &BlobEncode( &$blob ) 
	{
		$blobid = ibase_blob_create( $this->_connectionID);
		ibase_blob_add( $blobid, $blob );
		$blobidstr = ibase_blob_close( $blobid );

		return( $blobidstr );
	}
	
	// warning - this code is experimental and might not be available in the future
	function &BlobDecode( &$blob ) 
	{
		$blobid = ibase_blob_open( $blob );
		$realblob = ibase_blob_get( $blobid,99999); // 2nd param is max size of blob -- Kevin Boillet <kevinboillet@yahoo.fr>
		ibase_blob_close( $blobid );

		return( $realblob );
	} 
	
	/*
		Insert a null into the blob field of the table first.
		Then use UpdateBlob to store the blob.
		
		Usage:
		 
		$conn->Execute('INSERT INTO blobtable (id, blobcol) VALUES (1, null)');
		$conn->UpdateBlob('blobtable','blobcol',$blob,'id=1');
	*/
	function UpdateBlob($table,$column,$val,$where,$blobtype='BLOB')
	{
		$blob_id = ibase_blob_create($this->_connectionID);
		ibase_blob_add($blob_id, $val);
		$blob_id_str = ibase_blob_close($blob_id);
		return ibase_query("UPDATE $table SET $column=(?) WHERE $where", $blob_id_str) != false;
	}
}

/*--------------------------------------------------------------------------------------
         Class Name: Recordset
--------------------------------------------------------------------------------------*/

class ADORecordset_ibase extends ADORecordSet 
{

    var $databaseType = "ibase";
	var $bind=false;
	
        function ADORecordset_ibase($id)
        {
                return $this->ADORecordSet($id);
        }

        /*        Returns: an object containing field information.
                Get column information in the Recordset object. fetchField() can be used in order to obtain information about
                fields in a certain query result. If the field offset isn't specified, the next field that wasn't yet retrieved by
                fetchField() is retrieved.        */

        function &FetchField($fieldOffset = -1)
        {
                 $fld = new ADODBFieldObject;
                 $ibf = ibase_field_info($this->_queryID,$fieldOffset);
                 $fld->name = $ibf['name'];
				 if (empty($fld->name)) $fld->name = $ibf['alias'];
                 $fld->type = $ibf['type'];
                 $fld->max_length = $ibf['length'];
                 if ($this->debug) print_r($fld);
                 return $fld;
        }

        function _initrs()
        {
                $this->_numOfRows = -1;
                $this->_numOfFields = @ibase_num_fields($this->_queryID);
        }

        function _seek($row)
        {
                return false;
        }

        function _fetch($ignore_fields=false) {

                $f = ibase_fetch_row($this->_queryID); 
                if ($f === false) return false;
                $this->fields = $f;
                return true;
        }

	/* Use associative array to get fields array */
	function Fields($colname)
	{
		if (!$this->bind) {
			$this->bind = array();
			for ($i=0; $i < $this->_numOfFields; $i++) {
				$o = $this->FetchField($i);
				$this->bind[strtoupper($o->name)] = $i;
			}
		}
		
		 return $this->fields[$this->bind[strtoupper($colname)]];
		
	}
	
	
        function _close() 
		{
                return @ibase_free_result($this->_queryID);
        }

        function MetaType($t,$len=-1)
        {
                switch (strtoupper($t)) {
		case 'CHAR':
			return 'C';
			
              
		case 'TEXT':
		case 'VARCHAR':
                case 'VARYING':
                        if ($len <= $this->blobSize) return 'C';
			return 'X';
		  case 'BLOB':
                        return 'B';
               
                 case 'TIMESTAMP':
                 case 'DATE': return 'D';
                
                //case 'T': return 'T';

                //case 'L': return 'L';
		case 'INT': 
		case 'SHORT':
		case 'INTEGER': return 'I';
                default: return 'N';
                }
        }
}
?>
