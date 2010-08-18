<?php if (!defined('APPLICATION')) exit();
// Create some variables so that they aren't defined in every loop.
$ViewLocation = $this->FetchViewLocation('blog', 'blog', 'vanilla');
?>
<ul class="DataList Blog">
   <?php include($ViewLocation); ?>
</ul>
<?php
echo $this->Pager->ToString('more');
