<?php session_start();
include "../classes/importances.php";
include "../classes/user.php";

$importances = new Importances();
$authUser = new User();

$resLoginId = $authUser->getIdUser();
$loginId = $resLoginId->fetch_assoc();

$error = array();

if(!empty($_REQUEST['del'])){
	if(!$importances->delImportances($_REQUEST['del'], $loginId['id'])){
		$error = "Удаление не удалось";
	}
}


if(!empty($_REQUEST['add'])){
	if(!$importances->AddImportances($_REQUEST['title'], $_REQUEST['value'], $loginId['id'])){
		$error = "Добавление не удалось";
	}
}


$resListImportances = $importances->listImportances($loginId['id']);
?>
<div id="list_importances">
<?php
if($resListImportances){
?>

<table border="1" align="center" cellpadding="10">
	<tr>
		<td>№</td>
		<td>Название</td>
		<td>Важность</td>
		<td>Действие</td>
	</tr>
<?php
		$i=0;
		while($row = $resListImportances->fetch_assoc()){
			$i++;
			echo "<tr>
				<td>" . $i . "</td>
				<td>" . $row['title'] . "</td>
				<td>" . $row['value'] . "</td>
				<td><a class='delete_importances' href='importances/index.php' data-del='" . $row['id'] . "'>Удалить</a></td>
			</tr>";
		}
?>
</table>

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
unset($authUser, $importances);
?>
<a class='add_importances' href='importances/index.php'>Добавить</a>
<form id="form_add_importances" style="display: none" action="/" method="post">
		Название: <input class="ui-corner-all text" type="text" name="title" />
		Важность: <input class="ui-corner-all text" type="text" name="value" />
		<input type="hidden" name="add" value="Y" />
		<input type="submit" value="Добавить приоритет" />
</form>
</div>
<script>
	$("input:submit").button();
</script>