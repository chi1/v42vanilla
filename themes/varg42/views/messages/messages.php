<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();

$Alt = FALSE;
$CurrentOffset = $this->Offset;
foreach ($this->MessageData->Result() as $Message) {
   $CurrentOffset++;
   $Alt = $Alt == TRUE ? FALSE : TRUE;
   $Class = 'Item';
   $Class .= $Alt ? ' Alt' : '';
   if ($this->Conversation->DateLastViewed < $Message->DateInserted)
      $Class .= ' New';
   
   if ($Message->InsertUserID == $Session->UserID)
      $Class .= ' Mine';
      
   if ($Message->InsertPhoto != '')
      $Class .= ' HasPhoto';
      
   $Class = trim($Class);
   $Format = empty($Message->Format) ? 'Display' : $Message->Format;
   $Author = UserBuilder($Message, 'Insert');
?>
<li id="<?php echo $Message->MessageID; ?>"<?php echo $Class == '' ? '' : ' class="'.$Class.'"'; ?>>
   <div class="ConversationMessage">
      <span class="Permalink"><a name="Item_<?php echo $CurrentOffset;?>" class="Item"><?php echo Gdn_Format::Date($Message->DateInserted); ?></a></span>
      <div class="Meta">
         <span class="Author">
            <?php
            echo UserAnchor($Author, 'Name');            
            echo UserPhoto($Author, 'Photo');
            ?>
         </span>
      </div>
      <div class="Message"><p><?php echo Gdn_Format::To($Message->Body, $Format); ?></p></div>
   </div>
</li>
<?php }
