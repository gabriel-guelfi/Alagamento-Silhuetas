<?php

class Request{
	private $src;
	private $phpalert;

	public function __construct(){
		$this->src = $_SERVER['DOCUMENT_ROOT'].'/application/';

		include_once $_SERVER['DOCUMENT_ROOT'].'/utils/phpalert/class.phpalert.php';
		$this->phpalert = new PHPAlert('/utils');

		if(isset($_GET['execute'])){
			include_once $this->src.'class.filehandler.php';

			$fh = new FileHandler($_FILES, 'file', 'txt');
			if($fh->errorInfo()->status){
				$this->phpalert->add($fh->errorInfo()->msg, 'failure');
				self::navigateToUrl('/');
			} else{
				include_once $this->src.'class.datathinker.php';

				$dt = new DataThinker();
				$dt->calculate($fh->getFileData());
				echo '<pre>';
				print_r($dt->getResult());
				echo '</pre>';
			}
		}

		else $this->showForm();
	}

	private function showForm(){
		ob_start();

		try{
			include_once $this->src.'views/form.php';
		} catch(Exception $ex){
			echo 'There was an internal server error, please try again later. - ERROR INFO: '.$ex->getMessage() . '. In ' . $ex->getFile() . ' on line ' . $ex->getLine() . '.';
		}

		echo ob_get_clean();

		$this->phpalert->show();
	}

	public static function navigateToUrl($url, $useHeader = false){
		if($useHeader)
			header('Location: '.$url);
		else
			echo '<script type="text/javascript">window.location.href = "'.$url.'";</script>';

		return true;
	}
}