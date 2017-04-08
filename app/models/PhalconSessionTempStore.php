<?php


class PhalconSessionTempStore implements ITempStore
{
	private $_containerName = 'temp';
	private $session;
	public function __construct($containerName = "tempItems", $sessionSource = null)
	{
		$this->_containerName = $containerName;
		$this->session = $sessionSource ?? \Phalcon\DI::getDefault()->getSession();
	}
	
	public function store($data)
	{
		$this->session->set($this->_containerName, serialize($data));
	}
	
	public function retrieve()
	{
		return unserialize($this->session->get($this->_containerName));
	}
	
	public function clear()
	{
		$this->session->remove($this->_containerName);
	}
}