<?php
   public $Uses = array('Database', 'DiscussionModel', 'Form');
   
   $test = $DiscussionModel->Get('0', '30', array('d.CategoryID' => '1'));
   
   printf($test);
?>
