<?php
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/core.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/class/html_builder.php';
	
	
#	echo $HTML_TOP;
#	echo '<textarea cols="60">';
#	print_r($_POST);
#	echo '</textarea>';
#	echo '<textarea cols="60">';
#	print_r($_SESSION);
#	echo '</textarea>';
	
	
	$HB = new HtmlBuilder();
	
	$MAIN = '';
	$sql_constructed_query = '';
	
	$sql_db_file = 'main.ini';
	$tables_count = 2;
	
	if (isset($_POST['set_main'])) {
		$_SESSION = array();
	}
	
	if (isset($_SESSION['sql_db_file'])) {
		$sql_db_file = $_SESSION['sql_db_file'];
	}
	if (isset($_POST['sql_db_file'])) {
		$sql_db_file = $_POST['sql_db_file'];
		$_SESSION['sql_db_file'] = $sql_db_file;
	}
	if (isset($_SESSION['tables_count'])) {
		$tables_count = $_SESSION['tables_count'];
	}
	if (isset($_POST['tables_count'])) {
		$tables_count = $_POST['tables_count'];
		$_SESSION['tables_count'] = $tables_count;
	}
	settype($tables_count, "integer");
	
	
	$MAIN .= ''
		.'<div class="form">'
			.'<form id="main" action="constructor.php" method="post">'
				.'<table>'
					.'<tr><th>База (*.ini)</th><th>Количество таблиц</th><th>Начать</th></tr>'
					.'<tr>'
						.'<td><input type="text" name="sql_db_file" value="'.$sql_db_file.'" /></td>'
						.'<td><input type="text" name="tables_count" value="'.$tables_count.'" /></td>'
						.'<td><input type="submit" name="set_main" value="Начать" /></td>'
					.'</tr>'
				.'</table>'
			.'</form>'
		.'</div>'
	.'';
	
	$TC = new Connector($sql_db_file);
	$TDB = $TC->get_db();
	
	
	$i = 0;
	$arr_tables[] = null;
	$TDR = $TDB->query("SHOW TABLES;");
	while ($row = $TDR->fetch_row()) {
		$arr_tables[$i++] = $row[0];
	}
	
	$selected_tables[] = null;
	for ($i = 0; $i < $tables_count; $i++) {
		$selected_tables[$i] = $arr_tables[0];
		if (isset($_SESSION['selected_tables'][$i])) {
			$selected_tables[$i] = $_SESSION['selected_tables'][$i];
		}
		if (isset($_POST['selected_tables'][$i])) {
			$selected_tables[$i] = $_POST['selected_tables'][$i];
			$_SESSION['selected_tables'][$i] = $selected_tables[$i];
		}
	}

	$MAIN .= '<div class="form"><form id="tables" action="constructor.php" method="post"><table><tr>';
	for ($i = 0; $i < $tables_count; $i++) {
		$MAIN .= '<td><select name="selected_tables['.$i.']">'
			.$HB->to_select_array($arr_tables, $selected_tables[$i])
			.'</select></td>';
	}
	$MAIN .= '<td><input type="submit" name="set_tables" value="Выбрать таблицы" /></td></tr></table></form></div>';
	
	$selected_columns[][] = null;
	$selected_agregators[] = null;
	$selected_agregator_columns[] = null;
	$selected_group_columns[][] = null;
	
	$agregators[0] = '-none-';
	$agregators[1] = 'COUNT';
	$agregators[2] = 'SUM';
	$agregators[3] = 'AVG';
	$agregators[4] = 'MIN';
	$agregators[5] = 'MAX';
	
	$arr_columns[][] = null;
	
	
	for ($i = 0; $i < $tables_count; $i++) {
		$j = 1;
		$arr_columns[$i][0] = "-none-";
			$TDR = $TDB->query("SHOW COLUMNS FROM ".$selected_tables[$i].";");
			while ($row = $TDR->fetch_row()) {
				$arr_columns[$i][$j++] = $selected_tables[$i].".".$row[0];
			}
		$selected_columns[$i] = null;
		$j = 0;
		if (isset($_SESSION['selected_columns'][$i])) {
			foreach ($_SESSION['selected_columns'][$i] as $item ){
				$selected_columns[$i][$j] = $item;
				$j++;
			}
		}
		$j = 0;
		if (isset($_POST['selected_columns'][$i])) {
			foreach ($_POST['selected_columns'][$i] as $item ){
				$selected_columns[$i][$j] = $item;
				$_SESSION['selected_columns'][$i][$j] = $selected_columns[$i][$j];
				$j++;
			}
		}
		$selected_agregators[$i] = '-none-';
		if (isset($_SESSION['selected_agregators'][$i])) {
			$selected_agregators[$i] = $_SESSION['selected_agregators'][$i];
		}
		if (isset($_POST['selected_agregators'][$i])) {
			$selected_agregators[$i] = $_POST['selected_agregators'][$i];
			$_SESSION['selected_agregators'][$i] = $selected_agregators[$i];
		}
		$selected_agregator_columns[$i] = '-none-';
		if (isset($_SESSION['selected_agregator_columns'][$i])) {
			$selected_agregator_columns[$i] = $_SESSION['selected_agregator_columns'][$i];
		}
		if (isset($_POST['selected_agregator_columns'][$i])) {
			$selected_agregator_columns[$i] = $_POST['selected_agregator_columns'][$i];
			$_SESSION['selected_agregator_columns'][$i] = $selected_agregator_columns[$i];
		}
		$selected_group_columns[$i] = null;
		$j = 0;
		if (isset($_SESSION['selected_group_columns'][$i])) {
			foreach ($_SESSION['selected_group_columns'][$i] as $item ){
				$selected_group_columns[$i][$j] = $item;
				$j++;
			}
		}
		$j = 0;
		if (isset($_POST['selected_group_columns'][$i])) {
			foreach ($_POST['selected_group_columns'][$i] as $item ){
				$selected_group_columns[$i][$j] = $item;
				$_SESSION['selected_group_columns'][$i][$j] = $selected_group_columns[$i][$j];
				$j++;
			}
		}
	}
	
	
	
	
	$MAIN .= '<div class="form"><form id="columns" action="constructor.php" method="post"><table><tr>';
	for ($i = 0; $i < $tables_count; $i++) {
		$MAIN .= '<th> Столбцы для отображения из:<br />'.$selected_tables[$i].'</th>';
	}
	$MAIN .= '</tr><tr>';
	for ($i = 0; $i < $tables_count; $i++) {
		$MAIN .= '<td><select multiple name="selected_columns['.$i.'][]">'
			.$HB->to_multi_select_array($arr_columns[$i], $selected_columns[$i])
		.'</select></td>';
	}
	$MAIN .= '</tr><tr>';
	for ($i = 0; $i < $tables_count; $i++) {
		$MAIN .= '<th> Функция-агрегатор для:<br />'.$selected_tables[$i].'</th>';
	}
	$MAIN .= '</tr><tr>';
	for ($i = 0; $i < $tables_count; $i++) {
		$MAIN .= '<td><select name="selected_agregators['.$i.']">'
			.$HB->to_select_array($agregators, $selected_agregators[$i])
		.'</select></td>';
	}
	$MAIN .= '</tr><tr>';
	for ($i = 0; $i < $tables_count; $i++) {
		$MAIN .= '<th> Столбец агрегирования для:<br />'.$selected_tables[$i].'</th>';
	}
	$MAIN .= '</tr><tr>';
	for ($i = 0; $i < $tables_count; $i++) {
		$MAIN .= '<td><select name="selected_agregator_columns['.$i.']">'
			.$HB->to_select_array($arr_columns[$i], $selected_agregator_columns[$i])
		.'</select></td>';
	}
	$MAIN .= '</tr><tr>';
	for ($i = 0; $i < $tables_count; $i++) {
		$MAIN .= '<th> Столбцы группировки для:<br />'.$selected_tables[$i].'</th>';
	}
	$MAIN .= '</tr><tr>';
	for ($i = 0; $i < $tables_count; $i++) {
		$MAIN .= '<td><select multiple name="selected_group_columns['.$i.'][]">'
			.$HB->to_multi_select_array($arr_columns[$i], $selected_group_columns[$i])
		.'</select></td>';
	}
	$MAIN .= '</tr><tr><td><input type="submit" name="set_columns" value="Выбрать Столбцы" /></td>';
	for ($i = 1; $i < $tables_count; $i++) {
		$MAIN .= '<td></td>';
	}
	$MAIN .= '</tr></table></form></div>';
	
	
	$selected_joins[][] = null;
	
	for ($i = 1; $i < $tables_count; $i++) {
		if (isset($_POST['selected_joins'][$i-1][0])) {
			$_SESSION['selected_joins'][$i-1][0] = $_POST['selected_joins'][$i-1][0];
		}
		if (isset($_POST['selected_joins'][$i-1][1])) {
			$_SESSION['selected_joins'][$i-1][1] = $_POST['selected_joins'][$i-1][1];
		}
		if (isset($_SESSION['selected_joins'][$i-1][0])) {
			$selected_joins[$i-1][0] = $_SESSION['selected_joins'][$i-1][0];
		}
		if (isset($_SESSION['selected_joins'][$i-1][1])) {
			$selected_joins[$i-1][1] = $_SESSION['selected_joins'][$i-1][1];
		}
	}
	
	
	$MAIN .= '<div class="form"><form id="joins" action="constructor.php" method="post"><table>';
	for ($i = 1; $i < $tables_count; $i++) {
		$MAIN .= '<tr><th>Столбец связи '.$selected_tables[$i-1].'</th><th> С '.$selected_tables[$i].'</th></tr>';
		$MAIN .= '<tr><td><select name="selected_joins['.($i-1).'][0]">'
			.$HB->to_select_array($arr_columns[$i-1], $selected_joins[$i-1][0])
			.'</select></td>'
			.'<td><select name="selected_joins['.($i-1).'][1]">'
			.$HB->to_select_array($arr_columns[$i], $selected_joins[$i-1][1])
			.'</select></td></tr>';
		
	}
	
	$MAIN .= '<tr><td><input type="submit" name="set_joins" value="Выбрать связи" /></td><td></td>'
		.'</tr></table></form></div>';
	
