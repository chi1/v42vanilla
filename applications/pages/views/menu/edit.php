<?php if (!defined('APPLICATION')) exit();?>
<h1><?php
	if (is_array($this->MenuItem))
		echo T('Edit Menu Item');
	else
		echo T('Add Menu Item');
?></h1>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<ul>
   <li>
      <?php
         echo $this->Form->Label('Menu Group', 'Group');
         echo $this->Form->Input('Group');
      ?>
   </li>
   <li>
      <?php
         echo $this->Form->Label('Name', 'Text');
         echo $this->Form->Input('Text');
      ?>
   </li>
   <li>
      <?php
         echo $this->Form->Label('Url', 'Url');
         echo $this->Form->Input('Url');
      ?>
   </li>
</ul>
<?php echo $this->Form->Close('Save'); ?>