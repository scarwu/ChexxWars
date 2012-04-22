<?php
/*
 * 資料IO功能
 */
class InputOutput {
	private $row;
	private $hero;
	private $item;
	private $skill;
	private $mission;
	private $DB;
	private $FB;
	
	public function __construct() {
		require_once("data.php");
		require_once("core.php");
		$this->hero = $hero;
		$this->item = $item;
		$this->skill = $skill;
		$this->mission = $mission;
		$this->DB = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$this->FB = new Facebook(APP_API_KEY, APP_SECRET);
	}

//IO Write Funciton Start
	public function Write($data, $idA, $idB) {
		$this->DB->connect();
		$this->row = $this->DB->query_array("SELECT * FROM user WHERE uid='".$_SESSION['info']['uid']."'");	
		switch($data) {
			case 'store': {
				if($idA == 'hero') $this->WriteStoreHero($idB);
				elseif($idA == 'item') $this->WriteStoreItem($idB);
				elseif($idA == 'skill') $this->WriteStoreSkill($idB);
				break;
			}
			case 'status': {
				if($idA == 'arrange') $this->WriteStatusArrange($idB);
				elseif($idA == 'hero') $this->WriteStatusHero($idB);
				break;
			}
		}
		$this->DB->close();
	}
	
	//寫入英雄狀態
	private function WriteStatusHero($id) {
		$user_hero = json_decode($this->row['hero'], true);
		$data = json_decode(str_replace('\\', '', $id), true);
		foreach((array)$data['equip'] as $key => $value) {
			$user_hero[$value['id']][$value['order']]['skill'] = $value['skill'];
			$user_hero[$value['id']][$value['order']]['item'] = $value['item'];
			ksort($user_hero[$value['id']][$value['order']]['skill']);
			ksort($user_hero[$value['id']][$value['order']]['item']);
		}
		foreach((array)$data['skill'] as $key => $value) {
			$user_skill[$value['id']] = $value;
		}
		ksort($user_skill);
		foreach((array)$data['item'] as $key => $value) {
			$user_item[$value['id']] = $value;
		}
		ksort($user_item);
		$this->DB->query("UPDATE user SET hero='".json_encode($user_hero)."', skill='".json_encode($user_skill)."', item='".json_encode($user_item)."' WHERE uid='".$_SESSION['info']['uid']."'");
	}
	
	//寫入配置狀態
	private function WriteStatusArrange($id) {
		$data = json_decode(str_replace('\\', '', $id), true);
		$this->DB->query("UPDATE user SET arrange='".json_encode($data)."' WHERE uid='".$_SESSION['info']['uid']."'");
	}
	
	//寫入購買英雄
	private function WriteStoreHero($id) {
		$status = json_decode($this->row['status'], true);
		$user_hero = json_decode($this->row['hero'], true);
		if($status['coin'] >= $this->hero[$id]['price'] && count($user_hero[$id]) < $this->hero[$id]['qty']) {
			$status['coin'] -= $this->hero[$id]['price'];
			if(!array_key_exists($id, $user_hero)) $user_hero[$id] = array(array('id' => $id, 'order' => 0, 'item' => array(), 'skill' => array()));
			else {
				$add = array('id' => $id, 'order' => count($user_hero[$id]), 'item' => array(), 'skill' => array());
				array_push($user_hero[$id], $add);
			}
			ksort($user_hero);
			$this->DB->query("UPDATE user SET status='".json_encode($status)."', hero='".json_encode($user_hero)."' WHERE uid='".$_SESSION['info']['uid']."'");
		}
	}
	
