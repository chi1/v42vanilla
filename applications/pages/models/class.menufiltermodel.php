<?php if (!defined('APPLICATION')) exit();


class Gdn_MenuFilterModel extends Gdn_Model {
	
	const EXTRA_GROUP_VARS = 3;
	
	public $Order = array();
	public $Filter = array();
	
	public $SaveFile = '';
	
	public function __construct() {
		$this->Load();
		
		parent::__construct('MenuFilter');
	}
	
	function Toggle($Group, $ActuallySetTo = '')
	{
		if (!isset($this->Filter[$Group]))
			return false;
		
		if ($ActuallySetTo === '')
			$this->Filter[$Group]['Enabled'] = !$this->Filter[$Group]['Enabled'];
		else
			$this->Filter[$Group]['Enabled'] = (bool)$ActuallySetTo;
		
		$this->SaveToFile();
		return true;
	}
	
	function Delete($Group, $Index = '')
	{
		if (!isset($this->Filter[$Group]))
			return false;
		
		$Items = &$this->Filter[$Group];
		if ($Items['Builtin'] || !isset($Items[$Index]))
			return false;
		
		unset($Items[$Index]);
		if (count($Items) == self::EXTRA_GROUP_VARS)
			unset($this->Filter[$Group]); /* but we do remove the group if there's nothign left */
		else {
			foreach ($Items['Order'] as $i => $RefIndex) {
				if ($Index != $RefIndex)
					continue;
				
				unset($Items['Order'][$i]);
			}
			
			$Items['Order'] = array_values($Items['Order']);
		}
		
		$this->SaveToFile();
	}
	
	//new order = array of group names, sorted as desired
	function SortGroups($NewOrder)
	{
		if (count($NewOrder) != $this->Order)
			return false;
		
		$this->Order = $NewOrder;
		$this->SaveToFile();
		return true;
	}
	
	function SortItems($Group, $NewOrder)
	{
		if (!isset($this->Filter[$Group]))
			return false;
		
		if (count($NewOrder) != count($this->Filter[$Group]['Order']) + self::EXTRA_GROUP_VARS)
			return false;
		
		$this->Filter[$Group]['Order'] = $NewOrder;
		$this->SaveToFile();
		return true;
	}
	
	function Save($FormValues)
	{
		$this->Validation->ApplyRule('Group', 'Required');
		$this->Validation->ApplyRule('Group', 'UrlString', 'The group name can only contain letters, numbers and underscores');
		$this->Validation->ApplyRule('Text', 'Required');
		$this->Validation->ApplyRule('Url', 'Required');
		
		$Group = $FormValues['Group'];
		if (isset($FormValues['MenuID'])) {
			$Item = $this->GetID($FormValues['MenuID']);
			if (!$Item)
				$this->Validation->AddValidationResult('MenuID', 'Invalid menu ID supplied for editing');
		} else
			$Item = 0;
		
		if ((isset($this->Filter[$Group]) && $this->Filter[$Group]['Builtin']) || 
			(is_array($Item) && $this->Filter[$Item['Group']]['Builtin']))
			$this->Validation->AddValidationResult('Group', 'Only items in a custom group can be edited');
		
		if (!$this->Validate($FormValues))
			return false;
		
		if (is_array($Item) && $Group == $Item['Group']) {
			$ToEdit = &$this->Filter[$Item['Group']][$Index = $Item['Index']];
		} else {
			if (!isset($this->Filter[$Group])) {
				$this->Filter[$Group] = array('Builtin' => 0, 'Enabled' => 1, 'Order' => array());
				$this->Order[] = $Group;
			}
			
			$Index = count($this->Filter[$Group]) - self::EXTRA_GROUP_VARS;
			$this->Filter[$Group][$Index] = array();
			$this->Filter[$Group]['Order'][] = $Index;
			
			$ToEdit = &$this->Filter[$Group][$Index];
			
			if (is_array($Item)) {
				unset($this->Filter[$Item['Group']][$Item['Index']]);
				if (count($this->Filter[$Item['Group']]) == self::EXTRA_GROUP_VARS)
					unset($this->Filter[$Item['Group']]);
			}
		}
		
		$ToEdit = array(
			'Text'		=> $FormValues['Text'], 
			'Roles'		=> '', /* todo */
			'Url'		=> $FormValues['Url'], 
			'Attributes' => '' /* todo */
		);
		
		$this->SaveToFile();
		
		return $Group.'/'.$Index;
	}
	
