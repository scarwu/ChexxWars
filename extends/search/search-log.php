<?php
/*
 * 戰鬥紀錄產生器
 */
class BattleLog {
	private $path;
	private $team;
	private $LogRecord;
	private $Count;
	
	public function	__construct($battle_data) {
		require_once("search-a_star.php");
		$this->team[0] = $battle_data['teamA'];
		$this->team[1] = $battle_data['teamB'];
		$this->Count = 0;
		$this->path = new Path_Search($battle_data['maps']['map']);
		$this->Generate();
	}
	
	//回傳戰鬥紀錄
	public function ShowLog() {
		return $this->LogRecord;
	}
	
	//生成戰鬥紀錄
	private function Generate() {
		while(1) {
			foreach((array)$this->team[0] as $key => $value) {
				if($this->team[1] != NULL) {
					$this->Step($key, 0, 1);
				}
			}
			foreach((array)$this->team[1] as $key => $value) {
				if($this->team[0] != NULL) {
					$this->Step($key, 1, 0);
				}
			}
			if($this->team[0] == NULL) {
				$this->LogRecord[$this->Count++] = array(
					'action' => 'end',
					'who' => 1
				);
				break;
			}
			else if($this->team[1] == NULL) {
				$this->LogRecord[$this->Count++] = array(
					'action' => 'end',
					'who' => 0
				);
				break;
			}
		}
	}
	
	private function Step($keyA, $idA, $idB) {
		$sid = rand(0,4);
		$keyB = $this->Closeset($keyA, $idA, $idB);
		$range = $this->Distance($keyA, $keyB, $idA, $idB);
		if(isset($this->team[$idA][$keyA]['skill'][$sid])) {
			if($this->team[$idA][$keyA]['skill'][$sid]['max_dis'] >= $range && $this->team[$idA][$keyA]['skill'][$sid]['min_dis'] <= $range) {
				$this->Attack($keyA, $keyB, $idA, $idB, $sid);
			}
			else if($this->team[$idA][$keyA]['skill'][$sid]['min_dis'] > $range){
				$this->MovePoint($keyA, $keyB, $idA, $idB, $sid);
				$keyB = $this->Closeset($keyA, $idA, $idB);
				$range = $this->Distance($keyA, $keyB, $idA, $idB);
				if($this->team[$idA][$keyA]['skill'][$sid]['max_dis'] >= $range && $this->team[$idA][$keyA]['skill'][$sid]['min_dis'] <= $range) {
					$this->Attack($keyA, $keyB, $idA, $idB, $sid);
				}
			}
	    	else {
	    		$this->Move($keyA, $keyB, $idA, $idB, $sid);//移動
				//判斷可不可以攻擊
				$keyB = $this->Closeset($keyA, $idA, $idB);
				$range = $this->Distance($keyA, $keyB, $idA, $idB);
				if($this->team[$idA][$keyA]['skill'][$sid]['max_dis'] >= $range && $this->team[$idA][$keyA]['skill'][$sid]['min_dis'] <= $range) {
					$this->Attack($keyA, $keyB, $idA, $idB, $sid);
				}
			}
		}
	//沒選到攻擊 迴避
		else { 
			if($this->team[$idA][$keyA]['dis'] < $range){
				$this->Move($keyA, $keyB, $idA, $idB, $sid);
			}
			else {
				$this->MovePoint($keyA, $keyB, $idA, $idB, $sid);
			}
		}

	}
	//產生禁止區域
	private function Forbid($keyA, $keyB, $idA, $idB) {
		foreach((array)$this->team[0] as $value) {
			$forbid[$value['x']][$value['y']] = 1;
		}
		foreach((array)$this->team[1] as $value) {
			$forbid[$value['x']][$value['y']] = 1;
		}
		$forbid[$this->team[$idA][$keyA]['x']][$this->team[$idA][$keyA]['y']] = 0;
		$forbid[$this->team[$idB][$keyB]['x']][$this->team[$idB][$keyB]['y']] = 0;
		return $forbid;
	}

