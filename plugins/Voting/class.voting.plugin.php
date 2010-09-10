<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

// Define the plugin:
$PluginInfo['Voting'] = array(
   'Name' => 'Voting',
   'Description' => 'Allows users to vote on comments and discussions.',
   'Version' => '1.0.1b',
   'Author' => "Mark O'Sullivan",
   'AuthorEmail' => 'mark@vanillaforums.com',
   'AuthorUrl' => 'http://markosullivan.ca',
   'RequiredApplications' => array('Vanilla' => '2.0.3')
);

class VotingPlugin extends Gdn_Plugin {

	public function PostController_Render_Before($Sender) {
		$Sender->AddCSSFile('plugins/Voting/design/voting.css');
	}

	/**
	 * Insert the voting html on comments in a discussion.
	 */
	public function PostController_BeforeCommentMeta_Handler($Sender) {
		$this->DiscussionController_BeforeCommentMeta_Handler($Sender);
	}
	public function DiscussionController_BeforeCommentMeta_Handler($Sender) {
		if ($Sender->EventArguments['Type'] == 'Comment') {
	    	echo '<span class="Votes">';
				$Session = Gdn::Session();
				$Object = GetValue('Object', $Sender->EventArguments);
				$ID = $Object->CommentID;
				$CssClass = '';
				$VoteUpUrl = '/discussion/votecomment/'.$ID.'/voteup/'.$Session->TransientKey().'/';
				$VoteDownUrl = '/discussion/votecomment/'.$ID.'/votedown/'.$Session->TransientKey().'/';
				if (!$Session->IsValid()) {
					$VoteUpUrl = Gdn::Authenticator()->SignInUrl($Sender->SelfUrl);
					$VoteDownUrl = $VoteUpUrl;
					$CssClass = ' SignInPopup';
				}
				echo Anchor(Wrap(Wrap('Vote Down', 'i'), 'i', array('class' => 'ArrowSprite SpriteDown', 'rel' => 'nofollow')), $VoteDownUrl, 'VoteDown'.$CssClass);
				echo Wrap(StringIsNullOrEmpty($Object->Score) ? '0' : $Object->Score);
				echo Anchor(Wrap(Wrap('Vote Up', 'i'), 'i', array('class' => 'ArrowSprite SpriteUp', 'rel' => 'nofollow')), $VoteUpUrl, 'VoteUp'.$CssClass);
			echo '</span>';
		}
	}


	/* two functions to hide unwanted commments */
	public function DiscussionController_BeforeCommentDisplay_Handler($Sender) {
		if ($Sender->EventArguments['Type'] == 'Comment') {
//			$Session = Gdn::Session();
			$Object = GetValue('Object', $Sender->EventArguments);
			$CommentID = $Object->CommentID;
			$score = StringIsNullOrEmpty($Object->Score) ? 0 : $Object->Score;
			if ($score < 0) {
				echo '<li>' . "\n";
				echo '<ol class="hiddencomment">' . "\n";
				echo '<li class="hiddennotice">';
				echo T('Comment by ');
				echo UserAnchor(UserBuilder($Object, 'Insert'));
				echo T(' with score ');
				echo $score;
				echo T(' hidden. <a class="showhidecomment" href="#">Show/Hide</a></li>') . "\n";
			}
		}
	}

	public function DiscussionController_AfterComment_Handler($Sender) {
		if ($Sender->EventArguments['Type'] == 'Comment') {
			$Object = GetValue('Object', $Sender->EventArguments);
			$score = StringIsNullOrEmpty($Object->Score) ? 0 : $Object->Score;
			if ($score < 0) {
				echo '</ol>' . "\n";
				echo '</li>' . "\n";
			}
		}
	}

   /**
	 * Add the vote.js file to discussions page, and handle sorting of answers.
	 */
   public function DiscussionController_Render_Before($Sender) {
		$Sender->AddCSSFile('plugins/Voting/design/voting.css');
		$Sender->AddJSFile('plugins/Voting/voting.js');
   }
   
   
   /**
    * Increment/decrement comment scores
    */
   public function DiscussionController_VoteComment_Create($Sender) {
      $CommentID = GetValue(0, $Sender->RequestArgs, 0);
      $VoteType = GetValue(1, $Sender->RequestArgs);
      $TransientKey = GetValue(2, $Sender->RequestArgs);
      $Session = Gdn::Session();
      $FinalVote = 0;
      $Total = 0;
      if ($Session->IsValid() && $Session->ValidateTransientKey($TransientKey) && $CommentID > 0) {
         $CommentModel = new CommentModel();
         $OldUserVote = $CommentModel->GetUserScore($CommentID, $Session->UserID);
         $NewUserVote = $VoteType == 'voteup' ? 1 : -1;
         $FinalVote = intval($OldUserVote) + intval($NewUserVote);
        // Only allow users to vote up or down by 1.
         $AllowVote = $FinalVote > -2 && $FinalVote < 2;
         
         if ($AllowVote) {
            $Total = $CommentModel->SetUserScore($CommentID, $Session->UserID, $FinalVote);
    	    $Sender->DeliveryType(DELIVERY_TYPE_BOOL);
  		    $Sender->SetJson('TotalScore', $Total); 	     $Sender->SetJson('FinalVote', $FinalVote);
     		$Sender->Render();
		 }
      }
   }


   /**
    * Grab the score field whenever the discussions are queried.
    */
/*   public function DiscussionModel_AfterDiscussionSummaryQuery_Handler(&$Sender) {
      $Sender->SQL->Select('d.Score')
         ->Select('iu.Email', '', 'FirstEmail')
         ->Select('lcu.Email', '', 'LastEmail');
   }
*/


	/**
	 * Add JS & CSS to the page.
	 */
	public function DiscussionsController_Render_Before($Sender) {
		$this->AddJsCss($Sender);
	}
   public function CategoriesController_Render_Before($Sender) {
      $this->AddJsCss($Sender);
   }

   public function AddJsCss($Sender) {
      $Sender->AddCSSFile('plugins/Voting/design/voting.css');
		$Sender->AddJSFile('plugins/Voting/voting.js');
   }


   public function Setup() {

   }

}
