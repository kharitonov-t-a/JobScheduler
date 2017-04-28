<?php session_start();
include "../classes/importances.php";
include "../classes/user.php";
include "../classes/tasks.php";

$importances = new Importances();
$authUser = new User();
$obTask = new Tasks();


$resLoginId = $authUser->getIdUser();
$loginId = $resLoginId->fetch_assoc();

$error = array();

if(!empty($_REQUEST['del'])){
	if(!$obTask->delTasks($_REQUEST['del'], $loginId['id'])){
		$error = "Удаление не удалось";
	}
}
	
if(!empty($_REQUEST['add'])){
	if(!$obTask->AddTasks($_REQUEST['title'], $loginId['id'], $_REQUEST['importances'], $_REQUEST['description'], $_REQUEST['parrentTask'])){
		$error = "Добавление не удалось";
	}
}


if(!empty($_REQUEST['finish_edit'])){
	if(!$obTask->editTasks($loginId['id'], $_REQUEST['finish_edit'], $_REQUEST['title'], $_REQUEST['importance_id'], $_REQUEST['description'], $_REQUEST['date_create'], $_REQUEST['link_task'])){
		$error = "Редактирование не удалось";
	}
}

if(!empty($_REQUEST['done'])){
	if(!$obTask->doneTasks($_REQUEST['done'])){
		$error = "Завершить задачу не удалось";
	}
}



$resListImportances = $importances->listImportances($loginId['id']);

if($resListImportances!=false){
	while($row = $resListImportances->fetch_assoc()){
	
		$arListImportances[$row['id']] = $row;
	}
}


if(!empty($_REQUEST['sort'])) $_SESSION['sort'] = $_REQUEST['sort'];
if(!empty($_REQUEST['side'])) $_SESSION['side'] = $_REQUEST['side'];
if(!empty($_REQUEST['filter'])) $_SESSION['filter'] =  $_REQUEST['filter'];
if(empty($_REQUEST['filter'])&& empty($_SESSION['filter'])) $_SESSION['filter'] = 'filter2';
if(!empty($_REQUEST['parrent'])) $_SESSION['parrent'] =  $_REQUEST['parrent'];
if(empty($_REQUEST['parrent'])&& empty($_SESSION['parrent'])) $_SESSION['parrent'] = 'parrent2';
if(isset($_REQUEST['search'])){
	$_SESSION['search'] = $_REQUEST['search'];
	$_SESSION['currentPage'] = 0;
}
if(empty($_SESSION['search'])) $_SESSION['search'] = '';
if(isset($_REQUEST['filterImportances'])) $_SESSION['filterImportances'] = $_REQUEST['filterImportances'];
if(empty($_SESSION['filterImportances'])) $_SESSION['filterImportances'] = '';
if(isset($_REQUEST['filterDateCreate'])) $_SESSION['filterDateCreate'] = $_REQUEST['filterDateCreate'];
if(empty($_SESSION['filterDateCreate'])) $_SESSION['filterDateCreate'] = '';
if(empty($_SESSION['currentPage'])) $_SESSION['currentPage'] = 0;
if(empty($_SESSION['countTasks'])) $_SESSION['countTasks'] = 0;
if(!empty($_REQUEST['page'])){
	$_SESSION['countTasks'] += $_REQUEST['page'];
	if($_SESSION['countTasks']<0) $_SESSION['countTasks'] = 0;
	$_SESSION['currentPage'] = 0;
}
if(!empty($_REQUEST['toRight'])) $_SESSION['currentPage']++;
if(!empty($_REQUEST['toLeft'])) $_SESSION['currentPage']--;
if(!empty($_REQUEST['sort-parrent'])){
	$_SESSION['currentPage'] = 0;
	$_SESSION['countTasks'] = 0;
}


