<?php

class Request{
	private $src;
	private $phpalert;
	private static $instance;

	public static function getInstance(){
		if(self::$instance === null){
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct(){
		$this->src = $_SERVER['DOCUMENT_ROOT'].'/application/';

		include_once $_SERVER['DOCUMENT_ROOT'].'/utils/phpalert/class.phpalert.php';
		$this->phpalert = new PHPAlert('/utils');

		if(isset($_GET['execute'])){
			$this->execute();
		}

		else $this->showForm();
	}

	private function __clone(){
		
	}

	private function __wakeup(){

	}

	private function showForm(){
		ob_start();

		try{
			include_once $this->src.'views/view.form.php';
		} catch(Exception $ex){
			echo 'There was an internal server error, please try again later. - ERROR INFO: '.$ex->getMessage() . '. In ' . $ex->getFile() . ' on line ' . $ex->getLine() . '.';
		}

		echo ob_get_clean();

		$this->phpalert->show();
	}

	private function execute(){
		include_once $this->src.'class.filehandler.php';

		$fh = new FileHandler($_FILES, 'file', 'txt');
		if($fh->errorInfo()->status){
			$this->phpalert->add($fh->errorInfo()->msg, 'failure');
			self::navigateToUrl('/');
		} else{
			include_once $this->src.'class.floodfinder.php';

			$ff = new FloodFinder($fh->getFileData());
			if(isset($_POST['directdownload'])){
				if(!$fh->createFile($ff->_get('results'))){
					$this->phpalert->add("Failure! The response file could not be created.", 'failure');
					self::navigateToUrl('/');
				}
			}else{
				$date = date('m-d-Y h:i:s');
				if($fh->createFile($ff->_get('results'), $this->src.'responses/', 'Response - '.$date.'.txt')){
					$this->phpalert->add('Success! Response file path: '.$this->src.'responses/Response - '.$date.'.txt', 'success');
				} else{
					$this->phpalert->add("Failure! The response file could not be created.", 'failure');
				}
				
				self::navigateToUrl('/');
			}
		}
	}

	public static function navigateToUrl($url, $useHeader = false){
		if($useHeader)
			header('Location: '.$url);
		else
			echo '<script type="text/javascript">window.location.href = "'.$url.'";</script>';

		return true;
	}
}