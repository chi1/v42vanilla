<?php if (!defined('APPLICATION')) exit();
/**
 * Renders a list of users who are taking part in a particular discussion.
 */
class WhosOnlineModule extends Gdn_Module {
   
   protected $_OnlineUsers;
   
   public function __construct(&$Sender = '') {
      parent::__construct($Sender);
   }
   
   public function GetData() {
      $SQL = Gdn::SQL();
     // $this->_OnlineUsers = $SQL
     // insert or update entry into table
     $Session = Gdn::Session();
     
     if($Session->UserID)
        $SQL->Replace('Whosonline', array('UserID' => $Session->UserID, 'Timestamp' => time()), array('UserID' => $Session->UserID));
     
     
     $Frequency = Gdn::Config('WhosOnline.Frequency', 4) * 2;
     $History = time() - $Frequency;
     
     $this->_OnlineUsers = $SQL
        ->Select('u.UserID, u.Name, w.Timestamp')
        ->From('Whosonline w')
        ->Join('User u', 'w.UserID = u.UserID')
        ->Where('w.Timestamp >=', $History)
        ->OrderBy('u.Name')
        ->Get();
        
   }

   public function AssetTarget() {
      //return 'Foot';
      return 'Panel';
   }

   public function ToString() {
      $String = '';
      $Session = Gdn::Session();
      ob_start();
      ?>
      <div id="WhosOnline" class="Box">
         <h4><?php echo Gdn::Translate("Who's Online"); ?> (<?php echo $this->_OnlineUsers->NumRows(); ?>)</h4>
         <ul class="PanelInfo">
            <?php
            if($this->_OnlineUsers->NumRows() > 0) { 
               foreach($this->_OnlineUsers->Result() as $User) {
                  ?>
                  <li>
                     <strong>
                        <?php echo UserAnchor($User); ?>
                     </strong>
                     <?php echo Gdn_Format::Date($User->Timestamp); ?>
                  </li>
                  <?php
               }
            }
            ?>
         </ul>
      </div>
      <?php
      $String = ob_get_contents();
      @ob_end_clean();
      return $String;
   }
}
