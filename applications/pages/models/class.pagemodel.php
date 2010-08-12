<?php if (!defined('APPLICATION')) exit();

class Gdn_PageModel extends Gdn_Model {
	
	protected $PATH_PAGES;
	
	/* ordered by precedence */
	public $ValidExtensions = array('', '.html', '.htm', '.php');
	
	public function __construct() {
		$this->PATH_PAGES = PATH_CONF . DS . 'pages';
		
		parent::__construct('Page');
	}
	
	public function _VerifyName($Name)
	{
		return preg_match('#^[\w\d/]+$#i', $Name);
	}
	
	public function Coalesce($Args)
	{
		if (count($Args))
			$Out = implode('/', $Args);
		else
			$Out = '';
		
		return $Out;
	}
	
	public function Delete($Name)
	{
		/* note order */
		$Path = $this->SettingsFile($Name);
		if ($Path != '')
			unlink($Path);
		
		$Path = $this->Resolve($Name);
		if ($Path != '')
			unlink($Path);
	}
	
	/* name <-> url */
	public function Save($FormValues)
	{
		$this->Validation->ApplyRule('Url', 'Required');
		
		$Url = $FormValues['Url'];
		$Url2 = '';
		while ($Url2 != $Url) {
			$Url2 = $Url;
			$Url = str_replace('//', '/', $Url);
		}
		
		if (!$this->_VerifyName($Url))
			$this->Validation->AddValidationResult('Url', 'Url can only contain word/number characters and slashes');
		
		$Original = isset($FormValues['Page']) ? $FormValues['Page'] : '';
		if ($Original != $Url && $this->Resolve($Url))
			$this->Validation->AddValidationResult('Url', 'That url is already being used by another page!');
		
		if ($Original && !$this->Resolve($Original))
			$this->Validation->AddValidationResult('Page', 'I can\'t find the page you told me to edit!');
		
		if (!$this->Validate($FormValues))
			return false;
		
		if ($Original)
			$Page = $this->Get($Original);
		else
			$Page = array('Settings' => array());
		
		$Page['Url'] = $Url;
		$Page['Title'] = $Page['Settings']['Title'] = $FormValues['Title'];
		$Page['Content'] = $FormValues['Content'];
		/* todo: more settings */
		
		if ($Original && $Original != $Url)
			$this->Delete($Original);
		
		if (!$this->SaveToFile($Page)) {
			$this->Validation->AddValidationResult('Content', 
				'There was an error writing file '.$this->PATH_PAGES.DS.$Page['Url'].' and/or its settings file');
			return false;
		}
		
		return $Page['Url'];
	}
	
	private function _MakeDirectory($Path)
	{
		$Dir = dirname($Path);
		if (is_dir($Dir))
			return true;
		
		if (!$this->_MakeDirectory($Dir))
			return false;
		
		return mkdir($Dir);
	}
	
	public function SaveToFile($Page)
	{
		// we need not worry about escaping from conf/pages/ because we already verified the url above
		$Path = $this->PATH_PAGES . DS . $Page['Url'] . Gdn::Config('Pages.Custom.DefaultExtension');
		if (!$this->_MakeDirectory($Path))
			return false;
		Gdn_FileSystem::SaveFile($Path, $Page['Content']);
		
		$Path = $this->SettingsFile($Page['Url'], false);
		if ($Path == '')
			return false;
		
		$Contents = '<?php if (!defined(\'APPLICATION\')) exit(); return ';
		$Contents .= $this->_FormatArrayForSave($Page['Settings']);
		$Contents .= '; ?>';
		Gdn_FileSystem::SaveFile($Path, $Contents);
		
		return true;
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
	
	private function _Urlize($String)
	{
		$Pos = strrpos($String, '.');
		if ($Pos !== false)
			$String = substr($String, 0, $Pos);
		
		return ltrim(str_replace('\\', '/', $String), '/');
	}
	
	public function Get($Name)
	{
		$Path = $this->Resolve($Name);
		if ($Path == '')
			return '';
		
		$Name = $this->_Urlize(substr($Path, strlen($this->PATH_PAGES)));
		$Out = array('Url' => $Name, 'Path' => $Path);
		
		$Out['Content'] = Gdn_FileSystem::GetContents($Path);
		$Out['Settings'] = $this->Settings($Name);
		$Out['Title'] = @$Out['Settings']['Title'];
		
		return $Out;
	}
	
	public function Overview($Linearize = 1, $RootDir = '')
	{
		$Path = realpath($this->PATH_PAGES . DS . $RootDir);
		if (!$Path)
			return 0;
		
		$Dirh = opendir($Path);
		$Out = array();
		
		while (($File = readdir($Dirh)) !== false) {
			if ($File[0] == '.')
				continue;
			
			$Full = $this->_Urlize($RootDir . DS . $File);
			if (is_dir($Path . DS . $File)) {
				if ($Linearize)
					$Out = array_merge($Out, $this->Overview(1, $Full));
				else
					$Out[$File] = $this->Settings($Full);
			} else {
				if ($Linearize)
					$Out[] = array('Url' => $Full, 'Settings' => $this->Settings($Full));
				else
					$Out[$File] = $this->Overview(0, $Full);
			}
		}
		
		closedir($Dirh);
		
		return $Out;
	}
	
	public function Resolve($Name)
	{
		if ($Name == '' || !$this->_VerifyName($Name))
			return '';
		
		foreach ($this->ValidExtensions as $Ext) {
			$Path = realpath($this->PATH_PAGES . DS . strtolower($Name) . $Ext);
			if ($Path === false)
				continue;
			
			if (strncmp($this->PATH_PAGES, $Path, strlen($this->PATH_PAGES)))
				return '';
			if (is_file($Path))
				return $Path;
		}
		
		return '';
	}
	
	public function Settings($Name)
	{
		$Path = $this->SettingsFile($Name);
		
		if ($Path != '')
			return include($Path);
		else
			return array();
	}
	
	public function SettingsFile($Name, $CheckExistence = true)
	{
		$Path = $this->Resolve($Name);
		
		if ($Path == '')
			return '';
		
		$Settings = dirname($Path) . DS . '.' . basename($Path);
		
		if ($CheckExistence && !is_file($Settings))
			return '';
		
		return $Settings;
	}
	
}

?>
	
	