#	$arr_all_columns[] = array();
#	foreach ($arr_columns as $item) {
#		$arr_all_columns = array_merge($arr_all_columns, $item);
#	}
	
	
	$selected_filters = array();
	$i = 0;
	if (isset($_SESSION['selected_filters'])) {
		foreach ($_SESSION['selected_filters'] as $item) {
			$selected_filters[$i][0] = $item[0];
			if (isset($item[1])) {
				$selected_filters[$i][1] = $item[1];
				if (isset($item[2])) {
					$selected_filters[$i][2] = $item[2];
				}
			}
			$i++;
		}
	}
	$i = 0;
	if (isset($_POST['selected_filters'])) {
		foreach ($_POST['selected_filters'] as $item) {
			$selected_filters[$i][0] = $item[0];
			$_SESSION['selected_filters'][$i][0] = $selected_filters[$i][0];
			if (isset($item[1])) {
				$selected_filters[$i][1] = $item[1];
				$_SESSION['selected_filters'][$i][1] = $selected_filters[$i][1];
				if (isset($item[2])) {
					$selected_filters[$i][2] = $item[2];
					$_SESSION['selected_filters'][$i][2] = $selected_filters[$i][2];
				}
			}
			$i++;
		}
	}
	
	if (isset($_POST['reset_filters'])) {
		$selected_filters = array();
		$_POST['selected_filters'] = array();
		$_SESSION['selected_filters'] = array();
	}
	
	$MAIN .= '<div class="form"><form id="filters" action="constructor.php" method="post"><table>';
	$MAIN .= '<tr><th>Таблица</th><th>Столбец</th><th>Значение</th><th>Принято</th></tr>';
	$i = 0;
	foreach ($selected_filters as $item) {
		$table = '-none-';
		$column = '-none-';
		$value = '-none-';
		if (isset($item[0])) {
			$table = $item[0];
		}
		if (isset($item[1])) {
			$column = $item[1];
		}
		if (isset($item[2])) {
			$value = $item[2];
		}
		$MAIN .= '<tr><td><select name="selected_filters['.$i.'][0]">'
			.$HB->to_select_array($selected_tables, $table)
			.'</select></td>';
			if ($table != '-none-') {
				$arr_t_columns[0] = '-none-';
				$TDR = $TDB->query('SHOW COLUMNS FROM '.$table.';');
				$ii = 1;
				while ($row = $TDR->fetch_row()) {
					$arr_t_columns[$ii++] = $table.'.'.$row[0];
				}
				$MAIN .= '<td><select name="selected_filters['.$i.'][1]">'
					.$HB->to_select_array($arr_t_columns, $column)
					.'</select></td>';
				if ($column != '-none-') {
					$iii = 0;
					$arr_t_values[] = array();
					$TDR = $TDB->query('SELECT '.$column.' FROM '.$table.' GROUP BY '.$column.';')
						or die('SELECT '.$column.' FROM '.$table.' GROUP BY '.$column.';'.'<br>'.$TDB->error);
					while ($row = $TDR->fetch_row()) {
						$arr_t_values[$iii++] = $row[0];
					}
					$MAIN .= '<td><select name="selected_filters['.$i.'][2]">'
						.$HB->to_select_array($arr_t_values, $value)
						.'</select></td>';
					if($value != '-none-') {
						$MAIN .= '<td>OK</td></tr>';
					} else {
						$MAIN .= '<td><input type="submit" name="->" value="Принять" /></td></tr>';
					}
				} else { 
					$MAIN .= '<td><input type="submit" name="->" value="->" /></td><td></td></tr>';
				}
			} else { 
				$MAIN .= '<td><input type="submit" name="->" value="->" /></td><td></td><td></td></tr>';
			}
		$i++;
	}
	if (isset($_POST['add_filters']) or ($i == 0)) {
		$MAIN .= '<tr><td><select name="selected_filters['.$i.'][0]">'
			.$HB->to_select_array($selected_tables, '-none-')
			.'</select></td><td><input type="submit" name="->" value="->" /></td><td></td></tr>';
	}
	$MAIN .= '<tr><td><input type="submit" name="reset_filters" value="Сбросить фильтры" /></td>'
		.'<td><input type="submit" name="add_filters" value="Добавить фильтр" /></td>'
		.'</tr></table></form></div>';
	
	$sql_constructed_query = 'SELECT ';
	# COLUMNS
	$i = 0;
	foreach ($selected_columns as $arr_item) {
		if (is_array($arr_item)) {
			foreach ($arr_item as $item) {
				if ($item != '-none-') {
					if ($i != 0) {
						$sql_constructed_query .= ', '.$item;
						$i++;
					} else {
						$sql_constructed_query .= $item;
						$i++;
					}
				}
			}
		} else {
			if (!is_null($arr_item) AND ($arr_item != '-none-')) {
				if ($i != 0) {
					$sql_constructed_query .= ', '.$item;
					$i++;
				} else {
					$sql_constructed_query .= $item;
					$i++;
				}
			}
		}
	}
	# AGREGATOR TODO
	
	for ($ii = 0; $ii < $tables_count; $ii++) {
		if ($selected_agregators[$ii] != '-none-') {
			if ($i > 0) {
				$sql_constructed_query .= ', ';
			}
			if ($selected_agregators[$ii] == 'COUNT') {
				$sql_constructed_query .= 'COUNT(*)';
			} else {
				$sql_constructed_query .= $selected_agregators[$ii].'(`'
					.substr(strrchr($selected_agregator_columns[$ii], '.'), 1).'`)';
			}
			$i++;
		}
		
	}
	
	# FROM
	$sql_constructed_query .= ' FROM '.$selected_tables[0];
	for ($i = 1; $i < $tables_count; $i++) {
		$sql_constructed_query .= ' LEFT JOIN '.$selected_tables[$i].' ON '
			.$selected_joins[$i-1][0].' = '.$selected_joins[$i-1][1];
	}
	# WHERE
	$i = 0;
	foreach ($selected_filters as $item) {
		$column = '-none-';
		$value = '-none-';
		if (isset($item[1])) {
			$column = $item[1];
		}
		if (isset($item[2])) {
			$value = $item[2];
		}
		if (($column != '-none-') AND ($value != '-none-')) {
			if ($i == 0) {
				$sql_constructed_query .= ' WHERE '.$column.' = \''.$value.'\'';
				$i++;
			} else {
				$sql_constructed_query .= ' AND '.$column.' = \''.$value.'\'';
				$i++;
			}
			
		}
	}
	# GROUP BY
