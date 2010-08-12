<?php if (!defined('APPLICATION')) exit();?>
<h1><?php
	if (is_array($this->Page))
		echo T('Edit Page');
	else
		echo T('Add Page');
?></h1>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<ul>
   <li>
      <?php
         echo $this->Form->Label('Url', 'Url');
         echo '/page/' . $this->Form->Input('Url');
      ?>
   </li>
   <li>
      <?php
         echo $this->Form->Label('Title', 'Title');
         echo $this->Form->Input('Title');
      ?>
   </li>
   <li>
      <?php
         echo $this->Form->Label('Content (HTML'.(Gdn::Config('Pages.Custom.AllowPHP') ? ' + PHP' : '').')', 'Content');
         echo $this->Form->TextBox('Content', array('MultiLine' => true, 'class' => 'TextBox PageContent'));
      ?>
   </li>
</ul>
<?php echo $this->Form->Close('Save'); ?>