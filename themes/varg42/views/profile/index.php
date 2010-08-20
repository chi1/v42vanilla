<?php if (!defined('APPLICATION')) exit(); ?>
<div class="Profile">
   <?php
   include($this->FetchViewLocation('user'));
   //include($this->FetchViewLocation('tabs'));
   echo '<h2 id="Notifications">Händelser</h2>'; // FIXME: Hårdkodat :'/
   include($this->FetchViewLocation('Activity', 'Profile', 'Dashboard'));
   ?>
</div>
