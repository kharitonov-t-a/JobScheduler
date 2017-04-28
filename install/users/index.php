<?php session_start();
include "../classes/user.php";
$authUser = new User();

$error = array();

if(!empty($_REQUEST['del'])){
	if(!$authUser->delUser($_REQUEST['del'])){
		$error = "Удаление не удалось";
	}
}


$resListUser = $authUser->listUser();
?>
<div id="list_users">
<?php
if($resListUser){
?>

<table border="1" align="center" cellpadding="10">
	<tr>
		<td>№</td>
		<td>Логин</td>
		<td>Действие</td>
	</tr>
<?php
		$i=0;
		while($row = $resListUser->fetch_assoc()){
			$i++;
			echo "<tr>
				<td>" . $i . "</td>
				<td>" . $row['login'] . "</td>
				<td><a class='delete_user' href='users/index.php' data-del='" . $row['login'] . "'>Удалить</a></td>
			</tr>";
		}
?>
</table>

<?php
}else{
	$error = "Пользователи закончились";
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
unset($authUser);
?>
</div>