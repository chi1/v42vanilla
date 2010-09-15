<?php if (!defined('APPLICATION')) exit(); ?>
<div id="RecentBlog" class="Box">
   <h4><?php echo 'Senaste bloggposter'; ?></h4>
   <ul class="PanelBlog">
      <?php
      foreach ($this->_BlogData->Result() as $Blog) {

echo '<li>' . Anchor(Gdn_Format::Text($Blog->Name), '/discussion/'.$Blog->DiscussionID, 'Title') . '</li>';

}

echo '</ul></div>'; ?>
