<?//*********** Text-Output for HTML **************

$output.="<div class=\"sktext\" style=\"text-align:".$this->attributes_vars['align']."; margin:".$this->attributes_vars['margin'].";\">";
IF ($GLOBALS['debug']>0) $GLOBALS['DEBUG_OUTPUT'].=$GLOBALS['current_site']->attributes['dirname'];
$site_url=$GLOBALS['current_site']->attributes['site_url'].$GLOBALS['current_site']->attributes['dirname'];
$output.=str_replace("%server_address%",SERVER_ADDRESS,$this->attributes['objtext']);
$output.="</div>";


?>