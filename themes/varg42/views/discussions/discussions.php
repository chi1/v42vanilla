<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
if (!function_exists('WriteDiscussion'))
   include($this->FetchViewLocation('helper_functions', 'discussions', 'vanilla'));

	?>
    <h2 class="discussion">Annoucements</h2>
<div id="Content2">
<?php $Alt = '';
if (property_exists($this, 'AnnounceData') && is_object($this->AnnounceData)) {
	foreach ($this->AnnounceData->Result() as $Discussion) {
		$Alt = $Alt == ' Alt' ? '' : ' Alt';
		WriteDiscussion($Discussion, $this, $Session, $Alt);
	}
}
?>
</div>
<h2 class="discussion">Discussions</h2>
<div id="Content2">
<?php
$Alt = '';
foreach ($this->DiscussionData->Result() as $Discussion) {
   $Alt = $Alt == ' Alt' ? '' : ' Alt';
   WriteDiscussion($Discussion, $this, $Session, $Alt);
}
?>
</div>