<?php

if ($this->PageFile == '')
	echo "That's not a page!";
else {
?>
	
   	<p>arnold <?php echo Gdn_Format::markdown(file_get_contents($this->PageFile)); ?>arlold</p>

<?php
}

?>
