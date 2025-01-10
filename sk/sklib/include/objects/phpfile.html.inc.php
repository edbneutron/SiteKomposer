<?//*********** PHP-File-Include **************

if(file_exists(ROOT_PATH.$this->attributes_vars['filename'])) { include(ROOT_PATH.$this->attributes_vars['filename']); }
else {$output.="<div class=\"small\">PHP-File nicht gefunden!</div>"; }

?>