$resListTasks = $obTask->listTasks($loginId['id'],  $_SESSION['sort'], $_SESSION['side'], $_SESSION['filter'], $_SESSION['search'], $_SESSION['filterImportances'], $_SESSION['filterDateCreate'], 0, 0, 'parrent2');
$arListTasks=array();
if($resListTasks!=false){
	while($row = $resListTasks->fetch_assoc()){
		$arListTasks[$row['id']] = $row['title'];
	}
}

$resListTasks = $obTask->listTasks($loginId['id'],  $_SESSION['sort'], $_SESSION['side'], $_SESSION['filter'], $_SESSION['search'], $_SESSION['filterImportances'], $_SESSION['filterDateCreate'], 0, 0, $_SESSION['parrent']);

$countTasks = $resListTasks->num_rows;
if($countTasks<$_SESSION['countTasks']) $_SESSION['countTasks'] = $countTasks;

if($countTasks>$_SESSION['countTasks'] || $_SESSION['countTasks']>0){
	$resListTasks = $obTask->listTasks($loginId['id'],  $_SESSION['sort'], $_SESSION['side'], $_SESSION['filter'], $_SESSION['search'], $_SESSION['filterImportances'], $_SESSION['filterDateCreate'], $_SESSION['currentPage'], $_SESSION['countTasks'], $_SESSION['parrent']);
}
//---------------------------------------------выборка со всеми фильтрами сортировками и т.п.---------------------------------------------//

$arAllListTasks = array();
if($resListTasks){
	if(!empty($_REQUEST['sort-parrent'])){//сортировка дочерних и родительских // при большой таблице надо будет делать запрос
		$arParrentsListTasks = array();
		$arDouterListTasks = array();
		while($row = $resListTasks->fetch_assoc()){
			if($row['link_task']==0){
				$arParrentsListTasks[$row['id']]=$row;
			}else{
				$arDouterListTasks[$row['link_task']][]=$row;
			}
		}
		
		foreach($arParrentsListTasks as $id=>$row){
			$arAllListTasks[]=$row;
			if(!empty($arDouterListTasks[$id])){
			$arAllListTasks = array_merge($arAllListTasks, $arDouterListTasks[$id]);}
		}
	}else{
		while($row = $resListTasks->fetch_assoc()){
			$arAllListTasks[]=$row;
		}
	}
}

?>
<div id="list_tasks">
	<div style="display: inline-block">
		<div id="filter">
			<input type="radio" id="filter1" name="radio" <?php if($_SESSION['filter']=="filter1") echo 'checked="checked"'; ?>/><label for="filter1">TODO</label>
			<input type="radio" id="filter2" name="radio" <?php if($_SESSION['filter']=="filter2") echo 'checked="checked"'; ?> /><label for="filter2">TODO and DONE</label>
			<input type="radio" id="filter3" name="radio" <?php if($_SESSION['filter']=="filter3") echo 'checked="checked"'; ?>/><label for="filter3">DONE</label>
		</div>
		<div id="search">
			<form action="/" method="post">
				Поиск: <input class="ui-corner-all text" type="text" name="search" value="<?php echo $_SESSION['search'] ?>"/>
				<input type="submit" value="Искать!" />
			</form>
		</div>
		<div id="parrent">
			<input type="radio" id="parrent1" name="radio2" <?php if($_SESSION['parrent']=="parrent1") echo 'checked="checked"'; ?>/><label for="parrent1">Родительские</label>
			<input type="radio" id="parrent2" name="radio2" <?php if($_SESSION['parrent']=="parrent2") echo 'checked="checked"'; ?> /><label for="parrent2">Все</label>
			<input type="radio" id="parrent3" name="radio2" <?php if($_SESSION['parrent']=="parrent3") echo 'checked="checked"'; ?>/><label for="parrent3">Дочерние</label>
		</div>
		<div id="sort-parrent">
			<a class="sort-parrent">Сортировка деревом по родителю</a>
		</div>
	</div>
	<div style="display: block">
		<div id="filterImportances">
			Фильтр Важность: <select name="filterImportances">
			<?php 
				foreach($arListImportances as $row){
					echo "<option value='" . $row['id'] . "'";
					if($row['id'] == $_SESSION['filterImportances']) echo " selected ";
					echo ">" . $row['title'] . "</option>";
				}
			?>
			</select>
			<div class="set">
				<a class="filterImportances" data-filter="Y" >Фильтровать</a>
				<a class="filterImportances" data-filter="N" <?php if(!empty($_SESSION['filterImportances'])) echo "style='background: bisque'" ?> >Сбросить Фильтр</a>
			</div>
		</div>
		<div id="filterDateCreate">
			Дата создания: <input type="text" class="ui-corner-all" id="datepicker" name="filterDateCreate" value="<?php echo $_SESSION['filterDateCreate'] ?>" />
			<div class="set">
				<a class="filterDateCreate" data-filter="Y" >Фильтровать</a>
				<a class="filterDateCreate" data-filter="N" <?php if(!empty($_SESSION['filterDateCreate'])) echo "style='background: bisque'" ?> >Сбросить Фильтр</a>
			</div>
		</div>
	</div>
	<div style="display: inline-block; margin-bottom: 15px; margin-top: 5px">
		Показывать по <?php echo $_SESSION['countTasks'] ?> задач(и) на странице. <div class="set"><a class="page" data-page="1">+</a><a class="page" data-page="-1">-</a></div>
	</div>
