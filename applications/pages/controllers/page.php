<?php

class PageController extends Gdn_Controller {
	
	public $Uses = array('Gdn_PageModel');
	
	public $PageFile = '';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function Initialize() {
		$this->Head = new HeadModule($this);
		$this->AddJsFile('js/library/jquery.js');
		$this->AddJsFile('js/library/jquery.livequery.js');
		$this->AddJsFile('js/library/jquery.form.js');
		$this->AddJsFile('js/library/jquery.popup.js');
		$this->AddJsFile('js/library/jquery.gardenhandleajaxform.js');
		$this->AddJsFile('js/global.js');

		$this->AddCssFile('style.css');
		parent::Initialize();
	}

	function Index()
	{
		$PageName = $this->PageModel->Coalesce(func_get_args());
		$this->PageFile = $this->PageModel->Resolve($PageName);
		
		$Settings = $this->PageModel->Settings($PageName);
		if (isset($Settings['Title']))
			$this->Title($Settings['Title']);
		
		$this->Render();
	}
	
}

?>