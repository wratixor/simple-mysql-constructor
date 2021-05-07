<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/module/html_main.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/class/connector.php';
	$RN = "\r\n";
	$MAIN = '';
	
	
	#Подключения
	
	$db_param[] = null;
	$db_param['db'] = 'glpi';
	$db_param['server'] = '127.0.0.1';
	$db_param['username'] = 'root';
	$db_param['password'] = '';
	$db_param['port'] = 3306;
	$db_param['charset'] = 'utf8';
	$db_param['file'] = 'config_1.ini';
	
	if (isset($_POST['save'])) {
		$db_param['db'] = $_POST['db'];
		$db_param['server'] = $_POST['server'];
		$db_param['username'] = $_POST['username'];
		$db_param['password'] = $_POST['password'];
		$db_param['port'] = $_POST['port'];
		settype($db_param['port'], "integer");
		$db_param['charset'] = $_POST['charset'];
		$db_param['file'] = $_POST['file'];
		$ini_text = ''
			.'db = '.$db_param['db'].PHP_EOL
			.'server = '.$db_param['server'].PHP_EOL
			.'username = '.$db_param['username'].PHP_EOL
			.'password = '.$db_param['password'].PHP_EOL
			.'port = '.$db_param['port'].PHP_EOL
			.'charset = '.$db_param['charset'].PHP_EOL
		.'';
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/configs/'.$db_param['file'], $ini_text);
		$MAIN .= '<h2>Сохранено</h2>';
	}
	if (isset($_POST['load'])) {
		$db_param['file'] = $_POST['file'];
		$arr = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/configs/'.$db_param['file']);
		$db_param['db'] = $arr['db'];
		$db_param['server'] = $arr['server'];
		$db_param['username'] = $arr['username'];
		$db_param['password'] = $arr['password'];
		$db_param['port'] = $arr['port'];
		settype($db_param['port'], "integer");
		$db_param['charset'] = $arr['charset'];
		$MAIN .= '<h2>Загружено</h2>';
	}
	
	$MAIN .= ''
		.'<div class="form">'
		.'<form action="configurator.php" method="post">'
		.'<table><tr><td>'
		.'<p>База: <input type="text" name="db" value="'
		.$db_param['db']
		.'" /></p>'
		.'<p>Сервер: <input type="text" name="server" value="'
		.$db_param['server']
		.'" /></p>'
		.'<p>Порт: <input type="text" name="port" value="'
		.$db_param['port']
		.'" /></p>'
		.'</td><td>'
		.'<p>Логин: <input type="text" name="username" value="'
		.$db_param['username']
		.'" /></p>'
		.'<p>Пароль: <input type="text" name="password" value="'
		.$db_param['password']
		.'" /></p>'
		.'<p>Кодировка: <input type="text" name="charset" value="'
		.$db_param['charset']
		.'" /></p>'
		.'</td><td>'
		.'<p>Файл: <input type="text" name="file" value="'
		.$db_param['file']
		.'" /></p>'
		.'<p><input type="submit" name="load" value="Загрузить" /></p>'
		.'<p><input type="submit" name="save" value="Сохранить" /></p>'
		.'</td></tr></table>'
		.'</form>'
		.'</div>'
	.'';
	
	#Внутренние ресурсы
	
	if (isset($_POST['recreate_sql'])) {
		$MC = new Connector('main.ini');
		$MDB = $MC->get_db();
		$sql = 'DROP TABLE `wta_sql_manager`;';
		$MDR = $MDB->query($sql);
		$sql = 'CREATE TABLE `wta_sql_manager` ('
			.'`sql_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, '
			.'`sql_db_file` TINYTEXT NOT NULL, '
			.'`sql_name` TINYTEXT DEFAULT "no_name", '
			.'`sql_query` TEXT NOT NULL, '
			.'`sql_datecreate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
		.')ENGINE = InnoDB;';
		$MDR = $MDB->query($sql);
	}
	
	$MAIN .= '<div class="form">'
		.'<form action="configurator.php" method="post">'
		.'<p><input type="submit" name="recreate_sql" value="Пересоздать таблицу запросов" /></p>'
		.'</form>'
	.'</div>';
	
	echo $HTML_TOP;
	echo $MAIN;
	echo $HTML_BOTTOM;
?>