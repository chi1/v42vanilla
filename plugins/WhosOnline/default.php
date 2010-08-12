<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['WhosOnline'] = array(
   'Name' => 'Whos online',
   'Description' => "Lists the users currently browsing the forum.",
   'Version' => '0.6',
   'Author' => "Gary Mardell",
   'AuthorEmail' => 'info@garymardell.co.uk',
   'AuthorUrl' => 'http://garymardell.co.uk',
   'RegisterPermissions' => FALSE,
   'SettingsPermission' => FALSE
);

class WhosOnlinePlugin implements Gdn_IPlugin {
   
   public function PluginController_WhosOnline_Create(&$Sender) {
      $Sender->AddSideMenu('plugin/whosonline');
      $Sender->Form = new Gdn_Form();
      $Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      $ConfigurationModel->SetField(array('WhosOnline.Location.Show', 'WhosOnline.Frequency', 'WhosOnline.Hide'));
      $Sender->Form->SetModel($ConfigurationModel);
            
      if ($Sender->Form->AuthenticatedPostBack() === FALSE) {    
         $Sender->Form->SetData($ConfigurationModel->Data);    
      } else {
         $Data = $Sender->Form->FormValues();
         $ConfigurationModel->Validation->ApplyRule('WhosOnline.Frequency', array('Required', 'Integer'));
         $ConfigurationModel->Validation->ApplyRule('WhosOnline.Location.Show', 'Required');
         if ($Sender->Form->Save() !== FALSE)
            $Sender->StatusMessage = Gdn::Translate("Your settings have been saved.");
      }
      
      // creates the page for the plugin options such as display options
      $Sender->View = dirname(__FILE__).DS.'views'.DS.'whosonline.php';
      $Sender->Render();
   }

   public function PluginController_ImOnline_Create(&$Sender) {
      // render new block and replace whole thing opposed to just the data
      include_once(PATH_PLUGINS.DS.'WhosOnline'.DS.'class.whosonlinemodule.php');
      $WhosOnlineModule = new WhosOnlineModule($Sender);
      $WhosOnlineModule->GetData();
      echo $WhosOnlineModule->ToString();

   }
   
   public function Base_Render_Before(&$Sender) {
      $ConfigItem = Gdn::Config('WhosOnline.Location.Show', 'every');
      $Controller = $Sender->ControllerName;
      $Application = $Sender->ApplicationFolder;
      $Session = Gdn::Session();     

			// Check if its visible to users
			if(Gdn::Config('WhosOnline.Hide', TRUE) && !$Session->IsValid()) {
				return;
			}
			
      if (!InArrayI($Sender->ControllerName, array('categoriescontroller', 'discussioncontroller', 'discussionscontroller')) && $ConfigItem == 'discussion') return; 
   
      if ($Controller !== "plugin") {
         include_once(PATH_PLUGINS.DS.'WhosOnline'.DS.'class.whosonlinemodule.php');
         $WhosOnlineModule = new WhosOnlineModule($Sender);
         $WhosOnlineModule->GetData();
         $Sender->AddModule($WhosOnlineModule);
         $Session = Gdn::Session();
         $Sender->AddJsFile('/plugins/WhosOnline/whosonline.js');
         $Frequency = Gdn::Config('WhosOnline.Frequency', 4000);
         if (!is_numeric($Frequency))
            $Frequency = 4000;
            
         $Sender->AddDefinition('WhosOnlineFrequency', $Frequency);
      }
   }

   public function Base_GetAppSettingsMenuItems_Handler(&$Sender) {
      $Menu = $Sender->EventArguments['SideMenu'];
      $Menu->AddLink('Add-ons', 'Whos Online', 'plugin/whosonline', 'Garden.Themes.Manage');
   }

   public function Setup() { 
      $Structure = Gdn::Structure();
      $Structure->Table('Whosonline')
         ->Column('UserID', 'int', 11, FALSE, NULL, 'primary', TRUE)
         ->Column('Timestamp', 'int', 11, FALSE, NULL)
         ->Set(FALSE, FALSE); 
   }
}
