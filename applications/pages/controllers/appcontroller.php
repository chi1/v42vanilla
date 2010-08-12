<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

class PagesController extends Gdn_Controller {
   
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
		
		$this->AddCssFile('admin.css');
		$this->AddCssFile('pages.css');
		$this->MasterView = 'admin';
		parent::Initialize();
   }
   
	public function AddSideMenu($CurrentUrl) {
		if ($this->_DeliveryType == DELIVERY_TYPE_ALL) {
			$SideMenu = new Gdn_SideMenuModule($this);
			$SideMenu->HtmlId = '';
			$SideMenu->HighlightRoute($CurrentUrl);
			$this->EventArguments['SideMenu'] = &$SideMenu;
			$this->FireEvent('GetAppSettingsMenuItems');
			$this->AddModule($SideMenu, 'Panel');
		}
	}
   
}
