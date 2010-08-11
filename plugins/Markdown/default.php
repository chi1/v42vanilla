<?php if (!defined('APPLICATION')) exit();

//
// Copyright 2010 Jeff Verkoeyen
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//    http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//

$PluginInfo['Markdown'] = array(
   'Description' => 'Adds Markdown syntax support to the discussions and comments.',
   'Version' => '1.0',
   'RequiredApplications' => NULL, 
   'RequiredTheme' => FALSE, 
   'RequiredPlugins' => FALSE,
   'HasLocale' => FALSE,
   'Author' => "Jeff Verkoeyen",
   'AuthorEmail' => 'jverkoey@gmail.com',
   'AuthorUrl' => 'http://jeffverkoeyen.com'
);
require_once('vendors'.DS.'markdown'.DS.'markdown.php');

class MarkdownPlugin implements Gdn_IPlugin {
  
  // Standard rendering of comments.
  // See applications/vanilla/views/discussion/helper_functions.php
  // Look for BeforeCommentBody.
  public function DiscussionController_BeforeCommentDisplay_Handler($Sender) {
    $Discussion = &$Sender->EventArguments['Discussion'];
    $Comment = &$Sender->EventArguments['Comment'];
    $Discussion->Body = Markdown($Discussion->Body);
    $Comment->Body = Markdown($Comment->Body);
  }

  // AJAX posting of comments
  public function PostController_BeforeCommentBody_Handler($Sender) {
    $this->DiscussionController_BeforeCommentDisplay_Handler($Sender);
  }

  // AJAX preview of new discussions.
  public function DiscussionController_BeforeDiscussionRender_Handler($Sender) {
    if ($Sender->View == 'preview') {
      $Sender->Comment->Body = Markdown($Sender->Comment->Body);
    }
  }

  // AJAX preview of new comments.
  public function PostController_BeforeCommentRender_Handler($Sender) {
    if ($Sender->View == 'preview') {
      $Sender->Comment->Body = Markdown($Sender->Comment->Body);
    }
  }

  public function Setup() {
  }
}
