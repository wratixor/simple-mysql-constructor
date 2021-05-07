<?php
	class Paranoid {
		private static $method = PASSWORD_DEFAULT;
		private static $options = ['cost' => 10];
		
		
		public static function get_Hash($data) {
			$hash = password_hash($data, Paranoid::$method, Paranoid::$options);
			return $hash;
		}
		
		public static function try_AD_Login($login, $password) {
			#TODO
			return Paranoid::try_Login($login, $password);
		}
		public static function try_Login($login, $password) {
			#TODO
			if ($login === 'admin' and $password === 'admin') {
				return 0;
			} else {
				return 2;
			}
		}
		public function need_Auth() {
			#TODO
			return false;
		}
		public function need_more_Privilege($p) {
			#TODO
			return false;
		}
		public function get_UserInfo() {
			#TODO
			return array('name' => 'root', 'uac' => 777);
		}
		
	}
?>