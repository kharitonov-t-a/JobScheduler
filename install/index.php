<?php
include "header.php";
?>
<?php if($authUser->isAuth()){ ?>
	<div id="tabs">
		<ul>
			<?php if($authUser->isAdmin()){ ?>
				<li><a href="/users/index.php">Список пользователей</a></li>
			<?php } ?>
			<li><a href="/tasks/index.php">Список задач</a></li>
			<li><a href="/importances/index.php">Значения приоритетов (важность задачи)</a></li>
		</ul>
	</div>
<?php } ?>


<?php
include "footer.php";
?>