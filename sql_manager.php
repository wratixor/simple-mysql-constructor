<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/core.php';
	
	$MAIN = '';
	$MC = new Connector('main.ini');
	$MDB = $MC->get_db();
	
	if (isset($_POST['sql_add'])) {
		$sql = 'INSERT INTO `wta_sql_manager` (`sql_db_file`, `sql_name`, `sql_query`) '
			.'VALUES ("'.$_POST['sql_db_file'].'", "'.$_POST['sql_name'].'", "'.$_POST['sql_query'].'");';
		$MDR = $MC->e_query($sql);
		$MAIN .= '<div class="notice">Добавлено</div>';
	}
	
	$MAIN .= '<div class="form">'
		.'<form id="sql_tmp" action="sql_manager.php" method="post"><table>'
		.'<tr><th>База (*.ini)</th><th>Описание</th><th>Код запроса</th><th>Действия</th></tr>'
		.'<tr>'
		.'<td><input type="text" name="sql_db_file" /></td>'
		.'<td><input type="text" name="sql_name" /></td>'
		.'<td><input type="text" name="sql_query" /></td>'
		.'<td><input type="submit" name="sql_add" value="Добавить" /></td>'
		.'</tr>'
		.'</table></form>'
	.'</div>';
	
	if (isset($_POST['tohtml'])) {
		$TC = new Connector($_POST['sql_db_file']);
		#$TDB = $TC->get_db();
		$TDR = $TC->e_query($_POST['sql_query']);
		$MAIN .= '<div class="notice">Предпросмотр</div>';
		$MAIN .= '<div class="table"><table>';
		$MAIN .= '<tr>';
		while($field = $TDR->fetch_field()) {
			$MAIN .= '<th>'.$field->name.'</th>';
		}
		$MAIN .= '</tr>';
		while ($row = $TDR->fetch_row()) {
			$MAIN .= '<tr>';
			foreach ($row as $item) {
				$MAIN .= '<td>'.$item.'</td>';
			}
			$MAIN .= '</tr>';
		}
		$MAIN .= '</table></div>';
	}
	
	if (isset($_POST['tofile'])) {
		$TC = new Connector($_POST['sql_db_file']);
		$TDB = $TC->get_db();
		$TDR = $TDB->query($_POST['sql_query']);
		$MAIN .= '<div class="notice">Создаём файл:</div>';
		$TFS = '';
		while($field = $TDR->fetch_field()) {
			$TFS .= '"'.$field->name.'";';
		}
		$TFS .= PHP_EOL;
		while ($row = $TDR->fetch_row()) {
			foreach ($row as $item) {
				$TFS .= '"'.$item.'";';
			}
			$TFS .= PHP_EOL;
		}
		$file = ''.$_POST['sql_db_file'].'_'.date('Y-m-d-H-i-s').'.csv';
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/tables/'.$file, $TFS);
		$MAIN .= '<div class="notice">Ссылка на файл: <a href="./tables/'.$file.'">'.$file.'</a></div>';
	}
	
	if (isset($_POST['tobase'])) {
		$TC = new Connector($_POST['sql_db_file']);
		$TDR = $TC->e_query($_POST['sql_query']);
		$new_db_name = 's2b_'.date('YmdHis').'_'.stristr($_POST['sql_db_file'], '.ini', true);
		$sql = 'CREATE TABLE `'.$new_db_name.'` (';
		$inserts = array();
		$insert_a = 'INSERT INTO `'.$new_db_name.'` (';
		$i = 0;
		while ($field = $TDR->fetch_field()) {
			if ($i > 0) {
				$sql .= ', ';
				$insert_a .= ', ';
			}
			$sql .= '`'.$field->table.'_'.$field->name.'` ';
			$type = $TC->type2txt($field->type);
			$sql .= $type;
			if ($type != 'TIMESTAMP') {
                $sql .= '('.$field->length.')';
			}
			$insert_a .= '`'.$field->table.'_'.$field->name.'` ';
			$i++;
		}
		$sql .= ')ENGINE = InnoDB;';
		$insert_a .= ') VALUES (';
		#echo '<textarea>'.$sql.'</textarea>';
		$MDR = $MC->e_query($sql);
		
#		("'.$_POST['sql_db_file'].'", "'.$_POST['sql_name'].'", "'.$_POST['sql_query'].'");';
		$insert_c = ');';
		while ($row = $TDR->fetch_row()) {
			$i = 0;
			$insert_b = '';
			foreach ($row as $item) {
				if ($i > 0) {
					$insert_b .= ', ';
				}
				$insert_b .= '"'.$item.'"';
				$i++;
			}
			$inserts[] = $insert_a.$insert_b.$insert_c;
		}
#		print_r ($inserts);
		foreach ($inserts as $sql) {
			$MDR = $MC->e_query($sql);
		}
		
		$MAIN .= '<div class="notice">Сохранено в таблицу: "'.$new_db_name.'"!</div>';
		
	}
	
	if (isset($_POST['update'])) {
		$sql = 'UPDATE `wta_sql_manager` '
			.'SET `sql_db_file` = "'.$_POST['sql_db_file'].'", `sql_name` = "'.$_POST['sql_name'].'", `sql_query` = "'.$_POST['sql_query'].'" '
			.'WHERE `sql_id` = '.$_POST['sql_id'].';';
		$MDR = $MC->e_query($sql);
		$MAIN .= '<div class="notice">Обновлено!</div>';
		
	}
	
	if (isset($_POST['delete'])) {
		$sql = 'DELETE FROM `wta_sql_manager` WHERE `sql_id` = '.$_POST['sql_id'].';';
		$MDR = $MC->e_query($sql);
		$MAIN .= '<div class="notice">Удалено!</div>';
		
	}
	
	
	
	$sql = 'SELECT * FROM `wta_sql_manager`;';
	$MDR = $MC->e_query($sql);
	$MAIN .= '<div class="table"><table>'
		.'<tr><th>ID</th><th>База (*.ini)</th><th>Описание</th><th>Действия</th>'
		.'<th>Дата изменения</th><th>Код запроса</th></tr>';
	while ($row = $MDR->fetch_array(MYSQLI_ASSOC)) {
		$MAIN .= '<tr>'
			.'<td>'
				.'<form id="sql_'.$row['sql_id'].'" action="sql_manager.php" method="post" style="display: none;"></form>'
				.'<input form="sql_'.$row['sql_id'].'" type="hidden" name="sql_id" value="'.$row['sql_id'].'" />'
				.$row['sql_id']
			.'</td>'
			.'<td>'
				.'<input form="sql_'.$row['sql_id'].'" type="text" name="sql_db_file" value="'.$row['sql_db_file'].'" />'
			.'</td>'
			.'<td>'
				.'<input form="sql_'.$row['sql_id'].'" type="text" name="sql_name" value="'.$row['sql_name'].'" />'
			.'</td>'
			.'<td>'
				.'<input form="sql_'.$row['sql_id'].'" type="submit" name="tohtml" value="Просмотр" />'
				.'<input form="sql_'.$row['sql_id'].'" type="submit" name="tofile" value="В файл" />'
				.'<input form="sql_'.$row['sql_id'].'" type="submit" name="tobase" value="В базу" />'
				.'<input form="sql_'.$row['sql_id'].'" type="submit" name="update" value="Изменить" />'
				.'<input form="sql_'.$row['sql_id'].'" type="submit" name="delete" value="!Удалить" />'
			.'</td>'
			.'<td>'
				.$row['sql_datecreate'].
			'</td>'
			.'<td>'
				.'<input form="sql_'.$row['sql_id'].'" type="text" name="sql_query" value="'.$row['sql_query'].'" />'
			.'</td>'
		.'</tr>';
	}
	$MAIN .= '</table>';
	$page = new DefaultPage();
	$page->add_Text($MAIN);
	$page->show();
	
	#echo "----------------------------------------------------------".PHP_EOL;
	#print_r($page);

	
?>
