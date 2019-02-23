<?php

/*////////////////////////////////////////////////////////////////////////////////////
//This singleton class is responsible for receive and treat requests to application.//
*/////////////////////////////////////////////////////////////////////////////////////

class Request{

	// Holds application's root path
	private $src;
	// Holds an instance of utility PHPAlert's class
	private $phpalert;
	// Holds an instance of this class. (Singleton pattern)
	private static $instance;

	/*
	// Instantiates this class, if it isn't, saving it on $instance and return this instance. (Singleton pattern)
	*/
	public static function getInstance(){
		if(self::$instance === null){
			self::$instance = new self;
		}

		return self::$instance;
	}

	/*
	// Initiates execution starting up a session and setting up some properties, then decides request's flow.
	*/
	private function __construct(){
		session_start();
		$this->src = $_SERVER['DOCUMENT_ROOT'].'/application/';

		include_once $_SERVER['DOCUMENT_ROOT'].'/utils/phpalert/class.phpalert.php';
		$this->phpalert = new PHPAlert('/utils');



		if(isset($_GET['execute'])){
			$this->execute();
		}
		else{
			$this->showView('top');
			$this->showView('home');
			$this->showView('footer');
		} 
	}
	/*
	// (Singleton pattern)
	*/
	private function __clone(){
		
	}

	/*
	// (Singleton pattern)
	*/
	private function __wakeup(){

	}

	/*
	// Renders a view file on the screen.
	*/
	public function showView($viewname){
		ob_start();

		try{
			include_once $this->src.'views/view.'.$viewname.'.php';
		} catch(Exception $ex){
			echo 'There was an internal server error, please try again later. - ERROR INFO: '.$ex->getMessage() . '. In ' . $ex->getFile() . ' on line ' . $ex->getLine() . '.';
		}

		echo ob_get_clean();

		$this->phpalert->show();
	}

	/* 
	// This method sends input file to FileHandler class, then insert its result to FindFlood class, that calculates the portion of flood in each case specified at the input file.
	// After that, sends output, based on user's choice.
	*/
	private function execute(){
		include_once $this->src.'class.filehandler.php';

		$fh = new FileHandler($_FILES, 'file', 'txt');
		if($fh->errorInfo()->status){
			$this->phpalert->add($fh->errorInfo()->msg, 'failure');
			self::navigateToUrl('/');
		} else{
			include_once $this->src.'class.floodfinder.php';

			$ff = new FloodFinder($fh->getFileData());
			switch($_POST['output-option']){
				case '1':
				if(!$fh->createFile($ff->_get('results'))){
					$this->phpalert->add("Failure! The response file could not be created.", 'failure');
					self::navigateToUrl('/');
				}
				break;

				case '2':
				$date = date('m-d-Y h:i:s');
				if($fh->createFile($ff->_get('results'), $this->src.'responses/', 'Response - '.$date.'.txt')){
					$this->phpalert->add('Success! Response file path: '.$this->src.'responses/Response - '.$date.'.txt', 'success');
				} else{
					$this->phpalert->add("Failure! The response file could not be created.", 'failure');
				}

				self::navigateToUrl('/');
				break;
				case '3':
				$_SESSION['response-data'] = $ff->_get('results');
				$this->phpalert->add('Success! Response for input dataset is shown below.', 'success');
				self::navigateToUrl('/');
				break;
			}
		}
	}

	/*
	// This global method navigates client to a specified URL. It can be called from anywhere within this application.
	*/
	public static function navigateToUrl($url, $useHeader = false){
		if($useHeader)
			header('Location: '.$url);
		else
			echo '<script type="text/javascript">window.location.href = "'.$url.'";</script>';

		return true;
	}
}