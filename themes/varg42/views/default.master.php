<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
<head>
   <?php $this->RenderAsset('Head'); ?>
</head>
<body id="<?php echo $BodyIdentifier; ?>" class="<?php echo $this->CssClass; ?>">
   <div id="Frame">
   
   <div id="container-wrap">
      <div id="bg">
      <div id="container">
      <div id="Head">
        

				<!--Load custom logo from banner options-->
            
				<h1><a class="Title" href="<?php echo Url('/'); ?>"><?php echo Gdn_Theme::Logo(); ?></a></h1>
                
                  <!-- Start menu -->
                 <div class="Menu">
            <?php
			      $Session = Gdn::Session();
					if ($this->Menu) {
						$this->Menu->AddLink('Dashboard', T('Dashboard'), '/dashboard/settings', array('Garden.Settings.Manage'));
						// $this->Menu->AddLink('Dashboard', T('Users'), '/user/browse', array('Garden.Users.Add', 'Garden.Users.Edit', 'Garden.Users.Delete'));
						// $this->Menu->AddLink('Activity', T('Activity'), '/activity'); Palla activity.
			         $Authenticator = Gdn::Authenticator();
						if ($Session->IsValid()) {
							$Name = $Session->User->Name;
							$CountNotifications = $Session->User->CountNotifications;
							if (is_numeric($CountNotifications) && $CountNotifications > 0)
								$Name .= ' <span>'.$CountNotifications.'</span>';
								
							$this->Menu->AddLink('User', T('Profile'), '/profile/{UserID}/{Username}', array('Garden.SignIn.Allow'), array('class' => 'UserNotifications'));
							$this->Menu->AddLink('SignOut', T('Sign Out'), $Authenticator->SignOutUrl(), FALSE, array('class' => 'SignOut'));
						} else {
							$Attribs = array();
							if (C('Garden.SignIn.Popup') && strpos(Gdn::Request()->Url(), 'entry') === FALSE)
								$Attribs['class'] = 'SignInPopup';
								
							$this->Menu->AddLink('Entry', T('Sign In'), $Authenticator->SignInUrl($this->SelfUrl), FALSE, array('class' => 'SignIn'), $Attribs);
						}
						echo $this->Menu->ToString();
					}
				?>
                  
                  <!-- End menu -->
            
         </div>
      </div>
      
      <div id="Body">
      
         <!-- Start body content: helper menu and discussion list -->
         <?php if ($this->ControllerName == 'discussioncontroller') { // Lite fulhakk för att gömma panelen ibland
         ?>
         <div id="DiscussionContent"><?php $this->RenderAsset('Content'); ?></div>
         <?php } else { ?>
         <div id="Content"><?php $this->RenderAsset('Content'); ?></div>
         <?php } ?>
         
         <!-- End body content -->
         
         <!-- Start panel modules: search, categories, and bookmarked discussions -->
         
         <?php if ($this->ControllerName != 'discussioncontroller') { ?>
         <div id="Panel">
		 
         <div id="Search"><?php
					$Form = Gdn::Factory('Form');
					$Form->InputPrefix = '';
					echo 
						$Form->Open(array('action' => Url('/search'), 'method' => 'get')),
						$Form->TextBox('Search'),
						$Form->Button('Go', array('Name' => '')),
						$Form->Close();
				?></div>
		 
		<?php $this->RenderAsset('Panel'); ?>
         
         </div>
         <?php } ?>
         
         <!-- End panel -->

         
      </div>
      
      <!-- Start foot -->
      
      <div id="Foot">
			<div><div class="vanilla-ico"></div> Powered by <a href="http://vanillaforums.org"><span>Vanilla</span></a></div>
			<?php $this->RenderAsset('Foot'); ?>
		</div>
        
      <!-- End foot -->  
        </div>
        </div>
        </div>
   </div>
<?php $this->FireEvent('AfterBody'); ?>
</body>
</html>
