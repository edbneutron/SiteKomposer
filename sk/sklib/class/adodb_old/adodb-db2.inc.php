<?php
/* 
V1.40 19 September 2001 (c) 2000, 2001 John Lim (jlim@natsoft.com.my). All rights reserved.
  Released under both BSD license and Lesser GPL library license. 
  Whenever there is any discrepancy between the two licenses, 
  the BSD license will take precedence. 
Set tabs to 4 for best viewing.
  
  Latest version is available at http://php.weblogs.com/
  
  DB2 data driver. Requires ODBC.
 
From phpdb list:

Hi Andrew,

thanks a lot for your help. Today we discovered what
our real problem was:

After "playing" a little bit with the php-scripts that try
to connect to the IBM DB2, we set the optional parameter
Cursortype when calling odbc_pconnect(....).

And the exciting thing: When we set the cursor type
to SQL_CUR_USE_ODBC Cursor Type, then
the whole query speed up from 1 till 10 seconds
to 0.2 till 0.3 seconds for 100 records. Amazing!!!

Therfore, PHP is just almost fast as calling the DB2
from Servlets using JDBC (don't take too much care
about the speed at whole: the database was on a
completely other location, so the whole connection
was made over a slow network connection).

I hope this helps when other encounter the same
problem when trying to connect to DB2 from
PHP.

Kind regards,
Christian Szardenings

*/

if (!defined('_ADODB_ODBC_LAYER')) {
	include(ADODB_DIR."/adodb-odbc.inc.php");
}
if (!defined('ADODB_DB2')){
define('ADODB_DB2',1);
class ADODB_DB2 extends ADODB_odbc {
	var $databaseType = "db2";	
	var $concat_operator = 'CONCAT';
	var $curmode = SQL_CUR_USE_ODBC;

/*
	// HANDLED BY PARENT CLASS
	
	function BeginTrans()
	{       
		$this->autoCommit = false;
		return true;
	}
	
	function CommitTrans()
	{
		$this->Execute('COMMIT');
		$this->autoCommit = true;
		return true;
	}
	
	function RollbackTrans()
	{
		$this->Execute('ROLLBACK');
		$this->autoCommit = true;
		return true;
	}
*/
	function &SelectLimit($sql,$nrows=-1,$offset=-1,$arg3=false)
	{
		if ($offset <= 0) {
		// could also use " OPTIMIZE FOR $nrows ROWS "
			$sql .=  " FETCH FIRST $nrows ROWS ONLY ";
			return $this->Execute($sql,false,$arg3);
		} else
			return ADODBConnection::SelectLimit($sql,$nrows,$offset,$arg3);
	}
	
};
 

class  ADORecordSet_db2 extends ADORecordSet_odbc {	
	
	var $databaseType = "db2";		
	
	function ADORecordSet_db2($id)
	{
		$this->ADORecordSet_odbc($id);
	}

	function MetaType($t,$len=-1,$fieldobj=false)
	{
		switch (strtoupper($t)) {
		case 'VARCHAR':
		case 'CHAR':
		case 'CHARACTER':
			if ($len <= $this->blobSize) return 'C';
		
		case 'LONGCHAR':
		case 'TEXT':
		case 'CLOB':
		case 'DBCLOB': // double-byte
			return 'X';
		
		case 'BLOB':
		case 'GRAPHIC':
		case 'VARGRAPHIC':
			return 'B';
			
		case 'DATE':
			return 'D';
		
		case 'TIME':
		case 'TIMESTAMP':
			return 'T';
		
		//case 'BOOLEAN': 
		//case 'BIT':
		//	return 'L';
			
		//case 'COUNTER':
		//	return 'R';
			
		case 'INT':
		case 'INTEGER':
		case 'BIGINT':
		case 'SMALLINT':
			return 'I';
			
		default: return 'N';
		}
	}
}

} //define
?>