	private function Move($keyA, $keyB, $idA, $idB, $sid) {
		$start = array('x' => $this->team[$idA][$keyA]['x'], 'y' => $this->team[$idA][$keyA]['y']);
		$goal = array('x' => $this->team[$idB][$keyB]['x'], 'y' => $this->team[$idB][$keyB]['y']);
		$forbid = $this->Forbid($keyA, $keyB, $idA, $idB);
		$path = $this->path->A_star($start, $goal, $forbid);
		$path = array_slice($path, 1, count($path)-2);
		$dis = count($path);
		if($dis > 0) {
			$counter=0;
			$dis = $dis < $this->team[$idA][$keyA]['dis'] ? $dis : $this->team[$idA][$keyA]['dis'];
			for($i=0;$i<$dis;$i++) {
				$sdis=sqrt(pow($path[$i]['x']-$goal['x'],2)+pow($path[$i]['y']-$goal['y'],2));
				$counter++;
				if($sdis >= $this->team[$idA][$keyA]['skill'][$sid]['min_dis'] && $sdis <= $this->team[$idA][$keyA]['skill'][$sid]['max_dis']){												   					break;
				}
			}

			$this->team[$idA][$keyA]['x'] = $path[$counter-1]['x'];
			$this->team[$idA][$keyA]['y'] = $path[$counter-1]['y'];
			$this->LogRecord[$this->Count++] = array(
				'action' => 'move',
				'who' => array(
					'team' => $this->team[$idA][$keyA]['team'],
					'order' => $this->team[$idA][$keyA]['order'],
					'id' => $this->team[$idA][$keyA]['id'],
					'hp' => $this->team[$idA][$keyA]['hp'],
					'x' => $this->team[$idA][$keyA]['x'], 
					'y' => $this->team[$idA][$keyA]['y']
				),
				'where' => array_slice($path, 0, $counter)
			);
		}
	}
	
	private function MovePoint($keyA, $keyB, $idA, $idB, $sid) {
		$direction = rand(0,1);
		if(($this->team[$idA][$keyA]['y'] - $this->team[$idB][$keyB]['y']) > 0){
			if($direction ==0){
				$goal = array('x' => 2, 'y' => 16);
			}
			else {
				$goal = array('x' => 9, 'y' => 16);
			}
		}
		else if(($this->team[$idA][$keyA]['y'] - $this->team[$idB][$keyB]['y']) < 0){
			if($direction ==0){
				$goal = array('x' => 2, 'y' => 2);
			}
			else {
				$goal = array('x' => 9, 'y' => 2);
			}
		}
		else {
			if(($this->team[$idA][$keyA]['x'] - $this->team[$idB][$keyB]['x']) > 0){
				if($direction ==0){
				$goal = array('x' => 9, 'y' => 2);
			}
			else {
				$goal = array('x' => 9, 'y' => 16);
			}
			}
			else {
				if($direction ==0){
				$goal = array('x' => 2, 'y' => 2);
				}
				else {
					$goal = array('x' => 2, 'y' => 16);
				}
			}
		}
		$start = array('x' => $this->team[$idA][$keyA]['x'], 'y' => $this->team[$idA][$keyA]['y']);
		$forbid = $this->Forbid($keyA, $keyA, $idA, $idA);
		$path = $this->path->A_star($start, $goal, $forbid);
		$path = array_slice($path, 1, count($path)-1);
		$dis = count($path);
		if($dis > 0) {
			$counter=0;
			$dis = $dis < $this->team[$idA][$keyA]['dis'] ? $dis : $this->team[$idA][$keyA]['dis'];
			$dis = $dis == 1 ? $dis : $dis/2;
			for($i=0;$i<$dis;$i++) {
				$sdis=sqrt(pow($path[$i]['x']-$this->team[$idB][$keyB]['x'],2)+pow($path[$i]['y']-$this->team[$idB][$keyB]['y'],2));
				$counter++;
				if($sdis >= $this->team[$idA][$keyA]['skill'][$sid]['min_dis'] && $sdis <= $this->team[$idA][$keyA]['skill'][$sid]['max_dis']){												   					break;
				}
			}
			$this->team[$idA][$keyA]['x'] = $path[$counter-1]['x'];
			$this->team[$idA][$keyA]['y'] = $path[$counter-1]['y'];
			$this->LogRecord[$this->Count++] = array(
				'action' => 'move',
				'who' => array(
					'team' => $this->team[$idA][$keyA]['team'],
					'order' => $this->team[$idA][$keyA]['order'],
					'id' => $this->team[$idA][$keyA]['id'],
					'hp' => $this->team[$idA][$keyA]['hp'],
					'x' => $this->team[$idA][$keyA]['x'], 
					'y' => $this->team[$idA][$keyA]['y']
				),
				'where' => array_slice($path, 0, $counter)
			);
		}
	}