#	echo '<div>';
#	print_r($selected_group_columns);
#	echo '</div>';
	$i = 0;
	foreach ($selected_group_columns as $arr_item) {
		if (is_array($arr_item)) {
			foreach ($arr_item as $item) {
				if ($item != '-none-') {
					if ($i != 0) {
						$sql_constructed_query .= ', '.$item;
						$i++;
					} else {
						$sql_constructed_query .= ' GROUP BY '.$item;
						$i++;
					}
				}
			}
		} else {
			if (!is_null($arr_item) AND ($arr_item != '-none-')) {
				if ($i != 0) {
					$sql_constructed_query .= ', '.$item;
					$i++;
				} else {
					$sql_constructed_query .= ' GROUP BY '.$item;
					$i++;
				}
			}
		}
	}
	$sql_constructed_query .= ';';
	
	
	#$MAIN .= '<div class="form"><input type="submit" name="get_query" value="СГЕНЕРИРОВАТЬ" />';
	
	$MAIN .= '<div class="form">'
			.'<form id="sql_tmp" action="sql_manager.php" method="post"><table>'
				.'<tr><th>База (*.ini)</th><th>Описание</th><th>Код запроса</th><th>Действия</th></tr>'
				.'<tr>'
					.'<td><input type="text" name="sql_db_file" value="'.$sql_db_file.'" /></td>'
					.'<td><input type="text" name="sql_name" value="'.$sql_db_file.'_'.date('Y-m-d-H-i-s').'" /></td>'
					.'<td><textarea cols="64" name="sql_query">'.$sql_constructed_query.'</textarea></td>'
					.'<td><input type="submit" name="sql_add" value="Сохранить" /></td>'
				.'</tr>'
				.'</table></form>'
		.'</div>'
	.'';
	
	$page = new DefaultPage();
	$page->add_Text($MAIN);
	$page->show();
?>