	//寫入購買物品
	private function WriteStoreItem($id) {
		$status = json_decode($this->row['status'], true);
		$user_item = json_decode($this->row['item'], true);
		if($status['coin'] >= $this->item[$id]['price']) {
			$status['coin'] -= $this->item[$id]['price'];
			if(!array_key_exists($id, $user_item)) $user_item[$id] = array('id' => $id, 'own' => 1);
			else ++$user_item[$id]['own'];
			ksort($user_item);
			$this->DB->query("UPDATE user SET status='".json_encode($status)."', item='".json_encode($user_item)."' WHERE uid='".$_SESSION['info']['uid']."'");
		}
	}
	
	//寫入購買技能
	private function WriteStoreSkill($id) {
		$status = json_decode($this->row['status'], true);
		$user_skill = json_decode($this->row['skill'], true);
		if($status['coin'] >= $this->skill[$id]['price']) {
			$status['coin'] -= $this->skill[$id]['price'];
			if(!array_key_exists($id, $user_skill)) $user_skill[$id] = array('id' => $id, 'own' => 1);
			else ++$user_skill[$id]['own'];
			ksort($user_skill);
			$this->DB->query("UPDATE user SET status='".json_encode($status)."', skill='".json_encode($user_skill)."' WHERE uid='".$_SESSION['info']['uid']."'");
		}
	}

// IO Read Funciton Start
	public function Read($data, $id) {
		$this->DB->connect();
		$this->row = $this->DB->query_array("SELECT * FROM user WHERE uid='".$_SESSION['info']['uid']."'");
		$JSON["status"] = json_decode($this->row['status'], true);
		switch($data) {
			case 'status': {
				if($id == 'hero')
					$JSON["data"] = $this->ReadStatusHero();
				elseif($id == 'arrange')
					$JSON["data"] = $this->ReadStatusArrange();
				break;
			}
			case 'store': {
				if($id == 'hero')
					$JSON["data"] = $this->ReadStoreHero();
				elseif($id == 'item')
					$JSON["data"] = $this->ReadStoreItem();
				elseif($id == 'skill')
					$JSON["data"] = $this->ReadStoreSkill();
				break;
			}
			
			case 'mission': {
				$JSON["data"] = $this->ReadMission($id);
				break;	
			}
			
			case 'battle':
			case 'friends': {
				$JSON["data"] = $this->ReadFriends();
				break;
			}
		}
		$this->DB->close();
		return json_encode($JSON);
	}
	
	//讀取朋友資料
	private function ReadFriends() {
		$data = $_SESSION['friends'];
		for($i = 0;$i < sizeof($_SESSION['friends_list']);++$i) {
			$this->row = $this->DB->query_array("SELECT  status FROM user WHERE uid='".$_SESSION['friends_list'][$i]."'");
			if($this->row) {
				$data[$i]["status"] = json_decode($this->row["status"]);
			}
			else {
				$data[$i]["status"] = json_decode('{"win":0,"lose":0,"exp":0,"coin":0}');
			}
		}
		return $data;
	}
	