	private function Attack($keyA, $keyB, $idA, $idB, $sid) {
		$this->team[$idB][$keyB]['hp'] -= $this->team[$idA][$keyA]['skill'][$sid]['atk'];
		$this->LogRecord[$this->Count++] = array(
			'action' => 'atk',
			'whoA' => array(
				'team' => $this->team[$idA][$keyA]['team'],
				'order' => $this->team[$idA][$keyA]['order'],
				'id' => $this->team[$idA][$keyA]['id'],
				'hp' => $this->team[$idA][$keyA]['hp'],
				'x' => $this->team[$idA][$keyA]['x'], 
				'y' => $this->team[$idA][$keyA]['y']
			),
			'skill' => array(
				'id' => $this->team[$idA][$keyA]['skill'][$sid]['id'],
				'min_dis' => $this->team[$idA][$keyA]['skill'][$sid]['min_dis'],
				'max_dis' => $this->team[$idA][$keyA]['skill'][$sid]['max_dis'],
				'atk' => $this->team[$idA][$keyA]['skill'][$sid]['atk']
			),
			'whoB' => array(
				'team' => $this->team[$idB][$keyB]['team'],
				'order' => $this->team[$idB][$keyB]['order'],
				'id' => $this->team[$idB][$keyB]['id'],
				'hp' => $this->team[$idB][$keyB]['hp'],
				'x' => $this->team[$idB][$keyB]['x'], 
				'y' => $this->team[$idB][$keyB]['y']
			)
		);
		if($this->team[$idB][$keyB]['hp'] <= 0) {
			$this->LogRecord[$this->Count++] = array(
				'action' => 'die',
				'who' => array(
					'team' => $this->team[$idB][$keyB]['team'],
					'order' => $this->team[$idB][$keyB]['order'],
					'id' => $this->team[$idB][$keyB]['id'],
					'hp' => $this->team[$idB][$keyB]['hp'],
					'x' => $this->team[$idB][$keyB]['x'], 
					'y' => $this->team[$idB][$keyB]['y']
				)
			);
			unset($this->team[$idB][$keyB]);
		}
	}

	private function Distance($keyA, $keyB, $idA, $idB) {
		$DisX = $this->team[$idA][$keyA]['x'] - $this->team[$idB][$keyB]['x'];
		$DisY = $this->team[$idA][$keyA]['y'] - $this->team[$idB][$keyB]['y'];
		return sqrt(pow($DisX, 2) + pow($DisY, 2));
	}
	
	//找出最近的敵人
	private function Closeset($keyA, $idA, $idB) {
		foreach($this->team[$idB] as $keyB => $value) {
			$dis = pow($this->team[$idA][$keyA]['x'] - $value['x'], 2) + pow($this->team[$idA][$keyA]['y'] - $value['y'], 2);
			if(!isset($min_key)) {
				$min_key = $keyB;
				$min = $dis;
			}
			else {
				$min_key = $dis < $min ? $keyB : $min_key;
				$min = $dis < $min ? $dis : $min;
			}
		}
		return $min_key;
	}

}
?>