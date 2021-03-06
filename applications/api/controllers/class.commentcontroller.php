<?php if (!defined('APPLICATION')) exit();

class CommentController extends APIController 
{
   public $Uses = array('Form', 'Database', 'CategoryModel', 'DiscussionModel', 'CommentModel');

	public function Index()
	{
		$this->Render();
   }

   public function Add()
	{
      $Session = Gdn::Session();
		$Errors = array();

      // Set the model on the form.
      $this->Form->SetModel($this->CommentModel);

      if($this->Form->AuthenticatedPostBack() === TRUE) 
		{
         $FormValues = $this->Form->FormValues();

         // Check category permissions
			if($Session->CheckPermission('Vanilla.Comments.Add', $FormValues['CategoryID']))
			{
				$CommentID = $this->CommentModel->Save($FormValues);
				$this->SetJSON("CommentID", $CommentID);
			}
			else
			   $Errors[] = 'You do not have permission to add comments to this discussion';
		}
		else
			$Errors[] = 'You do not have credentials to post as this user';

		// Return the form errors
		if(count($Errors) > 0)
			$this->SetJSON("Errors", $Errors);

		$this->Render();
	}

}

?>
