<?php if (!defined('APPLICATION')) exit();
// If the photo contains an http://, it is just an icon, don't show it here.
/*if ($this->User->Photo != '' && strtolower(substr($this->User->Photo, 0, 7)) != 'http://') {
   ?>
   <div class="Photo">
      <?php echo Img('uploads/'.ChangeBasename($this->User->Photo, 'p%s')); ?>
   </div>
   <?php
}*/

// FIXME: Det här är en av de fulare utkommenteringar jag gjort, rly. Någon får gärna scouta efter en settings som tar bort det här på ett bättre sätt :)
