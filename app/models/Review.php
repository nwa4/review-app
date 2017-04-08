<?php

class Review
{
	private $_reviewSettings;
	private $_tempStore;
	
	public function __construct(ITempStore $tempStore, array $reviewSettings)
	{
		$this->_tempStore = $tempStore;
		$this->_reviewSettings = $reviewSettings;
		
		$this->_initializeStore();
		
	}
	
	public function getStore()
	{
		return $this->_tempStore;
	}
	
	
	public function nextItem()
	{
		
		foreach($this->_getAllReviewItemsFromStore() as $reviewItem)
		{
			
			if(!$reviewItem->isAnswered())
			{
				$dependants = $reviewItem->getDependants();
				if(count($dependants) > 0)
				{
					$hasDisabledDependee = false;
					foreach($dependants as $question=>$answer)
					{
						if(!$this->_getReviewItemFromStore($question)->canDependeesContinue())
							$hasDisabledDependee = true;
					}
					if(!$hasDisabledDependee)
						return $reviewItem;
				}
				else
					return $reviewItem;
				   
			}
		}
		
		return null;
	}
	
	public function previousItem()
	{
		throw new LogicException('Not implemented');
	}
	
	private function _initializeStore()
	{
		$storedItems = $this->_tempStore->retrieve();
		if(!is_array($storedItems) || count($storedItems) < 1)
		{
			$store = array();
			foreach($this->_reviewSettings as $key=>$item)
			{
				$store[$key] = array('answers'=>array(), 'continue'=>true);
			}
			$this->_tempStore->store($store);
		}
			
	}
	
	private function _getAllReviewItemsFromStore()
	{
		$reviewItems = array();
		$storedData = $this->_tempStore->retrieve();
		foreach($storedData as $question=>$data)
		{
			$reviewItem = $this->_buildReviewItemObject($question);
			
			$answers = $data['answers'];
			
			if(!$data['continue'])
				$reviewItem->disableDependees();
			
			var_dump($reviewItem);
			
			$reviewItem->processAnswer($answers);
			$reviewItems[] = $reviewItem;
		}
		
		return $reviewItems;
	}
	
	private function _getReviewItemFromStore($question)
	{
		foreach($this->_getAllReviewItemsFromStore() as $reviewItem)
		{
			if($reviewItem->getQuestion() == $question)
				return $reviewItem;
		}
		return null;
	}
	
	private function _buildReviewItemObject($question)
	{
		foreach($this->_reviewSettings as $key=>$value)
		{
			if($key == $question)
			{
				return new ReviewItem($key, $value['dependants']);
			}
		}
		return null;
	}
	
	public function addReviewItem(ReviewItem $reviewItem)
	{
		$storedReviewItems = $this->_tempStore->retrieve();
		
		$storedReviewItems[$reviewItem->getQuestion()]['answers'] = $reviewItem->getAnswers();
		$storedReviewItems[$reviewItem->getQuestion()]['continue'] = $reviewItem->canDependeesContinue();
		$this->_tempStore->store($storedReviewItems);
		
	}
	
	public function restart()
	{
		$this->_tempStore->clear();
	}
	
	public function saveReviewTo(\Phalcon\Mvc\Model $entity)
	{
		$reviewItems = $this->_getAllReviewItemsFromStore();
		
		if(count($reviewItems) < 1 || $this->nextItem() != null )
			return;
		
		if($entity == null)
			return;
		
		
				
		$entity->name = implode(',', $reviewItems[0]->getAnswers());
		$entity->dob = date('Y-m-d', strtotime(implode('-', $reviewItems[1]->getAnswers())));
		$entity->gender = implode(',', $reviewItems[2]->getAnswers());
		$entity->dev_interest = implode(',', $reviewItems[3]->getAnswers());
		$entity->dev_languages = implode(',', $reviewItems[4]->getAnswers());
		$entity->photo = implode(',', $reviewItems[5]->getAnswers());
		
		
		if ($entity->save() === false)
		{
			$errorMessages = '';
			foreach($entity->getMessages() as $message)
			{
				$errorMessages .= $message.'\n';
			}
			return $errorMessages;
		}
		
		return true;
	}
}