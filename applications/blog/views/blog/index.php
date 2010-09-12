<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
?>
<ul class="Blog">
<?php foreach ($this->BlogData->Result() as $Blogpost): ?>
<li class="Item">
   <div class="ItemContent Blogpost">
   	<h2 class="DiscussionTitle"><?php echo Anchor(Gdn_Format::Text($Blogpost->Name), '/discussion/'.$Blogpost->DiscussionID, 'Title'); ?></h2>
   	<div class="Message Blogbody">
   		<p><?php echo Gdn_Format::To($Blogpost->Body, $Blogpost->Format); ?></p>
   	</div>
   	<div class="Meta">
   	<div class="BlogAuthor">
   	<span class="Author"><?php echo Anchor($Blogpost->FirstName, '/profile/'.$Blogpost->FirstName, '') ?></span><br />
   	<?php  if ($Blogpost->FirstPhoto != '') { ?><span class="Photo"><?php echo Anchor(Img('uploads/'.ChangeBasename($Blogpost->FirstPhoto, 'n%s')), '/profile/'.$Blogpost->FirstName, ''); ?></span><?php } ?>
   	</div>
         <?php if ($Blogpost->Closed == '1') { ?>
         <span class="Closed"><?php echo T('Closed'); ?></span>
         <?php } ?>
            
            <?php 
            $count = $Blogpost->CountComments - 1;
            $text = ($count > 1) ? $count . ' kommentarer' : 'Inga kommentarer';
            
           echo '<div class="BlogCommentCount">'.Anchor($text, '/discussion/'.$Blogpost->DiscussionID, 'BlogCommentCount').'</div>';

         ?>         
               

      </div>
   </div>   
</li>
<?php endforeach; ?>
</ul>
