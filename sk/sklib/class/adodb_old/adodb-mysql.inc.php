<?php
/*
V1.40 19 September 2001 (c) 2000, 2001 John Lim (jlim@natsoft.com.my). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence.
  Set tabs to 8.
  
  MySQL code that does not support transactions. Use mysqlt if you need transactions.
  Requires mysql client. Works on Windows and Unix.
  
 28 Feb 2001: MetaColumns bug fix - suggested by  Freek Dijkstra (phpeverywhere@macfreek.com)
*/ 

if (! defined("_ADODB_MYSQL_LAYER")) {
 define("_ADODB_MYSQL_LAYER", 1 );

class ADODB_mysql extends ADODBConnection {
	var $databaseType = 'mysql';
    var $hasInsertID = true;
    var $hasAffectedRows = true;	
	var $metaTablesSQL = "SHOW TABLES";	
	var $metaColumnsSQL = "SHOW COLUMNS FROM %s";
	var $fmtTimeStamp = "'Y-m-d H:i:s'";
	var $hasLimit = true;
	var $hasMoveFirst = true;
	var $hasGenID = true;
	
	function ADODB_mysql() 
	{			
	}
	
    function _insertid()
    {
            return mysqli_insert_id($this->_connectionID);
    }
    
    function _affectedrows()
    {
            return mysqli_affected_rows($this->_connectionID);
    }
  
 	// See http://www.mysql.com/doc/M/i/Miscellaneous_functions.html
	// Reference on Last_Insert_ID on the recommended way to simulate sequences
 	var $_genIDSQL = "update %s set id=LAST_INSERT_ID(id+1);";
	var $_genSeqSQL = "create table %s (id int not null)";
	var $_genSeq2SQL = "insert into %s values (0)";
	
	function GenID($seqname='adodbseq')
	{
		if (!$this->hasGenID) return false;
		
		$getnext = sprintf($this->_genIDSQL,$seqname);
		$rs = @$this->Execute($getnext);
		if (!$rs) {
			$u = strtoupper($seqname);
			$this->Execute(sprintf($this->_genSeqSQL,$seqname));
			$this->Execute(sprintf($this->_genSeq2SQL,$seqname));
			$rs = $this->Execute($getnext);
		}
		$this->genID = mysqli_insert_id($this->_connectionID);
		
		if ($rs) $rs->Close();
		
		return $this->genID;
	}
	
  	function &MetaDatabases(): array
	{
		$qid = mysqli_query($this->_connectionID, "SHOW DATABASES");
		$arr = array();
		$i = 0;
		$max = mysqli_num_rows($qid);
		while ($i < $max) {
			$arr[] = $qid->fetch_object()->Database;
			$i += 1;
		}
		return $arr;
	}

	// returns concatenated string
	function Concat()
	{
		$s = "";
		$arr = func_get_args();
		$first = true;
		/*
		foreach($arr as $a) {
			if ($first) {
				$s = $a;
				$first = false;
			} else $s .= ','.$a;
		}*/
		
		// suggestion by andrew005@mnogo.ru
	        $s = implode(',',$arr); 
		if (strlen($s) > 0) return "CONCAT($s)";
		else return '';
	}
	
	// returns true or false
	function _connect($argHostname, $argUsername, $argPassword, $argDatabasename)
	{
		$this->_connectionID = mysqli_connect($argHostname, $argUsername, $argPassword);
		if ($this->_connectionID === false) return false;
		if ($argDatabasename) return $this->SelectDB($argDatabasename);
		return true;	
	}
	
	// returns true or false
	function _pconnect($argHostname, $argUsername, $argPassword, $argDatabasename)
	{
		$this->_connectionID = mysqli_connect("p:$argHostname", $argUsername, $argPassword);
		if ($this->_connectionID === false) return false;
		if ($argDatabasename) return $this->SelectDB($argDatabasename);
		return true;	
	}
	
 	function &MetaColumns($table) 
	{
		if ($this->metaColumnsSQL) {
		
			$rs = $this->Execute(sprintf($this->metaColumnsSQL,$table));
			
			if ($rs === false) return false;
			
			$retarr = array();
			while (!$rs->EOF){
				$fld = new ADODBFieldObject();
				$fld->name = $rs->fields[0];
				$fld->type = $rs->fields[1];
					
				// split type into type(length):
				if (preg_match("/^(.+)\((\d+)\)$/", $fld->type, $query_array)) {
					$fld->type = $query_array[1];
					$fld->max_length = $query_array[2];
				} else {
					$fld->max_length = -1;
				}
				$fld->not_null = ($rs->fields[2] != 'YES');
				$fld->primary_key = ($rs->fields[3] == 'PRI');
				$fld->auto_increment = (strpos($rs->fields[5], 'auto_increment') !== false);
				$fld->binary = (strpos($fld->type,'blob') !== false);
				
				$retarr[strtoupper($fld->name)] = $fld;	
				$rs->MoveNext();
			}
			$rs->Close();
			return $retarr;	
		}
		return false;
	}
		
	// returns true or false
	function SelectDB($dbName) 
	{
		$this->databaseName = $dbName;
		if ($this->_connectionID) {
			return @mysqli_select_db($dbName,$this->_connectionID);		
		}
		else return false;	
	}
	
	// parameters use PostgreSQL convention, not MySQL
	function &SelectLimit($sql,$nrows=-1,$offset=-1,$inputarr=false, $arg3=false,$secs=0)
	{
		$offsetStr =($offset>=0) ? "$offset," : '';
		
		return ($secs) ? $this->CacheExecute($secs,$sql." LIMIT $offsetStr$nrows",$inputarr,$arg3)
			: $this->Execute($sql." LIMIT $offsetStr$nrows",$inputarr,$arg3);
		
	}
	
	// returns queryID or false
	function _query($sql,$inputarr)
	{
	global $ADODB_COUNTRECS;
		if($ADODB_COUNTRECS) return mysqli_query($sql,$this->_connectionID);
		else return mysqli_unbuffered_query($sql,$this->_connectionID); // requires PHP >= 4.0.6
	}

	/*	Returns: the last error message from previous database operation	*/	
	function ErrorMsg() 
	{
		$this->_errorMsg = @mysqli_error($this->_connectionID);
	    	return $this->_errorMsg;
	}
	
	/*	Returns: the last error number from previous database operation	*/	
	function ErrorNo() 
	{
		return @mysqli_errno($this->_connectionID);
	}
	
	// returns true or false
	function _close()
	{
		return @mysqli_close($this->_connectionID);
	}
		
}
	
/*--------------------------------------------------------------------------------------
	 Class Name: Recordset
--------------------------------------------------------------------------------------*/

class ADORecordSet_mysql extends ADORecordSet{	
	
	var $databaseType = "mysql";
	var $canSeek = true;
	var $fetchMode;
	
	function ADORecordSet_mysql($queryID) {
	GLOBAL $ADODB_FETCH_MODE;
	
		switch ($ADODB_FETCH_MODE)
		{
		case ADODB_FETCH_NUM: $this->fetchMode = MYSQL_NUM; break;
		case ADODB_FETCH_ASSOC:$this->fetchMode = MYSQL_ASSOC; break;
		default:
		case ADODB_FETCH_DEFAULT:
		case ADODB_FETCH_BOTH:$this->fetchMode = MYSQL_BOTH; break;
		}
	
		$this->ADORecordSet($queryID);	
	}
	
	function _initrs()
	{
	GLOBAL $ADODB_COUNTRECS;
		$this->_numOfRows = ($ADODB_COUNTRECS) ? @mysqli_num_rows($this->_queryID):-1;
		$this->_numOfFields = @mysqli_num_fields($this->_queryID);
	}
	
	function &FetchField($fieldOffset = -1) {
		if ($fieldOffset != -1) {
			$o =  @mysqli_fetch_field($this->_queryID, $fieldOffset);
			$f = @mysqli_field_flags($this->_queryID,$fieldOffset);
			$o->max_length = @mysqli_field_len($this->_queryID,$fieldOffset); // suggested by: Jim Nicholson (jnich@att.com)
			//$o->max_length = -1; // mysql returns the max length less spaces -- so it is unrealiable
			$o->binary = (strpos($f,'binary')!== false);
		}
		else if ($fieldOffset == -1) {	/*	The $fieldOffset argument is not provided thus its -1 	*/
			$o = @mysqli_fetch_field($this->_queryID);
			$o->max_length = @mysqli_field_len($this->_queryID); // suggested by: Jim Nicholson (jnich@att.com)
			//$o->max_length = -1; // mysql returns the max length less spaces -- so it is unrealiable
		}
		
		return $o;
	}

	function _seek($row)
	{
		return @mysqli_data_seek($this->_queryID,$row);
	}
	
	// 10% speedup to move MoveNext to child class
	function MoveNext($ignore_fields=false) 
	{
		if ($this->_numOfRows != 0 && !$this->EOF) {		
			$this->_currentRow++;
			// using & below slows things down by 20%!
			$this->fields = @mysqli_fetch_array($this->_queryID,$this->fetchMode);
			
			if (is_array($this->fields)) return true;
		}
		$this->EOF = true;
		return false;
	}	
	
	function _fetch($ignore_fields=false)
	{
		$this->fields = @mysqli_fetch_array($this->_queryID,$this->fetchMode);
		return (is_array($this->fields));
	}
	
	function _close() {
		return @mysqli_free_result($this->_queryID);		
	}
	
	function MetaType($t,$len=-1,$fieldobj=false)
	{
		$len = -1; // mysql max_length is not accurate
		switch (strtoupper($t)) {
		case 'STRING': 
		case 'CHAR':
		case 'VARCHAR': 
		case 'TINYBLOB': 
		case 'TINYTEXT': 
		case 'ENUM': 
		case 'SET': 
			if ($len <= $this->blobSize) return 'C';
			
		case 'TEXT':
		case 'LONGTEXT': 
		case 'MEDIUMTEXT':
			return 'X';
			
		// php_mysql extension always returns 'blob' even if 'text'
		// so we have to check whether binary...
		case 'IMAGE':
		case 'LONGBLOB': 
		case 'BLOB':
		case 'MEDIUMBLOB':
			return !empty($fieldobj->binary) ? 'B' : 'X';
			
		case 'DATE': return 'D';
		
		case 'DATETIME':
		case 'TIMESTAMP': return 'T';
		
		case 'INT': 
		case 'INTEGER':
		case 'BIGINT':
		case 'TINYINT':
		case 'MEDIUMINT':
		case 'SMALLINT': 
			
			if (!empty($fieldobj->primary_key)) return 'R';
			else return 'I';
		
		default: return 'N';
		}
	}

}
}
?>