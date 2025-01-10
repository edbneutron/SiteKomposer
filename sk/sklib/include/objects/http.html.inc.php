<?//*********** HTTP-Include **************

	require_once(CLASS_PATH.'/http/http.inc' );
	header( 'Content-Type: text/html' );
	
	$http_client = new http( HTTP_V11, false);
	$http_client->host = $this->attributes_vars['httpaddress'];
	
	if ( $http_client->get( $this->attributes_vars['httppath'] ) == HTTP_STATUS_OK)
		$external_page=$http_client->get_response_body();

	unset( $http_client );
	$external_page=html_compress($external_page);
$startpos=0;	
$strlen=strlen($external_page);
$endpos=$strlen;

if($this->attributes_vars['startstring']>"") $startpos=strpos($external_page,$this->attributes_vars['startstring']);
if($this->attributes_vars['endstring']>"") $endpos=strpos($external_page,$this->attributes_vars['endstring'],$startpos);
$startlen=strlen($this->attributes_vars['startstring']);
$endlen=strlen($this->attributes_vars['endstring']);
$thislen=$strlen-($startpos+$startlen)-($strlen-($endpos));
$outstring=substr($external_page,$startpos+$startlen,$thislen);

DEBUG_out(3,"debug3","http: start $startpos len $strlen endpos $endpos thislen $thislen endlen $endlen");
DEBUG_out(3,"debug3","outstring $outstring");

$output=$outstring;




?>