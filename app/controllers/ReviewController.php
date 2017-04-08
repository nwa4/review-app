<?php

/**
* 
*/
class ReviewController extends \Phalcon\Mvc\Controller
{
	private $_review;
	public function	initialize()
	{
		$this->view->setTemplateAfter('default');
	}
	
	
	public function onConstruct()
	{
		$this->_review = new Review(new PhalconSessionTempStore(), ReviewData::$settings);
	}
	
	public function indexAction($question=false)
	{
		$nextQuestion = "complete";
		
		$nextItem = $this->_review->nextItem();
		
		if($nextItem != null)
			$nextQuestion = $nextItem->getQuestion();
			
		
		echo $this->view->partial("review/$nextQuestion");
	}
	
	public function processAction()
	{
		$post = (object)$this->request->getPost();
		$error='';
		try
		{
			switch($post->question) {
				case 'question1':
					if(!isset($post->name) || empty($post->name))
						$error = 'name';
					else
						$this->processReviewPost($post->question, array($post->name));
						
					break;
				case 'question2':
					if(empty($post->day) || empty($post->month) || empty($post->year))
						$error = 'dob';
					elseif(!checkdate($post->month, $post->day, $post->year))
						$error = 'dob-invalid';
					else
						$this->processReviewPost($post->question, array("$post->day-$post->month-$post->year"));				
						
					break;
				case 'question3':
					if(!isset($post->gender))
						$error = 'gender';
					else
						$this->processReviewPost($post->question, array($post->gender));
					break;
				case 'question4':
					if(!isset($post->devInetrest))
						$error = 'dev';
					else
						$this->processReviewPost($post->question, array($post->devInetrest), $post->devInetrest == 'No');
					break; 
				case 'question5':
					if(!isset($post->softDev) && count($post->softDev) < 1)
						$error = 'devList';
					else
						$this->processReviewPost($post->question, $post->softDev, $post->softDev[0] == 'noDev');
					
					break;
				case 'question6':
					if(!$this->request->hasFiles() == true || $this->request->getUploadedFiles()[0]->getSize() > (500*1024))
						$error = 'photo';
					
					else
					{
						$random = new \Phalcon\Security\Random();
						$fileName = $random->uuid() . '.'.$this->request->getUploadedFiles()[0]->getExtension();
						$photoDestination = ReviewData::$photoDir . $fileName;
						
						$this->request->getUploadedFiles()[0]->moveTo($photoDestination);
						
						$this->processReviewPost($post->question, array($fileName));
					}
					break;
				default:
					return;
			} 
		}
		catch(Phalcon\Exception $ex)
		{
			echo $ex->getMessage();
		}
		
		$this->response->redirect("../" .join('/',[  !empty($error) ? "?error=$error" : '']));
	}
	
	public function restartAction()
	{
		$this->_review->restart();
		$this->response->redirect("../");
	}
	
	private function processReviewPost($question, $answer, $disableDependees =false)
	{
		$reviewItem = new ReviewItem($question, array(ReviewData::$settings[$question]['dependants']));
		$reviewItem->processAnswer($answer);
		if($disableDependees)
			$reviewItem->disableDependees();
		$this->_review->addReviewItem($reviewItem);
	}
}