	function GetID($Group, $Index = '')
	{
		if ($Index === '') {
			$Tmp = explode('/', $Group, 2);
			if (count($Tmp) == 2)
				list($Group, $Index) = $Tmp;
		}
		
		if (!isset($this->Filter[$Group]))
			return 0;
		
		if ($Index === '') {
			$Item = $this->Filter[$Group];
			unset($Item['Order']);
		} else {
			if (!isset($this->Filter[$Group][$Index]))
				return 0;
			
			$Item = $this->Filter[$Group][$Index];
			$Item['Builtin'] = $this->Filter[$Group]['Builtin'];
		}
		
		$Item['Group'] = $Group;
		$Item['Index'] = $Index;
		
		return $Item;
	}
	
	function Load($File = '', $ForceLoad = 0)
	{
		//load default
		if ($File == '')
			$File = PATH_CONF . DS . 'menu.php';
		if ($File == $this->SaveFile && !$ForceLoad)
			return;
		
		if (file_exists($File))
			list($this->Order, $this->Filter) = include($File);
		
		$this->SaveFile = $File;
	}
	
	function Filter(&$MenuModule)
	{
		$Items = &$MenuModule->Items;
		foreach ($this->Order as $Group) {
			$Filter = &$this->Filter[$Group];
			
			if ($Filter['Builtin']) {
				if (isset($Items[$Group]) && !$Filter['Enabled'])
					unset($Items[$Group]);
				continue;
			}
			
			if (!$Filter['Enabled'])
				continue;
			
			foreach($Filter['Order'] as $Index) {
				$Link = &$Filter[$Index];
				$MenuModule->AddLink($Group, $Link['Text'], $Link['Url']);
			}
			
		}
		
		$MenuModule->Sort = $this->Order;
	}
	
	function Update(&$MenuModule)
	{
		$New = array();
		
		foreach ($MenuModule->Items as $Group => $Data) {
			if (isset($this->Filter[$Group]))
				continue;
			
			$this->Filter[$Group] = array('Builtin' => 1, 'Enabled' => 1, 'Order' => array());
			foreach ($Data as $Index => $Entry) {
				$this->Filter[$Group][$Index] = array(
					'Text' 		 => $Entry['Text'], 
					'Roles'		 => 0,
					'Url'		 => $Entry['Url'], 
					'Attributes' => $Entry['Attributes']
				);
				
				$this->Filter[$Group]['Order'][$Index] = $Index;
			}
			
			$New[$Group] = 1;
		}
		
		if (count($New)) {
			foreach ($MenuModule->Sort as $Group) {
				if (!isset($New[$Group]))
					continue;
				
				$this->Order[] = $Group;
				unset($New[$Group]);
			}
			
			foreach ($New as $Group => $Dummy) {
				$this->Order[] = $Group;
			}
			
			$this->SaveToFile();
		}
	}
	
	function SaveToFile($File = '')
	{
		$Contents = '<?php if (!defined(\'APPLICATION\')) exit(); return ';
		$Contents .= $this->_FormatArrayForSave(array($this->Order, $this->Filter));
		$Contents .= '; ?>';
		
		if ($File == '')
			$File = $this->SaveFile;
		
		Gdn_FileSystem::SaveFile($File, $Contents);
	}
	
	function _FormatValueForSave($Value)
	{
		return is_int($Value) 
			? $Value 
			: '"'.str_replace(
				array("\\", "\""), 
				array("\\\\", "\\\""), 
				$Value).'"';
	}
	
	function _FormatArrayForSave($Array)
	{
		$Out = array();
		
		foreach ($Array as $Key => $Value) {
			$Out[] = $this->_FormatValueForSave($Key)
						. ' => ' 
						. (is_array($Value) ? $this->_FormatArrayForSave($Value) : $this->_FormatValueForSave($Value));
		}
		
		return 'array('.implode(', ', $Out).')';
	}
	
}
