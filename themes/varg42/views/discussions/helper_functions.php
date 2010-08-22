<?php if (!defined('APPLICATION')) exit();

function WriteDiscussion($Discussion, &$Sender, &$Session, $Alt) {
   $CssClass = 'Item';
   $CssClass .= $Discussion->Bookmarked == '1' ? ' Bookmarked' : '';
   $CssClass .= $Alt.' ';
   $CssClass .= $Discussion->Announce == '1' ? ' Announcement' : '';
   $CssClass .= $Discussion->InsertUserID == $Session->UserID ? ' Mine' : '';
   $CountUnreadComments = 0;
   if (is_numeric($Discussion->CountUnreadComments))
      $CountUnreadComments = $Discussion->CountUnreadComments;
      
   // Logic for incomplete comment count.
   if($Discussion->CountCommentWatch == 0 && $DateLastViewed = GetValue('DateLastViewed', $Discussion)) {
      if(Gdn_Format::ToTimestamp($DateLastViewed) >= Gdn_Format::ToTimestamp($Discussion->LastDate)) {
         $CountUnreadComments = 0;
         $Discussion->CountCommentWatch = $Discussion->CountComments;
      } else {
         $CountUnreadComments = '';
      }
   }
   $CssClass .= ($CountUnreadComments > 0 && $Session->IsValid()) ? ' New' : '';
   $Sender->EventArguments['Discussion'] = &$Discussion;
   $First = UserBuilder($Discussion, 'First');
   $Last = UserBuilder($Discussion, 'Last');
   $DiscussionName = Gdn_Format::Text($Discussion->Name);
   if ($DiscussionName == '')
      $DiscussionName = T('Blank Discussion Topic');

   static $FirstDiscussion = TRUE;
   if (!$FirstDiscussion)
      $Sender->FireEvent('BetweenDiscussion');
   else
      $FirstDiscussion = FALSE;
      
   $Commentscount = $Discussion->CountComments-1; // FIXME: Fulhakk, eh? :D Förstår inte varför förstaposten räknas som kommentar
?>
<li class="<?php echo $CssClass; ?>">
   <?php
   $Sender->FireEvent('BeforeDiscussionContent');
   ?>
   <div class="ItemContent Discussion">
      <?php echo Anchor($DiscussionName, '/discussion/'.$Discussion->DiscussionID.'/'.Gdn_Format::Url($Discussion->Name).($Discussion->CountCommentWatch > 0 && C('Vanilla.Comments.AutoOffset') ? '/#Item_'.$Discussion->CountCommentWatch : ''), 'Title'); ?>
      <?php $Sender->FireEvent('AfterDiscussionTitle'); ?>
      <div class="Meta">
         <?php if ($Discussion->Announce == '1') { ?>
         <span class="Announcement"><?php echo T('Announcement'); ?></span>
         <?php } ?>
         <?php if ($Discussion->Closed == '1') { ?>
         <span class="Closed"><?php echo T('Closed'); ?></span>
         <?php } ?>
         <span class="CommentCount"><?php printf(Plural($Commentscount, '%s comment', '%s comments'), $Commentscount); ?></span>
         <?php
            if ($Session->IsValid()) {
               if ($CountUnreadComments == $Discussion->CountComments)
                  echo '<strong>'.T('New').'</strong>';
               else if ($CountUnreadComments > 0)
                  echo '<strong>'.Plural($CountUnreadComments, '%s New', '%s New Plural').'</strong>';
            }

            if ($Discussion->LastCommentID != '') {
               echo '<span class="LastCommentBy">'.sprintf(T('Most recent by %1$s'), UserAnchor($Last)).'</span>';
               echo '<span class="LastCommentDate">'.Gdn_Format::Date($Discussion->LastDate).'</span>';
            } else {
               echo '<span class="LastCommentBy">'.sprintf(T('Started by %1$s'), UserAnchor($First)).'</span>';
               echo '<span class="LastCommentDate">'.Gdn_Format::Date($Discussion->FirstDate).'</span>';
            }
         
            if (C('Vanilla.Categories.Use'))
               echo Wrap(Anchor($Discussion->Category, '/categories/'.$Discussion->CategoryUrlCode, 'Category'));
               
            $Sender->FireEvent('DiscussionMeta');
         ?>
      </div>
   </div>
</li>
<?php
}

function WriteFilterTabs(&$Sender) {
   $Session = Gdn::Session();
   $Title = property_exists($Sender, 'Category') && is_object($Sender->Category) ? $Sender->Category->Name : T('All Discussions');
   $Bookmarked = T('My Bookmarks');
   $MyDiscussions = T('My Discussions');
   $MyDrafts = T('My Drafts');
   $CountBookmarks = 0;
   $CountDiscussions = 0;
   $CountDrafts = 0;
   if ($Session->IsValid()) {
      $CountBookmarks = $Session->User->CountBookmarks;
      $CountDiscussions = $Session->User->CountDiscussions;
      $CountDrafts = $Session->User->CountDrafts;
   }
   if (is_numeric($CountBookmarks) && $CountBookmarks > 0)
      $Bookmarked .= '<span>'.$CountBookmarks.'</span>';            

   if (is_numeric($CountDiscussions) && $CountDiscussions > 0)
      $MyDiscussions .= '<span>'.$CountDiscussions.'</span>';            

   if (is_numeric($CountDrafts) && $CountDrafts > 0)
      $MyDrafts .= '<span>'.$CountDrafts.'</span>';
      
   ?>
<div class="Tabs DiscussionsTabs">
   <ul>
      <?php $Sender->FireEvent('BeforeDiscussionTabs'); ?>
      <li<?php echo strtolower($Sender->ControllerName) == 'discussionscontroller' && strtolower($Sender->RequestMethod) == 'index' ? ' class="Active"' : ''; ?>><?php echo Anchor(T('All Discussions'), 'discussions'); ?></li>
      <?php $Sender->FireEvent('AfterAllDiscussionsTab'); ?>
      <?php if ($CountBookmarks > 0 || $Sender->RequestMethod == 'bookmarked') { ?>
      <li<?php echo $Sender->RequestMethod == 'bookmarked' ? ' class="Active"' : ''; ?>><?php echo Anchor($Bookmarked, '/discussions/bookmarked', 'MyBookmarks'); ?></li>
      <?php
         $Sender->FireEvent('AfterBookmarksTab');
      }
      if ($CountDiscussions > 0 || $Sender->RequestMethod == 'mine') {
      ?>
      <li<?php echo $Sender->RequestMethod == 'mine' ? ' class="Active"' : ''; ?>><?php echo Anchor($MyDiscussions, '/discussions/mine', 'MyDiscussions'); ?></li>
      <?php
      }
      if ($CountDrafts > 0 || $Sender->ControllerName == 'draftscontroller') {
      ?>
      <li<?php echo $Sender->ControllerName == 'draftscontroller' ? ' class="Active"' : ''; ?>><?php echo Anchor($MyDrafts, '/drafts', 'MyDrafts'); ?></li>
      <?php
      }
      $Sender->FireEvent('AfterDiscussionTabs');
      ?>
   </ul>
   <?php
   if (property_exists($Sender, 'Category') && is_object($Sender->Category)) {
      ?>
      <div class="SubTab">↳ <?php echo $Sender->Category->Name; ?></div>
      <?php
   }
   ?>
</div>
   <?php
}
