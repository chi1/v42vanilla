<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
$NewOrDraft = !isset($this->Comment) || property_exists($this->Comment, 'DraftID') ? TRUE : FALSE;
$Editing = isset($this->Comment);
?>
<div class="MessageForm CommentForm">
   <?php if (!$Editing) { ?>
   <div class="Tabs CommentTabs">
      <ul>
         <li class="Active"><?php echo Anchor(T('Write Comment'), '#', 'WriteButton'); ?></li>
         <?php
         if (!$Editing)
            echo '<li>'.Anchor(T('Preview'), '#', 'PreviewButton')."</li>\n";
         
         if ($NewOrDraft)
            echo '<li>'.Anchor(T('Save Draft'), '#', 'DraftButton')."</li>\n";
   
         $this->FireEvent('AfterCommentTabs');
         ?>
      </ul>
   </div>
   <?php
   } else {
      $this->Form->SetFormValue('Body', $this->Comment->Body);
   }
   echo $this->Form->Open();
   echo $this->Form->Errors();
   echo $this->Form->TextBox('Body', array('MultiLine' => TRUE));
   echo "<div class=\"Buttons\">\n";
   $this->FireEvent('BeforeFormButtons');
   $CancelText = 'Gå tillbaka';
   $CancelClass = 'Button';
   if (!$NewOrDraft) 
      $CancelText = $CancelClass = 'Cancel';

   echo Anchor(T($CancelText), 'discussions', $CancelClass);
   echo $this->Form->Button($Editing ? 'Spara kommentar' : 'Posta kommentar', array('class' => 'Button CommentButton')); // För övrigt borde den som tyckte det var smart att hårdkoda det här skjutas.
   $this->FireEvent('AfterFormButtons');
   echo "</div>\n";
   echo $this->Form->Close();
   ?>
</div>
