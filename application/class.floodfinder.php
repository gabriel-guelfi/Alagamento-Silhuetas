<?php

/*//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//This class receives a dataset, generates a map based on it, then calculates floodables areas, based on this map.//
*///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class FloodFinder{
	// Holds total floodables areas. Classe's output.
	private $results;
	// Holds a list of generated maps.
	private $maps;

	/*
	// Instantiates this class, setting up its properties, then starts execution, passing dataset received.
	*/
	public function __construct($data){
		$this->dataResult = [];
		$this->maps = [];
		$this->execute($data);
	}

	/*
	// Executes calculation creating a map, based on matrix dataset, then finds floodable areas in each map created. Saves results on a list within a property.
	*/
	private function execute($data){
		foreach($data as $d){
			$map = $this->createMap($d->data);
			$this->maps[] = $map;

			$initY = (max($d->data) - $d->data[0]);
			$this->results[] = $this->findFloods($map, $d->data, $initY, 0);
		}
	}

	/*
	// Receive a matrix dataset and generates a map, based on it.
	*/
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

	/*
	// Receives a map, a dataset and a start position. Begins navigating in map, finding each floodable area and count it.
	*/
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

	/*
	// Navigates through a map slice, counting any empty spaces between two barriers. Each space counted is a floodable area. Returns total spaces counted.
	*/
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

	/*
	// Returns a property value.
	*/
	public function _get($varname){
		return $this->$varname;
	}
}