<?php if (!defined('APPLICATION')) exit(); ?>
<?php
   $UcContext = ucfirst($this->Data['Plugin.Flagging.Data']['Context']);
   $ElementID = $this->Data['Plugin.Flagging.Data']['ElementID'];
   $URL = $this->Data['Plugin.Flagging.Data']['URL'];
   $Title = sprintf("Flag this %s",ucfirst($this->Data['Plugin.Flagging.Data']['Context']));
?>
<h2><?php echo T($Title); ?></h2>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<ul>
   <li>
      <div class="Warning">
         Du håller på att anmäla denna  <?php echo T($this->Data['Plugin.Flagging.Data']['Context']); ?>. Tänk först över ifall du är väldigt säker på att det är något du vill göra, och fyll sedan i en bra anledning nedan och tryck "Rapportera!".
      </div>
      Länk till <?php echo T($this->Data['Plugin.Flagging.Data']['Context']); ?>en: <?php echo Anchor("{$UcContext} #{$ElementID}", $URL); ?> - av <?php echo $this->Data['Plugin.Flagging.Data']['ElementAuthor']; ?>
   </li>
   <li>
      <?php
         echo $this->Form->Label('Anledning', 'Plugin.Flagging.Reason');
         echo $this->Form->TextBox('Plugin.Flagging.Reason', array('MultiLine' => TRUE));
      ?>
   </li>
   <?php
      $this->FireEvent('FlagContentAfter');
   ?>
</ul>
<?php echo $this->Form->Close('Flag this!');
