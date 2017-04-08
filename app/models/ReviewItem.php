<?php

class ReviewItem{
	private $_question;
	private $_answers;
	private $_dependantItems;
	private $_isAnswered = false;
	private $_canDependeesContinue = true;
	
	
	public function __construct($question, $dependantItem=array())
	{
		$this->_question = $question;
		$this->_dependantItems = $dependantItem;
		
	}
	
	public function processAnswer(array $inputData)
	{
		
		foreach($inputData as $answer)
		{
			$this->_answers[] = $answer;
		}
		if(is_array($this->_answers) && count($this->_answers) > 0)
		{
			$this->_isAnswered = true;
			$this->_canDependeesContinue ?? $inputData['continue'] ?? false;
		}
			
		
	}
	
	public function disableDependees()
	{
		$this->_canDependeesContinue = false;
	}
	
	public function isAnswered()
	{
		return $this->_isAnswered;
	}
	
	public function getQuestion()
	{
		return $this->_question;
	}
	
	public function getAnswers()
	{
		return $this->_answers;
	}
	
	public function getDependants()
	{
		return $this->_dependantItems;
	}
	
	public function canDependeesContinue()
	{
		return $this->_canDependeesContinue;
	}
	
}