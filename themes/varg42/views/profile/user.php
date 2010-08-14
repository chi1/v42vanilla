<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
?>
<div class="User">
   <h1><?php echo $this->User->Name; ?></h1>
   <?php
      if ($this->User->About != '') {
         echo '<div id="Status">'.Gdn_Format::Display($this->User->About);
         if ($this->User->About != '' && ($Session->UserID == $this->User->UserID || $Session->CheckPermission('Garden.Users.Edit')))
            echo ' - ' . Anchor(T('Clear'), '/profile/clear/'.$this->User->UserID.'/'.$Session->TransientKey(), 'Change');
            
         echo '</div>';
      }
   ?>
</div>
<?php if ($this->User->Photo != '' && strtolower(substr($this->User->Photo, 0, 7)) != 'http://') {
   ?>
   <div class="Photo">
      <?php echo Img('uploads/'.ChangeBasename($this->User->Photo, 'p%s')); ?>
   </div>
  <?php
} ?>
<div class="Box About">
   <h4><?php echo T('About'); ?></h4>
   <dl>
      <dt><?php echo T('Username'); ?></dt>
      <dd><?php echo $this->User->Name; ?></dd>
      <?php               
      if ($this->User->ShowEmail == 1 || $Session->CheckPermission('Garden.Registration.Manage')) {
         echo '<dt>'.T('Email').'</dt>
         <dd>'.Gdn_Format::Email($this->User->Email).'</dd>';
      }
      ?>
      <dt><?php echo T('Joined'); ?></dt>
      <dd><?php echo Gdn_Format::Date($this->User->DateFirstVisit); ?></dd>
      <dt><?php echo T('Visits'); ?></dt>
      <dd><?php echo $this->User->CountVisits; ?></dd>
      <dt><?php echo T('Last Active'); ?></dt>
      <dd><?php echo Gdn_Format::Date($this->User->DateLastActive); ?></dd>
      <dt><?php echo T('Roles'); ?></dt>
      <dd><?php echo implode(', ', $this->Roles); ?></dd>
      <?php               
      if ($this->User->InviteUserID > 0) {
         $Inviter = new stdClass();
         $Inviter->UserID = $this->User->InviteUserID;
         $Inviter->Name = $this->User->InviteName;
         echo '<dt>'.T('Invited by').'</dt>
         <dd>'.UserAnchor($Inviter).'</dd>';
      }
      $this->FireEvent('OnBasicInfo');
      ?>
   </dl>
</div>