	//讀取英雄狀態
	private function ReadStatusHero() {
		$temp = json_decode($this->row['hero'], true);
		foreach((array)$temp as $keyA => $valueA) {
			foreach((array)$valueA as $keyB => $valueB) {
				$temp[$keyA][$keyB]['name'] = $this->hero[$keyA]['name'];
				$temp[$keyA][$keyB]['hp'] = $this->hero[$keyA]['hp'];
				$temp[$keyA][$keyB]['atk'] = $this->hero[$keyA]['atk'];
				$temp[$keyA][$keyB]['dis'] = $this->hero[$keyA]['dis'];
				$temp[$keyA][$keyB]['res'] = $this->hero[$keyA]['res'];
				$temp[$keyA][$keyB]['skill'] = $this->hero[$keyA]['skill'];
				$temp[$keyA][$keyB]['item'] = $this->hero[$keyA]['item'];
				foreach((array)$valueB['item'] as $keyC => $valueC) {
					$temp[$keyA][$keyB]['item_list'][$keyC] = array(
						'id' => $valueC,
						'name' => $this->item[$valueC]['name'],
						'hp' => $this->item[$valueC]['hp'],
						'atk' => $this->item[$valueC]['atk'],
						'dis' => $this->item[$valueC]['dis']
					);
					$temp[$keyA][$keyB]['hp'] += $this->item[$valueC]['hp'];
					$temp[$keyA][$keyB]['atk'] += $this->item[$valueC]['atk'];
					$temp[$keyA][$keyB]['dis'] += $this->item[$valueC]['dis'];
				}
				foreach((array)$valueB['skill'] as $keyC => $valueC) {
					$temp[$keyA][$keyB]['skill_list'][$keyC] = array(
						'id' => $valueC,
						'name' => $this->skill[$valueC]['name'],
						'atk' => $this->skill[$valueC]['atk'],
						'min_dis' => $this->skill[$valueC]['min_dis'],
						'max_dis' => $this->skill[$valueC]['max_dis']
					);
				}
			}
		}
		$data['hero'] = $temp;
		$temp = json_decode($this->row['skill'], true);
		foreach((array)$temp as $key => $value) {
			$temp[$key]['name'] = $this->skill[$key]['name'];
			$temp[$key]['atk'] = $this->skill[$key]['atk'];
			$temp[$key]['min_dis'] = $this->skill[$key]['min_dis'];
			$temp[$key]['max_dis'] = $this->skill[$key]['max_dis'];
		}
		$data['skill'] = $temp;
		$temp = json_decode($this->row['item'], true);
		foreach((array)$temp as $key => $value) {
			$temp[$key]['name'] = $this->item[$key]['name'];
			$temp[$key]['hp'] = $this->item[$key]['hp'];
			$temp[$key]['atk'] = $this->item[$key]['atk'];
			$temp[$key]['dis'] = $this->item[$key]['dis'];
		}
		$data['item'] = $temp;
		return $data;
	}
	
	//讀取戰鬥配置
	private function ReadStatusArrange() {
		$temp = json_decode($this->row['hero'], true);
		foreach((array)$temp as $keyA => $valueA) {
			foreach((array)$valueA as $keyB => $valueB) {
				$data['unset'][$keyA][$keyB]['name'] = $this->hero[$keyA]['name'];
				$data['unset'][$keyA][$keyB]['res'] = $this->hero[$keyA]['res'];
				$data['unset'][$keyA][$keyB]['hp'] = $this->hero[$keyA]['hp'];
				$data['unset'][$keyA][$keyB]['atk'] = $this->hero[$keyA]['atk'];
				$data['unset'][$keyA][$keyB]['dis'] = $this->hero[$keyA]['dis'];
				$data['unset'][$keyA][$keyB]['id'] = $keyA;
				$data['unset'][$keyA][$keyB]['order'] = $keyB;
				foreach((array)$valueB['item'] as $key => $value) {
					$data['unset'][$keyA][$keyB]['hp'] += $this->item[$value]['hp'];
					$data['unset'][$keyA][$keyB]['atk'] += $this->item[$value]['atk'];
					$data['unset'][$keyA][$keyB]['dis'] += $this->item[$value]['dis'];
				}
			}
		}
		$temp = json_decode($this->row['arrange'], true);
		foreach((array)$temp as $keyA => $valueA) {
			$data['set'][$keyA] = $data['unset'][$valueA['id']][$valueA['order']];
			$data['set'][$keyA]['x'] = $valueA['x'];
			$data['set'][$keyA]['y'] = $valueA['y'];
			unset($data['unset'][$valueA['id']][$valueA['order']]);
		}
		return $data;
	}
	
	//讀取英雄資料
	private function ReadStoreHero() {
		return $this->hero;
	}
	
	//讀取物品資料
	private function ReadStoreItem() {
		return $this->item;
	}
	
	//讀取技能資料
	private function ReadStoreSkill() {
		return $this->skill;
	}
	
	//讀取任務資料
	private function ReadMission($id) {
		return $this->mission[$id];
	}
}
?>