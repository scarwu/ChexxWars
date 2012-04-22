<?php
define('MAP_X', 12);
define('MAP_Y', 18);

class Path_Search {
	private $closedset;
	private $openset;
	private $came_from;	
	private $map_cost;
	private $map_wall;
	private $g_score;
	private $h_score;
	private $f_score;
	private $heuristic;
	private $way;
	
	public function __construct($map_cost, $map_wall, $h, $w) {
		$this->map_cost = $map_cost;
		$this->map_wall = $map_wall;
		$this->heuristic = $h; 
		$this->way = $w;
	}
	
	public function A_star($start, $goal) {
		if($this->map_wall[$start['x']][$start['y']] == 1) {
			return false;
		}
		$this->openset[$this->val($start)] = $start;
		$this->g_score[$this->val($start)] = 0;
		$this->h_score[$this->val($start)] = $this->heuristic_estimate($start, $goal);
		$this->f_score[$this->val($start)] = $this->h_score[$this->val($start)];
		
		while(isset($this->openset)) {
			$x = $this->lowest_cost($this->openset, $goal);
			if($this->val($x) == $this->val($goal) || !isset($x)  || $this->map_wall[$goal['x']][$goal['y']] == 1) {
				if(!isset($x) || $this->map_wall[$goal['x']][$goal['y']] == 1) {
					$goal = $start;
				}
				$path['shortpath'] = $this->reconstruct_path($goal);
				$path['closedset'] = $this->closedset;
				$path['openset'] = $this->openset;
				$path['g_score'] = $this->g_score;
				$path['h_score'] = $this->h_score;
				$path['f_score'] = $this->f_score;
				return $path;
			}
			unset($this->openset[$this->val($x)]);
			$this->closedset[$this->val($x)] = $x;
			
			foreach((array)$this->neighbor_nodes($x, $goal) as $y) {
				if(array_key_exists($this->val($y), $this->closedset)) {
					continue;
				}
				$tentative_g_score = $this->g_score[$this->val($x)] + $this->dist_between($x, $y) + $this->map_cost[$y['x']][$y['y']];

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
			while( isset($this->came_from[$this->val($target)]) ) {
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

	//找出f_score中最小的cost
	private function lowest_cost() {
		foreach((array)$this->openset as $tmp) {
			if(!isset($min_cost_point)) {
				$min_cost_point = $tmp;
			}
			else {
				$min_cost_point = $this->f_score[$this->val($min_cost_point)] >= $this->f_score[$this->val($tmp)] ? $tmp : $min_cost_point ;
			}
		}
		return $min_cost_point;
	}
	
	//找出 x 的鄰近點
	private function neighbor_nodes($current, $target) {
		if($this->way == 0) {
			for($i = $current['x']-1;$i <= $current['x']+1;++$i) {
				for($j = $current['y']-1;$j <= $current['y']+1;++$j) {
					$tmp['x'] = $i;
					$tmp['y'] = $j;
					if(($i >= 0 && $i < MAP_X) && ($j >= 0 && $j < MAP_Y)) {
						if(!array_key_exists($this->val($tmp), $this->closedset) && $this->map_wall[$i][$j] != 1) {
							$neighbor[$this->val($tmp)] = $tmp;
							$neighbor[$this->val($tmp)]['parent']['x'] = $current['x'];
							$neighbor[$this->val($tmp)]['parent']['y'] = $current['y'];
						}
					}
				}
			}
		}
		elseif($this->way == 1) {
			for($i = $current['x']-1;$i <= $current['x']+1;++$i) {
				$tmp['x'] = $i;
				$tmp['y'] = $current['y'];
				if($i >= 0 && $i < MAP_X) {
					if(!array_key_exists($this->val($tmp), $this->closedset) && $this->map_wall[$i][$current['y']] != 1) {
						$neighbor[$this->val($tmp)] = $tmp;
						$neighbor[$this->val($tmp)]['parent']['x'] = $current['x'];
						$neighbor[$this->val($tmp)]['parent']['y'] = $current['y'];
					}
				}
			}
			for($j = $current['y']-1;$j <= $current['y']+1;++$j) {
				$tmp['x'] = $current['x'];
				$tmp['y'] = $j;
				if($j >= 0 && $j < MAP_Y) {
					if(!array_key_exists($this->val($tmp), $this->closedset) && $this->map_wall[$current['x']][$j] != 1) {
						$neighbor[$this->val($tmp)] = $tmp;
						$neighbor[$this->val($tmp)]['parent']['x'] = $current['x'];
						$neighbor[$this->val($tmp)]['parent']['y'] = $current['y'];
					}
				}
			}
		}
		return $neighbor;
	}

	//啟發式估計
	private function heuristic_estimate($current, $target) {
		$xDistance = abs($current['x'] - $target['x']);
		$yDistance = abs($current['y'] - $target['y']);
		if($this->heuristic == 0) {
			//Manhattan Method
			return 10*($xDistance + $yDistance);		
		}
		elseif($this->heuristic == 1) {
			//Diagonal Shortcut
			if($xDistance > $yDistance ) {
				return 14*$yDistance + 10*($xDistance - $yDistance);
			}
			else {
				return 14*$xDistance + 10*($yDistance - $xDistance);
			}
		}
	}
	
	private function dist_between($xDistance, $yDistance) {
		return (int)ceil(sqrt(pow($xDistance, 2) + pow($yDistance, 2)));
	}
	
	//計算index value
	private function val($point) {
		return $point['x'] * MAP_Y + $point['y'];
	}
}
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>A* search algorithm Demo</title>
A* search algorithm Demo By ScarWu<br>

<?php
$map_cost = array(
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2),
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2),
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2),
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2),
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2),
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2),
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2),
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2),
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2),
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2),
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2),
	array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2)
);

