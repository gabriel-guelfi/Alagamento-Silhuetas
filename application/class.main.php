<?php

class Main{
	private $src;
	private $phpalert;

	public function __construct(){
		$this->src = $_SERVER['DOCUMENT_ROOT'].'/application/';

		include $_SERVER['DOCUMENT_ROOT'].'/utils/phpalert/class.phpalert.php';
		$this->phpalert = new PHPAlert('/utils');

		if(isset($_GET['action'])){
			$action = $_GET['action'];
			$this->$action();
		}

		else $this->index();
	}

	private function index(){
		ob_start();

		try{
			include $this->src.'views/form.php';
		} catch(Exception $ex){
			echo 'There was an internal server error, please try again later. - ERROR INFO: '.$ex->getMessage() . '. In ' . $ex->getFile() . ' on line ' . $ex->getLine() . '.';
		}

		echo ob_get_clean();

		$this->phpalert->show();
	}

	private function upload(){
		if($_FILES['file']['error'] || empty($_FILES['file'])){
			$this->phpalert->add("There was a problem uploading the file.", 'failure');
			self::navigateToUrl('/');
			return false;
		}

		$fdata = file($_FILES['file']['tmp_name'], FILE_IGNORE_NEW_LINES);
		$data = [];
		for($i = 0; $i < count($fdata); $i++){
			if(!$i || $fdata[$i] == "")
				continue;

			$next = ($fdata[$i+1] == "" ? $i+2 : $i+1);

			$data[] = (object) [
				'length' => $fdata[$i],
				'struct' => explode(' ', $fdata[$next])
			];
			$i = $next;
		}

		unset($fdata);

		$this->process($data);
	}

	private function process($data){
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}

	public static function navigateToUrl($url, $useHeader = false){
		if($useHeader)
			header('Location: '.$url);
		else
			echo '<script type="text/javascript">window.location.href = "'.$url.'";</script>';

		return true;
	}

	public static function lineBreak(){
		if(PATH_SEPARATOR == ":")
			return "\r\n";
		else return "\n";
	}
}