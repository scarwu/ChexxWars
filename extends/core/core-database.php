<?php
//簡易資料庫連結
class Database {
	private $db_host;
	private $db_user;
	private $db_pass;
	private $db_name;
	private $db_link;

	public function __construct($host, $user, $pass, $name) {
		$this->db_host = $host;
		$this->db_user = $user;
		$this->db_pass = $pass;
		$this->db_name = $name;
	}
	
	public function connect() {
		$this->db_link = mysql_connect($this->db_host, $this->db_user, $this->db_pass) or die("MySQL failed"); 
		mysql_select_db($this->db_name, $this->db_link) or die("Database failed");
		mysql_query("SET NAMES 'utf8'");
       	mysql_query("SET CHARACTER_SET_CLIENT=utf8");
       	mysql_query("SET CHARACTER_SET_RESULTS=utf8");
	}
	
	public function close() {
		return mysql_close($this->db_link);
	}
	
	public function query($SQL) {
		return mysql_query($SQL) or die("Query failed");
	}
	
	public function query_array($SQL) {
		$result = mysql_query($SQL) or die("Query failed");
		return mysql_fetch_array($result);
	}
	
	public function query_row($SQL) {
		$result = mysql_query($SQL) or die("Query failed");
		return mysql_fetch_row($result);
	}
	
	public function query_object($SQL) {
		$result = mysql_query($SQL) or die("Query failed");
		return mysql_fetch_object($result);
	}
}
?>