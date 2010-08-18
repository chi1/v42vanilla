<?php if (!defined('APPLICATION')) exit();
$this->Title(T('My Blog'));
include($this->FetchViewLocation('helper_functions', 'blog', 'vanilla'));
$ViewLocation = $this->FetchViewLocation('blog');
WriteFilterTabs($this);
if ($this->DiscussionData->NumRows() > 0) {
echo $this->Pager->ToString('less');
?>
<ul class="DataList Blog Mine">
   <?php include($ViewLocation); ?>
</ul>
<?php
echo $this->Pager->ToString('more');
} else {
   echo '<div class="Empty">'.T('You have not started any blog.').'</div>';
}
