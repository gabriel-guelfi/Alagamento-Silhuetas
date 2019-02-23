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

			$initY = (max($d->data) - $d->data[0]);
			$this->results[] = $this->findFloods($map, $d->data, $initY, 0);
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

	private function findFloods($map, $mtx, $y, $x){
		$height = max($mtx);

		$floods = 0;
		for($y; $y < $height; $y++){
			if(isset($map[$y][$x+1])){
				if($map[$y][$x+1]){
					$y = ($height - $mtx[$x+1]) - 1;
					$x++;
					continue;
				}

				$floods += $this->countRowFlood(($x+1), $map[$y]);
			}
		}

		return $floods;
		
	}

	private function countRowFlood($init, $mapRow){
		$tmp = 0;
		for($i = $init; $i < count($mapRow); $i++){
			if($mapRow[$i]){
				return $tmp;
			}
			$tmp++;
		}

		return 0;
	}

	public function _get($varname){
		return $this->$varname;
	}
}