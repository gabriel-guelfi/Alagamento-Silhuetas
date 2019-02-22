<?php

class FileHandler{
	private $errorStatus;
	private $errorMsg;
	private $fileResult;

	public function __construct($filesdata, $index, $allowedExt){
		if(is_string($allowedExt))
			$allowedExt = [$allowedExt];

		$this->errorStatus = false;

		if($filesdata[$index]['error'] || empty($filesdata[$index])){
			$this->errorStatus = true;
			$this->errorMsg = "There was a problem uploading the file.";
		} elseif(!in_array(pathinfo($filesdata[$index]['name'], PATHINFO_EXTENSION), $allowedExt)){
			$this->errorStatus = true;
			$this->errorMsg = "File extension not allowed.";
		} else {
			$this->formatFileData($filesdata[$index]);
		}
	}

	private function formatFileData($filedata){
		$fdata = file($filedata['tmp_name'], FILE_IGNORE_NEW_LINES);
		$data = [];
		for($i = 0; $i < count($fdata); $i++){
			if(!$i || $fdata[$i] == "")
				continue;

			$next = ($fdata[$i+1] == "" ? $i+2 : $i+1);

			$data[] = (object) [
				'length' => $fdata[$i],
				'data' => explode(' ', $fdata[$next])
			];
			$i = $next;
		}

		unset($fdata);

		$this->fileResult = $data;
	}

	public function getFileData(){
		return $this->fileResult;
	}

	public function createFile($data, $save = false, $filePath = null){


	}

	public function errorInfo(){
		return (object) [
			'status' => $this->errorStatus,
			'msg' => $this->errorMsg
		];
	}

	public static function lineBreak(){
		if(PATH_SEPARATOR == ":")
			return "\r\n";
		else return "\n";
	}
}