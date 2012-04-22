<?php
class Path_Search {
	private $closedset;
	private $openset;
	private $came_from;	
	private $g_score;
	private $h_score;
	private $f_score;
	//private $map_cost;
	private $map_wall;
	private $forbid;
	private $x;
	private $y;
	
	//public function __construct($map_cost, $map_wall) {
	public function __construct($map_wall) {
		//$this->map_cost = $map_cost;
		$this->map_wall = $map_wall;
		$this->x = 12;
		$this->y = 18;
	}
	
	public function A_star($start, $goal, $forbid) {
		$this->forbid = $forbid;
		$this->closedset = NULL;
		$this->openset = NULL;
		$this->came_from = NULL;
		$this->g_score = NULL;
		$this->h_score = NULL;
		$this->f_score = NULL;
		
		if($this->map_wall[$start['x']][$start['y']] == 1 || $this->forbid[$start['x']][$start['y']] == 1) {
			return false;
		}
		$this->openset[$this->val($start)] = $start;
		$this->g_score[$this->val($start)] = 0;
		$this->h_score[$this->val($start)] = $this->heuristic_estimate($start, $goal);
		$this->f_score[$this->val($start)] = $this->h_score[$this->val($start)];
		
		while(isset($this->openset)) {
			$x = $this->openset_lowest_cost();
			if($this->val($x) == $this->val($goal) || !isset($x) || $this->map_wall[$goal['x']][$goal['y']] == 1 || $this->forbid[$goal['x']][$goal['y']] == 1) {
				if(!isset($x) || $this->map_wall[$goal['x']][$goal['y']] == 1 || $this->forbid[$goal['x']][$goal['y']] == 1) {
					$goal = $start;
				}
				if(!isset($this->came_from[$this->val($goal)])) {
					$temp = $this->closedset_lowest_cost();
					$goal['x'] = $temp['x'];
					$goal['y'] = $temp['y'];
				}
				$path = $this->reconstruct_path($goal);
				foreach($path as $key => $value) {
					unset($path[$key]['parent']);
				}
				return $path;
			}
			unset($this->openset[$this->val($x)]);
			$this->closedset[$this->val($x)] = $x;
			
			foreach((array)$this->neighbor_nodes($x, $goal) as $y) {
				if(array_key_exists($this->val($y), $this->closedset)) {
					continue;
				}
				$tentative_g_score = $this->g_score[$this->val($x)] + $this->dist_between($x, $y);

				if(!array_key_exists($this->val($y), $this->openset)) {
					$this->openset[$this->val($y)] = $y;
					$tentative_is_better = true;
				}
				elseif($tentative_g_score < $this->g_score[$this->val($y)]) {
					$tentative_is_better = true;
				}
				else {
					$tentative_is_better = false;
				}

				if($tentative_is_better) {
					$this->came_from[$this->val($y)] = $y;
					$this->g_score[$this->val($y)] = $tentative_g_score;
					$this->h_score[$this->val($y)] = $this->heuristic_estimate($y, $goal);
					$this->f_score[$this->val($y)] = $this->g_score[$this->val($y)] + $this->h_score[$this->val($y)];
 				}
			}
		}
	}

	public function reconstruct_path($target) {
		if(isset($this->came_from[$this->val($target)]['parent'])) {
			$p[0] = $this->came_from[$this->val($target)];
			while(isset($this->came_from[$this->val($target)]) ) {
				$tmp = $this->came_from[$this->val($target)]['parent'];
				array_push($p, (isset($this->came_from[$this->val($tmp)]) ? $this->came_from[$this->val($tmp)] : $tmp));
				$target = $tmp;
			}
			return array_reverse($p);
		}
		else {
			$p[0] = $target;
			return $p;
		}
	}

	//找出closedset f_score中最小的cost
	private function closedset_lowest_cost() {
		foreach((array)$this->closedset as $tmp) {
			if(!isset($min_cost)) {
				$min_cost = $tmp;
			}
			else {
				$min_cost = $this->f_score[$this->val($min_cost)] >= $this->f_score[$this->val($tmp)] ? $tmp : $min_cost;
			}
		}
		return $min_cost;
	}
	
	//找出openset f_score中最小的cost
	private function openset_lowest_cost() {
		foreach((array)$this->openset as $tmp) {
			if(!isset($min_cost)) {
				$min_cost = $tmp;
			}
			else {
				$min_cost = $this->f_score[$this->val($min_cost)] >= $this->f_score[$this->val($tmp)] ? $tmp : $min_cost;
			}
		}
		return $min_cost;
	}
	
	//找出 x 的鄰近點
	private function neighbor_nodes($current, $target) {
		for($i = $current['x']-1;$i <= $current['x']+1;++$i) {
			$tmp['x'] = $i;
			$tmp['y'] = $current['y'];
			if($i >= 0 && $i < $this->x) {
				if(!array_key_exists($this->val($tmp), $this->closedset) && $this->map_wall[$i][$current['y']] != 1 && $this->forbid[$i][$current['y']] != 1) {
					$neighbor[$this->val($tmp)] = $tmp;
					$neighbor[$this->val($tmp)]['parent']['x'] = $current['x'];
					$neighbor[$this->val($tmp)]['parent']['y'] = $current['y'];
				}
			}
		}
		for($j = $current['y']-1;$j <= $current['y']+1;++$j) {
			$tmp['x'] = $current['x'];
			$tmp['y'] = $j;
			if($j >= 0 && $j < $this->y) {
				if(!array_key_exists($this->val($tmp), $this->closedset) && $this->map_wall[$current['x']][$j] != 1 && $this->forbid[$current['x']][$j] != 1) {
					$neighbor[$this->val($tmp)] = $tmp;
					$neighbor[$this->val($tmp)]['parent']['x'] = $current['x'];
					$neighbor[$this->val($tmp)]['parent']['y'] = $current['y'];
				}
			}
		}
		return $neighbor;
	}

	//啟發式估計
	private function heuristic_estimate($current, $target) {
		$xDistance = abs($current['x'] - $target['x']);
		$yDistance = abs($current['y'] - $target['y']);
		if($xDistance > $yDistance ) {
			return 14*$yDistance + 10*($xDistance - $yDistance);
		}
		else {
			return 14*$xDistance + 10*($yDistance - $xDistance);
		}
	}
	
	private function dist_between($xDistance, $yDistance) {
		return (int)sqrt(pow($xDistance, 2) + pow($yDistance, 2));
	}
	
	//計算index value
	private function val($point) {
		return $point['x'] * $this->y + $point['y'];
	}
}
?>
