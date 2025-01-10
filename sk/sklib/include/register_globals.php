<?PHP

/*
 *	Register Global Variables
 *	-----------
 *	
 *	Usage:		handle PHP configs that have REGISTER_GLOBALS turned off.
 */

	if (isset($HTTP_GET_VARS)) {
		reset($HTTP_GET_VARS);
		while ( list($var, $val) = each($HTTP_GET_VARS) ) {
			$$var=$val;
		}
	}

	if (isset($HTTP_POST_VARS)) {
		reset($HTTP_POST_VARS);
		while ( list($var, $val) = each($HTTP_POST_VARS) ) {
			$$var=$val;
		}
	}

	if (isset($HTTP_POST_FILES)) {
		reset($HTTP_POST_FILES);
		while ( list($var, $val) = each($HTTP_POST_FILES) ) {
			extract ($val, EXTR_PREFIX_ALL, "$var");
			$$var = $val['tmp_name'];
		}
	}

	if (isset($HTTP_COOKIE_VARS)) {
		reset($HTTP_COOKIE_VARS);
		while ( list($var, $val) = each($HTTP_COOKIE_VARS) ) {
			$$var=$val;
		}
	}
	
	if (isset($HTTP_SESSION_VARS)) {
		reset($HTTP_SESSION_VARS);
		while ( list($var, $val) = each($HTTP_SESSION_VARS) ) {
			$$var=$val;
		}
	}

	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];
	$argc = $HTTP_SERVER_VARS['argc'];
	$argv = $HTTP_SERVER_VARS['argv'];

	$HTTP_HOST = isset($HTTP_SERVER_VARS['HTTP_HOST']) ? $HTTP_SERVER_VARS['HTTP_HOST'] : $HTTP_ENV_VARS['HTTP_HOST'];
	$HTTP_USER_AGENT = isset($HTTP_SERVER_VARS['HTTP_USER_AGENT']) ? $HTTP_SERVER_VARS['HTTP_USER_AGENT'] : $HTTP_ENV_VARS['HTTP_USER_AGENT'];
	$QUERY_STRING = isset($HTTP_SERVER_VARS['QUERY_STRING']) ? $HTTP_SERVER_VARS['QUERY_STRING'] : $HTTP_ENV_VARS['QUERY_STRING'];
	$REMOTE_ADDR = isset($HTTP_SERVER_VARS['REMOTE_ADDR']) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : $HTTP_ENV_VARS['REMOTE_ADDR'];
	//$HTTPS = isset($HTTP_SERVER_VARS['HTTPS']) ? $HTTP_SERVER_VARS['HTTPS'] : $HTTP_ENV_VARS['HTTPS'];
	$SERVER_NAME = isset($HTTP_SERVER_VARS['SERVER_NAME']) ? $HTTP_SERVER_VARS['SERVER_NAME'] : $HTTP_ENV_VARS['SERVER_NAME'];
	$SERVER_PORT = isset($HTTP_SERVER_VARS['SERVER_PORT']) ? $HTTP_SERVER_VARS['SERVER_PORT'] : $HTTP_ENV_VARS['SERVER_PORT'];
	//$REDIRECT_URL = isset($HTTP_SERVER_VARS['REDIRECT_URL']) ? $HTTP_SERVER_VARS['REDIRECT_URL'] : $HTTP_ENV_VARS['REDIRECT_URL'];

?>