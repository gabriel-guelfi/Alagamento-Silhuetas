<?php

class FloodFinder{
	private $results;
	private $maps;

	public function __construct($data){
		$this->dataResult = [];
		$this->maps = [];
		$this->execute($data);
	}

	private function execute($data){
		foreach($data as $d){
			$map = $this->createMap($d->data);
			$this->maps[] = $map;

			$this->results[] = $this->findFloods($map, $d->data);
		}
	}

	private function createMap($mtx){
		$width = count($mtx);
		$height = max($mtx);
		$map = [];

		for($h = ($height - 1); $h >= 0; $h--){
			for($w = 0; $w < $width; $w++){
				if($mtx[$w]){
					$map[$h][$w] = 1;
					$mtx[$w]--;
				} else{
					$map[$h][$w] = 0;
				}
			}
		}

		unset($width);
		unset($height);
		unset($mtx);

		ksort($map);

		return $map;
	}

	private function findFloods($map, $mtx){
		return $this->lambdaSearch($map, $mtx, (max($mtx) - $mtx[0]), 0);
		
	}

	private function lambdaSearch($map, $mtx, $y, $x, $floods = 0, $return = true){
		$tmp = 0;
		echo'['.$y.', '.$x.']<br>';

		if(isset($map[$y][$x+1])){
			if($map[$y][$x+1]){
				$x++;
				$y = (max($mtx) - $mtx[$x]);
				echo 'muda x';
				$this->lambdaSearch($map, $mtx, $y, $x, $floods, false);
			} else{
				for($_x = $x+1; $_x < count($map[$y]); $_x++){
					if($map[$y][$_x]){
						$y++;
						echo 'muda y';
						$floods += $this->lambdaSearch($map, $mtx, $y, $x, $floods, false);

						break;
					}

					$tmp++;
				}
			}
		}

		if($return)
			return $floods;
		else
			return $tmp;
	}

	public function _get($varname){
		return $this->$varname;
	}
}