<?php if (!defined('APPLICATION')) exit();
$this->Title(T('All Blog'));
include($this->FetchViewLocation('helper_functions', 'blog', 'vanilla'));

/* Filtertabs are evil. I hate them.
WriteFilterTabs($this);*/
if ($this->DiscussionData->NumRows() > 0 || (is_object($this->AnnounceData) && $this->AnnounceData->NumRows() > 0)) {
?>
<ul class="DataList Blog">
   <?php include($this->FetchViewLocation('blog')); ?>
</ul>
<?php
} else {
   ?>
   <div class="Empty"><?php echo T('No blog were found.'); ?></div>
   <?php
}
