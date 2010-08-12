<?php if (!defined('APPLICATION')) exit();


class CustomController extends PagesController {
	
	public $Uses = array('Form', 'Gdn_MenuFilterModel', 'Gdn_PageModel');
	
	public $PageOverview, $Page;
	
	public function Add()
	{
		$this->Permission('Garden.Pages.Manage');
		$this->View = 'Edit';
		$this->Edit();
	}
	
	public function Delete($TransientKey)
	{
		$this->Permission('Garden.Pages.Manage');
		$this->DeliveryType(DELIVERY_TYPE_BOOL);
		$Session = Gdn::Session();
      
		if ($TransientKey !== false && $Session->ValidateTransientKey($TransientKey)) {
			$Args = func_get_args();
			array_shift($Args);
			$Name = $this->PageModel->Coalesce($Args);
			$Page = $this->PageModel->Get($Name);
			
			if (!$Page)
				$this->Form->AddError('Invalid page!');
			else
				$this->PageModel->Delete($Name);
		} else
			$this->Form->AddError('Invalid transient key!');
		
		$this->Render();
	}
	
	public function Edit()
	{
		$this->Permission('Garden.Pages.Manage');
		$this->AddSideMenu('pages/custom');
		
		$Page = $this->PageModel->Coalesce(func_get_args());
		$this->Form->SetModel($this->PageModel);
		$this->Page = $this->PageModel->Get($Page);
		
		if ($this->Page)
			$this->Form->AddHidden('Page', $this->Page['Url']);
		
		if ($this->Form->AuthenticatedPostBack() === FALSE) {
			$this->Form->SetData($this->Page);
		} else {
			if ($this->Form->Save() != '') {
				$this->StatusMessage = T('Your changes have been saved.');
				$this->RedirectUrl = Url('pages/custom');
			}
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
		$this->Permission('Garden.Pages.Manage');
		$this->AddSideMenu('pages/custom');
		$this->AddJsFile('/js/library/jquery.ui.packed.js');
		$this->AddJsFile('custom.js');
		$this->Title(T('Custom Pages'));
		
		$this->PageOverview = $this->PageModel->Overview();
		$this->Render();
	}
	
}

