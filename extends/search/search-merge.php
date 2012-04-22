<?php
class DataMerge {
	private $hero;
	private $item;
	private $skill;
	private $mission;
	private $arrange;
	private $maps;
	private $DB;
	private $FB;

	public function __construct() {
		require_once("data.php");
		require_once("core.php");
		$this->hero = $hero;
		$this->item = $item;
		$this->skill = $skill;
		$this->maps = $maps;
		$this->mission = $mission;
		$this->arrange = $arrange;
		$this->DB = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$this->FB = new Facebook(APP_API_KEY, APP_SECRET);
	}
	
	public function GetData($data, $id) {
		switch($data) {
			case 'mission': {
				return $this->Mission($id);
				break;	
			}
			case 'battle': {
				return $this->Battle($id);
				break;	
			}
		}
	}
	
	private function PlayerTeamCreate($row, $id) {
		$data = json_decode($row['arrange'], true);
		for($i = 0;$i < count($data);++$i) {
			if($id == 1) {
				$data[$i]['x'] = 11 - $data[$i]['x'];
				$data[$i]['y'] = 17 - $data[$i]['y'];
			}
			$data[$i]['name'] = $this->hero[$data[$i]['id']]['name'];
			$data[$i]['hp'] = $this->hero[$data[$i]['id']]['hp'];
			$data[$i]['atk'] = $this->hero[$data[$i]['id']]['atk'];
			$data[$i]['dis'] = $this->hero[$data[$i]['id']]['dis'];
			$statushero = json_decode($row['hero'], true);
			$data[$i]['skill'] = $statushero[$data[$i]['id']][$data[$i]['order']]['skill'];
			$data[$i]['item'] = $statushero[$data[$i]['id']][$data[$i]['order']]['item'];
			$data[$i]['team'] = $id;
			$data[$i]['order'] = $i;
			for($j = 0;$j < count($data[$i]['item']);++$j) {
				$data[$i]['hp'] += $this->item[$data[$i]['item'][$j]]['hp'];
				$data[$i]['atk'] += $this->item[$data[$i]['item'][$j]]['atk'];
				$data[$i]['dis'] += $this->item[$data[$i]['item'][$j]]['dis'];
				$data[$i]['item'][$j] = array(
					'id' => $data[$i]['item'][$j],
					'name' => $this->item[$data[$i]['item'][$j]]['name'],
					'hp' => $this->item[$data[$i]['item'][$j]]['hp'],
					'atk' => $this->item[$data[$i]['item'][$j]]['atk'],
					'dis' => $this->item[$data[$i]['item'][$j]]['dis']
				);
			}
			if(count($data[$i]['skill']) > 0) {
				for($j = 0;$j < count($data[$i]['skill']);++$j) {
					$data[$i]['skill'][$j] = array(
						'id' => $data[$i]['skill'][$j],
						'name' => $this->skill[$data[$i]['skill'][$j]]['name'],
						'max_dis' => $this->skill[$data[$i]['skill'][$j]]['max_dis'],
						'min_dis' => $this->skill[$data[$i]['skill'][$j]]['min_dis'],
						'atk' => ($this->skill[$data[$i]['skill'][$j]]['atk'] + $data[$i]['atk'])
					);
				}
			}
			else {
				$data[$i]['skill'][0] = array(
					'id' => '000',
					'name' => '近戰攻擊',
					'max_dis' => 1,
					'min_dis' => 1,
					'atk' => $data[$i]['atk']
				);
			}
		}
		return $data;
	}
	
	private function Battle($id) {
		$temp['maps'] = $this->maps[array_rand($this->maps)];
		$this->DB->connect();
		$row = $this->DB->query_array("SELECT arrange, hero FROM user WHERE uid='".$_SESSION['info']['uid']."'");
		//合併TeamA
		$temp['teamA'] = $this->PlayerTeamCreate($row, 0);
		//合併TeamB
		$row = $this->DB->query_array("SELECT arrange, hero FROM user WHERE uid='".$id."'");
		if(!$row) {
			if(in_array($id, $_SESSION['friends_list'])) {
				//新建使用者資料
				require_once("data/data-default.php");
				$this->DB->query("INSERT user SET uid='".$id."', status='".$default_status."', item='".$default_item."', skill='".$default_skill."', arrange='".$default_arrange."', hero='".$default_hero."'");
				$row = $this->DB->query_array("SELECT arrange, hero FROM user WHERE uid='".$id."'");
			}
			else return 0;
		}
		$this->DB->close();
		$temp['teamB'] = $this->PlayerTeamCreate($row, 1);
		return $temp;
	}	
	
	//生成Mission資料
	private function Mission($id) {
		$temp['maps'] = $this->maps[$id];
		$this->DB->connect();
		$row = $this->DB->query_array("SELECT arrange, hero FROM user WHERE uid='".$_SESSION['info']['uid']."'");
		$this->DB->close();
		//合併TeamA
		$temp['teamA'] = $this->PlayerTeamCreate($row, 0);
		//合併TeamB
		$data = $this->arrange[$this->mission[$id]['arrange']];
		for($i = 0;$i < count($data);++$i) {
			$data[$i]['name'] = $this->hero[$data[$i]['id']]['name'];
			$data[$i]['hp'] = $this->hero[$data[$i]['id']]['hp'];
			$data[$i]['atk'] = $this->hero[$data[$i]['id']]['atk'];
			$data[$i]['dis'] = $this->hero[$data[$i]['id']]['dis'];
			$data[$i]['team'] = 1;
			$data[$i]['order'] = $i;
			for($j = 0;$j < count($data[$i]['item']);++$j) {
				$data[$i]['hp'] += $this->item[$data[$i]['item'][$j]]['hp'];
				$data[$i]['atk'] += $this->item[$data[$i]['item'][$j]]['atk'];
				$data[$i]['dis'] += $this->item[$data[$i]['item'][$j]]['dis'];
				$data[$i]['item'][$j] = array(
					'id' => $data[$i]['item'][$j],
					'name' => $this->item[$data[$i]['item'][$j]]['name'],
					'hp' => $this->item[$data[$i]['item'][$j]]['hp'],
					'dis' => $this->item[$data[$i]['item'][$j]]['dis'],
					'atk' => $this->item[$data[$i]['item'][$j]]['atk']
				);
			}
			if(count($data[$i]['skill']) > 0) {
				for($j = 0;$j < count($data[$i]['skill']);++$j) {
					$data[$i]['skill'][$j] = array(
						'id' => $data[$i]['skill'][$j],
						'name' => $this->skill[$data[$i]['skill'][$j]]['name'],
						'max_dis' => $this->skill[$data[$i]['skill'][$j]]['max_dis'],
						'min_dis' => $this->skill[$data[$i]['skill'][$j]]['min_dis'],
						'atk' => ($this->skill[$data[$i]['skill'][$j]]['atk'] + $data[$i]['atk'])
					);
				}
			}
			else {
				$data[$i]['skill'][0] = array(
					'id' => '000',
					'name' => '近戰攻擊',
					'max_dis' => 1,
					'min_dis' => 1,
					'atk' => $data[$i]['atk']
				);
			}
		}
		$temp['teamB'] = $data;
		return $temp;
	}
}
?>