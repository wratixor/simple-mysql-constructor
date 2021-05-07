<?php
	class Connector {
		
		private $db_resource;
		
		public function __construct($config_file) {
			$arr = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/configs/'.$config_file);
			
			$this->db_resource = new mysqli($arr['server'], $arr['username'], $arr['password'], $arr['db'], $arr['port']);
			$this->db_resource->set_charset($arr['charset']);
			
			if($this->db_resource->connect_error){
				die('Не удалось подключиться к MySQL: ('
						.$this->db_resource->connect_errno
						.') '
						.$this->db_resource->connect_error
					);
			}
		}
		
		public function get_db() {
			return $this->db_resource;
		}
		public function e_query($query) {
			$db_result = $this->db_resource->query($query) 
				or die('Не удалось выполнить: "'.$query.'"<br>Потому что: "'.$this->db_resource->error.'"');
			return $db_result;
		}
		public function __destruct() {
			$this->db_resource->close();
		}
		
		
		public function type2txt($type_id) {
			$MYSQLI_TYPES = array();
			$MYSQLI_TYPES[1] = 'TINYINT';
			$MYSQLI_TYPES[2] = 'SMALLINT';
			$MYSQLI_TYPES[3] = 'INT';
			$MYSQLI_TYPES[4] = 'FLOAT';
			$MYSQLI_TYPES[5] = 'DOUBLE';
			$MYSQLI_TYPES[6] = 'DEFAULT NULL';
			$MYSQLI_TYPES[7] = 'TIMESTAMP';
			$MYSQLI_TYPES[8] = 'BIGINT';
			$MYSQLI_TYPES[9] = 'MEDIUMINT';
			$MYSQLI_TYPES[10] = 'DATE';
			$MYSQLI_TYPES[11] = 'TIME';
			$MYSQLI_TYPES[12] = 'DATETIME';
			$MYSQLI_TYPES[13] = 'YEAR';
			$MYSQLI_TYPES[14] = 'DATE';
			$MYSQLI_TYPES[16] = 'BIT';
			$MYSQLI_TYPES[247] = 'ENUM';
			$MYSQLI_TYPES[248] = 'SET';
			$MYSQLI_TYPES[249] = 'TINYBLOB';
			$MYSQLI_TYPES[250] = 'MEDIUMBLOB';
			$MYSQLI_TYPES[251] = 'LONGBLOB';
			$MYSQLI_TYPES[252] = 'BLOB';
			$MYSQLI_TYPES[253] = 'VARCHAR';
			$MYSQLI_TYPES[254] = 'CHAR';
			$MYSQLI_TYPES[247] = 'INTERVAL';
			$MYSQLI_TYPES[255] = 'GEOMETRY';
			$MYSQLI_TYPES[245] = 'JSON';
			$MYSQLI_TYPES[246] = 'DECIMAL';
			return $MYSQLI_TYPES[$type_id];
		}
		
		public static function h_flags2txt($flags_num) {
			##TODO FIX IT
			static $flags;
			if (!isset($flags)) {
				$flags = array();
				$constants = get_defined_constants(true);
				foreach ($constants['mysqli'] as $c => $n) {
					if (preg_match('/MYSQLI_(.*)_FLAG$/', $c, $m)) {
						if (!array_key_exists($n, $flags)) {
							$flags[$n] = $m[1];
						}
					}
				}
			}
			$result = array();
			foreach ($flags as $n => $t) {
				if ($flags_num & $n) $result[] = $t;
			}
			return implode(' ', $result);
		}
		
		
	}
?>