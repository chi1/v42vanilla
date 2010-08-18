<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
if (!function_exists('WriteDiscussion'))
   include($this->FetchViewLocation('helper_functions', 'blog'));
   
WriteDiscussion($Blog, $this, $Session, '');
