<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/connector.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/paranoid.php';
	
	$MC = new Connector('main.ini');
	$P = new Paranoid();
	
	$user = 'admin';
	$password = 'admin';
	$lvl = 777;
	
	
	
	#$pass_hash = Paranoid::get_Hash($password);
	
	#$query = 'INSERT INTO `wta_users` (`user_name`, `user_pass`, `user_lvl`) VALUES (\''.$user.'\', \''.$pass_hash.'\', '.$lvl.')';
	
	#$MC->e_query($query);
	
?>