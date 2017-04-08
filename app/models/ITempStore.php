<?php

interface ITempStore
{
	public function store($data);
	public function retrieve();
	public function clear();
}