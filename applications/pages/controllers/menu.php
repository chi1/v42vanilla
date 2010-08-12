<?php if (!defined('APPLICATION')) exit();


class MenuController extends PagesController {
	
	public $Uses = array('Form', 'Gdn_MenuFilterModel' /*, 'Gdn_PageModel' */);
	
	public function Add($Group = '')
	{
		$this->Permission('Garden.Menu.Manage');
		$this->View = 'Edit';
		$this->Edit($Group);
	}
	
	public function Toggle($Group)
	{
		$this->Permission('Garden.Menu.Manage');
		$this->View = 'Index';
		
		$this->MenuFilterModel->Toggle($Group);
		$this->Index();
	}
	
	public function Edit($Group = '', $Index = '')
	{
		$this->Permission('Garden.Messages.Manage');
		$this->AddSideMenu('pages/menu');
		
		$this->Form->SetModel($this->MenuFilterModel);
		$this->MenuItem = $this->MenuFilterModel->GetID($Group, $Index);
		
		if ($this->MenuItem) {
			if ($this->MenuItem['Builtin'])
				$this->MenuItem = 0;
			else if ($this->MenuItem['Index'] === '') /* we're only  using the group as a template */
				$this->MenuItem = array('Group' => $this->MenuItem['Group']);
			else
				$this->Form->AddHidden('MenuID', $Group.'/'.$Index);
		}
		
		if ($this->Form->AuthenticatedPostBack() === FALSE) {
			$this->Form->SetData($this->MenuItem);
		} else {
			if ($this->Form->Save() != '') {
				$this->StatusMessage = T('Your changes have been saved.');
				$this->RedirectUrl = Url('pages/menu');
			}
		}
		
		$this->Render();
	}
	
	public function Delete($Group = '', $Index = '', $TransientKey = false)
	{
		$this->Permission('Garden.Messages.Manage');
		$this->DeliveryType(DELIVERY_TYPE_BOOL);
		$Session = Gdn::Session();
      
		if ($TransientKey !== false && $Session->ValidateTransientKey($TransientKey)) {
			$Item = $this->MenuFilterModel->GetID($Group, $Index);
			if (!$Item)
				$this->Form->AddError('Invalid group/index!');
			else if ($Item['Builtin'])
				$this->Form->AddError('I can\'t touch builtin menu groups!');
			else
				$this->MenuFilterModel->Delete($Group, $Index);
		} else
			$this->Form->AddError('Invalid transient key!');
		
		$this->Render();
	}
	
	public function Sort()
	{
		$Session = Gdn::Session();
		
		try {
			$TransientKey = GetPostValue('TransientKey', '');
			if (!$Session->ValidateTransientKey($TransientKey))
				throw new Exception('Invalid transient key');
			
			$TableID = GetPostValue('TableID', false);
			if (!$TableID)
				throw new Exception('I can\'t do anything without a reference TableID!');
			
			$Rows = GetPostValue($TableID, false);
			if (!is_array($Rows))
				throw new Exception('You haven\'t given me any data!');
			
			if ($TableID == 'MenuFilterTable') {
				array_shift($Rows); // first element = header
				
				$this->MenuFilterModel->SortGroups($Rows);
			} else if (substr($TableID, 0, 15) == 'MenuItemsTable_') {
				$Group = substr($TableID, 15);
				
				if (!$this->MenuFilterModel->SortItems($Group, $Rows))
					throw new Exception('I screwed up trying to sort the group items (bad group name?)');
			} else
					throw new Exception('I don\'t understand the table reference you\'ve given me');
			
		} catch (Exception $E) {
			$this->Form->AddError($E->getMessage());
		}
		
		$this->Render();
	}
	
	public function Initialize()
	{
		parent::Initialize();
		if ($this->Menu)
			$this->Menu->HighlightRoute('/garden/settings');
	}
	
	public function Index()
	{
		$this->Permission('Garden.Menu.Manage');
		$this->AddSideMenu('pages/menu');
		$this->AddJsFile('/js/library/jquery.tablednd.js');
		$this->AddJsFile('/js/library/jquery.ui.packed.js');
		$this->AddJsFile('menu.js');
		$this->Title(T('Menu'));
		
		$this->MenuOrder = $this->MenuFilterModel->Order;
		$this->MenuData = $this->MenuFilterModel->Filter;
		$this->Render();
	}
}