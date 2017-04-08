<?php

class ReviewEntity extends \Phalcon\Mvc\Model
{
	public function initialize()
    {
        $this->setSource("review");
    }
}