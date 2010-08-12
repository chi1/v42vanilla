<?php if (!defined('APPLICATION')) exit();

if ($this->PageFile == '')
	echo "That's not a page!";
else {
	if (Gdn::Config('Pages.Custom.AllowPHP'))
		include($this->PageFile);
	else
		readfile($this->PageFile);
}

?>