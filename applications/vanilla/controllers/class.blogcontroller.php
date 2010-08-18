<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

/**
 * Blog Controller - handles displaying Blog in all their forms.
 */
class BlogController extends VanillaController {
   
   public $Uses = array('Database', 'DiscussionModel', 'Form');
   
   /**
    * A boolean value indicating if discussion options should be displayed when
    * rendering the discussion view.
    *
    * @var boolean
    */
   public $ShowOptions;
   public $Category;
   public $CategoryID;
   
   public function Index($Offset = '0') {
   list($Offset, $Limit) = OffsetLimit($Offset, Gdn::Config('Vanilla.Discussions.PerPage', 30));
      
      $Category = '1';

      $this->SetData('Category', $Category, TRUE);
      
      if ($Category === FALSE)
         return $this->All();
      
      $this->AddCssFile('vanilla.css');
      $this->Menu->HighlightRoute('/discussions');      
      if ($this->Head) {
         $this->Head->Title($Category->Name);
         $this->AddJsFile('discussions.js');
         $this->AddJsFile('bookmark.js');
			$this->AddJsFile('jquery.menu.js');
         $this->AddJsFile('options.js');
         $this->AddJsFile('jquery.gardenmorepager.js');
         $this->Head->AddRss($this->SelfUrl.'/feed.rss', $this->Head->Title());
      }
      if (!is_numeric($Offset) || $Offset < 0)
         $Offset = 0;
         
      
      $this->SetData('CategoryID', $this->Category->CategoryID, TRUE);

      // Add Modules
      $this->AddModule('NewDiscussionModule');
      $this->AddModule('BlogModule');
      $BookmarkedModule = new BookmarkedModule($this);
      $BookmarkedModule->GetData();
      $this->AddModule($BookmarkedModule);
   
      $DiscussionModel = new DiscussionModel();
      $Wheres = array('d.CategoryID' => $this->CategoryID);
      
      $this->Permission('Vanilla.Discussions.View', TRUE, 'Category', $this->CategoryID);
      $CountDiscussions = $DiscussionModel->GetCount($Wheres);
      $this->SetData('CountDiscussions', $CountDiscussions);
      $AnnounceData = $Offset == 0 ? $DiscussionModel->GetAnnouncements($Wheres) : new Gdn_DataSet();
      $this->SetData('AnnounceData', $AnnounceData, TRUE   );
      $this->SetData('DiscussionData', $DiscussionModel->Get($Offset, $Limit, $Wheres), TRUE);

      // Build a pager
      $PagerFactory = new Gdn_PagerFactory();
      $this->Pager = $PagerFactory->GetPager('Pager', $this);
      $this->Pager->ClientID = 'Pager';
      $this->Pager->Configure(
         $Offset,
         $Limit,
         $CountDiscussions,
         'blog/'.$CategoryIdentifier.'/%1$s'
      );

      // Set the canonical Url.
      $this->CanonicalUrl(Url(ConcatSep('/', 'blog', PageNumber($Offset, $Limit, TRUE)), TRUE));
      
      // Change the controller name so that it knows to grab the discussion views
      $this->ControllerName = 'BlogController';
      // Pick up the discussions class
      $this->CssClass = 'Blog';
      
      // Deliver json data if necessary
      if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
         $this->SetJson('LessRow', $this->Pager->ToString('less'));
         $this->SetJson('MoreRow', $this->Pager->ToString('more'));
         $this->View = 'blog';
      }
      
      // Render the controller
      $this->Render();
   }
   
   public function Initialize() {
      parent::Initialize();
      $this->ShowOptions = TRUE;
      $this->Menu->HighlightRoute('/Blog');
      $this->AddCssFile('vanilla.css');
		$this->AddJsFile('bookmark.js');
		$this->AddJsFile('Blog.js');
		$this->AddJsFile('jquery.menu.js');
		$this->AddJsFile('options.js');
      $this->AddJsFile('jquery.gardenmorepager.js');
		$this->FireEvent('AfterInitialize');
   }
   
   public function Bookmarked($Offset = '0') {
      $this->Permission('Garden.SignIn.Allow');

      if (!is_numeric($Offset) || $Offset < 0)
         $Offset = 0;
      
      $Session = Gdn::Session();
      $Limit = Gdn::Config('Vanilla.Blog.PerPage', 30);
      $Wheres = array('w.Bookmarked' => '1', 'w.UserID' => $Session->UserID);
      $DiscussionModel = new DiscussionModel();
      $this->DiscussionData = $DiscussionModel->Get($Offset, $Limit, $Wheres);
      $this->SetData('Blog', $this->DiscussionData);
      $CountBlog = $DiscussionModel->GetCount($Wheres);
      $this->SetData('CountBlog', $CountBlog);
      $this->Category = FALSE;
      
      // Build a pager
      $PagerFactory = new Gdn_PagerFactory();
      $this->Pager = $PagerFactory->GetPager('MorePager', $this);
      $this->Pager->MoreCode = 'More Blog';
      $this->Pager->LessCode = 'Newer Blog';
      $this->Pager->ClientID = 'Pager';
      $this->Pager->Configure(
         $Offset,
         $Limit,
         $CountBlog,
         'Blog/bookmarked/%1$s'
      );
      
      // Deliver json data if necessary
      if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
         $this->SetJson('LessRow', $this->Pager->ToString('less'));
         $this->SetJson('MoreRow', $this->Pager->ToString('more'));
         $this->View = 'Blog';
      }
      
      // Add Modules
      $this->AddModule('NewDiscussionModule');
      $this->AddModule('BlogModule');
      
      $this->Render();
   }
   
   public function Mine($Offset = '0') {
      $this->Permission('Garden.SignIn.Allow');

      if (!is_numeric($Offset) || $Offset < 0)
         $Offset = 0;
      
      $Limit = Gdn::Config('Vanilla.Blog.PerPage', 30);
      $Session = Gdn::Session();
      $Wheres = array('d.InsertUserID' => $Session->UserID);
      $DiscussionModel = new DiscussionModel();
      $this->DiscussionData = $DiscussionModel->Get($Offset, $Limit, $Wheres);
      $this->SetData('Blog', $this->DiscussionData);
      $CountBlog = $this->SetData('CountBlog', $DiscussionModel->GetCount($Wheres));
      
      // Build a pager
      $PagerFactory = new Gdn_PagerFactory();
      $this->Pager = $PagerFactory->GetPager('MorePager', $this);
      $this->Pager->MoreCode = 'More Blog';
      $this->Pager->LessCode = 'Newer Blog';
      $this->Pager->ClientID = 'Pager';
      $this->Pager->Configure(
         $Offset,
         $Limit,
         $CountBlog,
         'Blog/mine/%1$s'
      );
      
      // Deliver json data if necessary
      if ($this->_DeliveryType != DELIVERY_TYPE_ALL) {
         $this->SetJson('LessRow', $this->Pager->ToString('less'));
         $this->SetJson('MoreRow', $this->Pager->ToString('more'));
         $this->View = 'Blog';
      }
      
      // Add Modules
      $this->AddModule('NewDiscussionModule');
      $this->AddModule('BlogModule');
      $BookmarkedModule = new BookmarkedModule($this);
      $BookmarkedModule->GetData();
      $this->AddModule($BookmarkedModule);
      
      // Render the controller
      $this->Render();
   }
}
