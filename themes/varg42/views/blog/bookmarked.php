<?php if (!defined('APPLICATION')) exit();
$this->Title(T('My Bookmarks'));
include($this->FetchViewLocation('helper_functions', 'blog', 'vanilla'));

WriteFilterTabs($this);
if ($this->DiscussionData->NumRows() > 0) {
?>
<ul class="DataList Blog Bookmarks">
   <?php include($this->FetchViewLocation('blog')); ?>
</ul>
<?php
   echo $this->Pager->ToString('more');
} else {
   ?>
   <div class="Empty"><?php echo T('No blog were found.'); ?></div>
   <?php
}
