<?php if (!defined('APPLICATION')) exit();
$this->Title(T('All Discussions'));
include($this->FetchViewLocation('helper_functions', 'discussions', 'vanilla'));

/* Filtertabs are evil. I hate them.
WriteFilterTabs($this);*/
if ($this->DiscussionData->NumRows() > 0 || (is_object($this->AnnounceData) && $this->AnnounceData->NumRows() > 0)) {
?>
<ul class="DataList Discussions">
   <?php include($this->FetchViewLocation('discussions')); ?>
</ul>
<?php
   echo $this->Pager->ToString('more');
} else {
   ?>
   <div class="Empty"><?php echo T('No discussions were found.'); ?></div>
   <?php
}