<?php
if($resListTasks){
?>

<?php if(!empty($_REQUEST['edit'])){?>
	<form id="form_edit_tasks" action="/" method="post">
<?php } ?>
<table border="1" align="center" cellpadding="10">
	<tr>
		<td><a class='action' data-sort='id' data-side='<?php if($_SESSION['sort']=='id'){echo $_SESSION['side'];}else{echo "asc";}?>'>ID</a></td>
		<td><a class='action' data-sort='title' data-side='<?php if($_SESSION['sort']=='title'){echo $_SESSION['side'];}else{echo "asc";}?>'>Название</a></td>
		<td><a class='action' data-sort='importance_id' data-side='<?php if($_SESSION['sort']=='importance_id'){echo $_SESSION['side'];}else{echo "asc";}?>'>Важность</a></td>
		<td><a class='action' data-sort='description' data-side='<?php if($_SESSION['sort']=='description'){echo $_SESSION['side'];}else{echo "asc";}?>'>Описание</a></td>
		<td><a class='action' data-sort='date_create' data-side='<?php if($_SESSION['sort']=='date_create'){echo $_SESSION['side'];}else{echo "asc";}?>'>Дата создания</a></td>
		<td><a class='action' data-sort='done' data-side='<?php if($_SESSION['sort']=='done'){echo $_SESSION['side'];}else{echo "asc";}?>'>Состояние</a></td>
		<td><a class='action' data-sort='link_task' data-side='<?php if($_SESSION['sort']=='link_task'){echo $_SESSION['side'];}else{echo "asc";}?>'>Родитель</a></td>
		<td>Действие</td>
	</tr>
<?php
	foreach($arAllListTasks as $row){
		if($_REQUEST['edit'] == $row['id']){
			echo "<tr>
				<td>" . $row['id'] . "</td>
				<td><input class='ui-corner-all text' type='text' name='title' value='" . $row['title'] . "'/></td>
				<td><select size='5' name='importance_id'>";
					foreach($arListImportances as $rowImportances){
						echo "<option value='" . $rowImportances['id'] . "'";
						if($rowImportances['id'] == $row['importance_id']) echo "selected";
						echo ">" . $rowImportances['title'] . "</option>";
					}
				echo "</select></td>
				<td><textarea class='ui-corner-all text' name='description'> " . $row['description'] . "</textarea></td>
				<td><input class='ui-corner-all text' type='text' name='date_create' value='" . $row['date_create'] . "'/></td>
				<td>" . (($row['done']==1)?"DONE":"TODO") . "</td>
				<td><input class='ui-corner-all text' type='text' name='link_task' value='" . $row['link_task'] . "'/></td>
				<td><a class='delete_tasks' href='tasks/index.php' data-del='" . $row['id'] . "'>Удалить</a>";
				if($row['done']!=1) echo " / <a class='done_tasks' href='tasks/index.php' data-id='" . $row['id'] . "'>Завершить</a>";
				echo"<input type='hidden' name='finish_edit' value='" . $row['id'] . "' /></td>
			</tr>";		
		}else{
			echo "<tr";
			if($row['link_task']!=0) echo " style='background: lightyellow'";
			echo ">
				<td>" . $row['id'] . "</td>
				<td>" . $row['title'] . "</td>
				<td>" . $arListImportances[$row['importance_id']]['title'] . "</td>
				<td>" . $row['description'] . "</td>
				<td>" . $row['date_create'] . "</td>
				<td>" . (($row['done']==1)?"DONE":"TODO") . "</td>
				<td>" . $row['link_task'] . "</td>
				<td><a class='delete_tasks' href='tasks/index.php' data-del='" . $row['id'] . "'>Удалить</a> /
				<a class='edit_tasks' href='tasks/index.php' data-id='" . $row['id'] . "'>Редактировать</a>";
				if($row['done']!=1) echo " / <a class='done_tasks' href='tasks/index.php' data-id='" . $row['id'] . "'>Завершить</a>";
				echo"</td>
			</tr>";
		}
	}
?>
</table>
<?php 

if($countTasks>$_SESSION['countTasks'] && $_SESSION['countTasks']>0){//вывод постранички
	echo"<div class='navigation'>";
	if($_SESSION['currentPage']>0){
		echo"<a class='toLeft'>&laquo;</a>";
	}
	if(($_SESSION['currentPage']>0) || (($_SESSION['currentPage']*$_SESSION['countTasks'])<($countTasks-$_SESSION['countTasks']))){
		echo "<p class='centrePage'>" . ($_SESSION['currentPage']+1) . "</p>";
	}
	if(($_SESSION['currentPage']*$_SESSION['countTasks'])<($countTasks-$_SESSION['countTasks'])){
		echo"<a class='toRight'>&raquo;</a>";
	}
	echo"</div>";
}
?>
<?php if(!empty($_REQUEST['edit'])){?>
	<input type="submit" value="Изменить задание" />
	<a class="cencel_edit">Отменить изменение</a>
	</form>
<?php } ?>
<?php
}else{
	$error = "Нет записей";
}
if(!empty($error)){
?>
	<div class="ui-widget">
		<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
			<p><?php echo $error ?></p>
		</div>
	</div>
<?php
}

?>
<a class='add_tasks' href='tasks/index.php'>Добавить</a>
<form id="form_add_tasks" style="display: none" action="/" method="post">
	<div>
		Название: <input class="ui-corner-all text" type="text" name="title" />
	</div>
	<div>
		Описание: <textarea class="ui-corner-all text" name="description" />
	</div>
	<div>
		Важность: <select style="top: 0;" size="5" name="importances">
			<?php 
			foreach($arListImportances as $row){
				echo "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
			}
			?>
		</select>
	</div>
	<div>
		Родительская задача: <select style="top: 0;" name="parrentTask">
			<option value=''>Нет</option>
			<?php 
			foreach($arListTasks as $key=>$row){
				echo "<option value='" . $key . "'>"  . $key . "[" . $row . "]</option>";
			}
			?>
		</select>
	</div>
		<input type="hidden" name="add" value="Y" />
		<input type="submit" value="Добавить задачу" />
</form>
</div>
<?php
unset($authUser, $importances, $obTask);
?>

<script>
	$("input:submit, a.cencel_edit, a.page").button();
	$("#filter, div.set, #parrent, #sort-parrent").buttonset();
	$("#datepicker").datepicker({changeMonth:true, changeYear:true, dateFormat: 'yy-mm-dd'});
</script>