$map_wall = array(
	array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	array(1,1,0,0,0,0,0,1,1,1,1,0,0,0,0,0,1,1),
	array(1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,1),
	array(1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1),
	array(1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1),
	array(1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,1),
	array(1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,1),
	array(1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1),
	array(1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1),
	array(1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,1),
	array(1,1,0,0,0,0,0,1,1,1,1,0,0,0,0,0,1,1),
	array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1)
);

$start['x'] = $_GET['x1'] != NULL ? (int)$_GET['x1'] : 0;
$start['y'] = $_GET['y1'] != NULL ? (int)$_GET['y1'] : 0;
$goal['x'] = $_GET['x2'] != NULL ? (int)$_GET['x2'] : 11;
$goal['y'] = $_GET['y2'] != NULL ? (int)$_GET['y2'] : 17;
$h = $_GET['h'] != NULL ? (int)$_GET['h'] : 0;
$w = $_GET['w'] != NULL ? (int)$_GET['w'] : 0;

if($start['x'] < 0 || $start['x'] > 11) $start['x'] = 0;
if($start['y'] < 0 || $start['y'] > 17) $start['y'] = 0;
if($goal['x'] < 0 || $goal['x'] > 11) $goal['x'] = 0;
if($goal['y'] < 0 || $goal['y'] > 17) $goal['y'] = 0;
if($h < 0 || $h > 1) $h = 0;
if($w < 0 || $w > 1) $w = 0;

$short_path = new Path_Search($map_cost, $map_wall, $h, $w);
$path = $short_path->A_star($start, $goal);
?>

<style type="text/css">
td {
	width: 40px;
	height: 40px;
	border-color: #000;
	border: solid 0px;
	font-size: 10px;
}
</style>



<form method="get" action="a_star.php">
	起點 
	X<input name="x1" type="text" value="<?php echo $start['x']; ?>" size="3" maxlength="2" />
	Y<input name="y1" type="text" value="<?php echo $start['y']; ?>" size="3" maxlength="2" />
	終點
	X<input name="x2" type="text" value="<?php echo $goal['x']; ?>" size="3" maxlength="2" />
	Y<input name="y2" type="text" value="<?php echo $goal['y']; ?>" size="3" maxlength="2" />
	<br />
	錯誤估計函式
	<label><input name="h" type="radio" value="0" <?php if($h == 0) echo 'checked="checked"'; ?> />Manhattan Method</label>
	<label><input name="h" type="radio" value="1" <?php if($h == 1) echo 'checked="checked"'; ?> />Diagonal Shortcut</label>
	<br />
	行走方式
	<label><input name="w" type="radio" value="0" <?php if($w == 0) echo 'checked="checked"'; ?> />米字</label>
	<label><input name="w" type="radio" value="1" <?php if($w == 1) echo 'checked="checked"'; ?> />十字</label>
	<br />
	<input type="submit" value="確定" />
</form>

<table border="1" cellspacing="0" cellpadding="0">
<?php
foreach((array)$path['shortpath'] as $x) {
	$shortpath[$x['x'] * MAP_Y + $x['y']] = $x;
}

for($i = 0;$i < MAP_X;++$i) {
	echo '<tr>';
	for($j = 0;$j < MAP_Y;++$j) {
		$tmp = $i * MAP_Y + $j;
		if($map_wall[$i][$j] == 0) {
			if(isset($shortpath[$tmp])) {
				if($start['x'] == $i && $start['y'] == $j || $goal['x'] == $i && $goal['y'] == $j){
					echo '<td align="center" bgcolor="#333333">';
				}
				else {
					echo '<td align="center" bgcolor="#666666">';
				}
				if(isset($shortpath[$tmp]['parent'])) {
					echo "(".$i.",".$j.")<br>";
				}
				else {
					echo "(".$i.",".$j.")<br>";
				}
			}
			elseif(isset($path['closedset'][$tmp])) {
				echo '<td align="center" bgcolor="#999999">';
				echo "(".$i.",".$j.")<br>";
			}
			elseif(isset($path['openset'][$tmp])) {
				echo '<td align="center" bgcolor="#CCCCCC">';
				echo "(".$i.",".$j.")<br>";
			}
			else {
				echo '<td align="center">';
				echo "(".$i.",".$j.")<br>";
			}
			echo "</td>";
		}
		else {
			echo '<td align="center" bgcolor="#000000">';
			echo "(".$i.",".$j.")<br>";
			echo "</td>";
		}
	}
	echo '</tr>';
}
?>
</table>
