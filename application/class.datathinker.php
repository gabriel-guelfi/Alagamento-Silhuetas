<?php

class DataThinker{
	private $dataResult;

	public function __construct(){

	}

	public function calculate($data){
		$this->dataResult = $data;
	}

	public function getResult(){
		return $this->dataResult;
	}
}