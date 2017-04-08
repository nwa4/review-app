<?php

/**
* 
*/


class IndexController extends \Phalcon\Mvc\Controller
{
	public function	initialize()
	{
		$this->view->setTemplateAfter('default');
	}
	
	//handles all question retrievals and dispatch to apropriate view.
	//
	public function indexAction()
	{
		$nextQuestion = "complete";
		$additionalError = '';
		$review = new Review(new PhalconSessionTempStore(), ReviewData::$settings);
		
		$nextItem = $review->nextItem();
		
		if($nextItem != null)
			$nextQuestion = $nextItem->getQuestion();
		else
		{
			//saves the completed the questions to the database.
			try
			{
				$succesOrErrors = $review->SaveReviewTo(new ReviewEntity());
				if($succesOrErrors !== true)
					$this->view->error = $succesOrErrors;
					
			}
			catch(Phalcon\Exception $ex)
			{
				$additionalError = '?error='.$ex->getMessage();
			}
		}
			
		
		$this->view->question = $nextQuestion;
		
		$this->view->pick("review/$nextQuestion".$additionalError);
	}
}