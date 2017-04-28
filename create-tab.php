<?php header('Content-type: text/html; charset=windows-1251')?>
<link href="/create/st.css" rel="stylesheet" type="text/css">
<?php
include "./classes/master.php";
include "./classes/user.php";
$master = new Master();
$user = new User();

if($_REQUEST['step']==1){

	$arQuery = array(
		"todo_tasks" => "CREATE TABLE todo_tasks (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			title VARCHAR(50),
			date_create DATE,
			importance_id INT,
			description VARCHAR(255),
			user_id INT,
			done BOOL DEFAULT 0,
			link_task INT DEFAULT 0
		);",
		"todo_importances" => "CREATE TABLE todo_importances (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			title VARCHAR(50),
			value INT,
			user_id INT
		);",
		"todo_users" => "CREATE TABLE todo_users (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			login VARCHAR(50) UNIQUE,
			password VARCHAR(50)
		);",
	);

	foreach($arQuery as $key => $query){
		print_r($master->AddTable($key, $query));
	}
	
	if($user->regUser('admin', 'admin')){
		echo "Добавлен аккант администратора. Логин:admin Пароль:admin";
	}else{
		echo "Ошибка добавления акканта администратора.";
	}
	
	$master->Lowering($_SERVER['DOCUMENT_ROOT']."/install/", $_SERVER['DOCUMENT_ROOT']."/");
	
	
	
	?>  
	<br/><a href='index.php'>Конец</a>
	
<?php } ?>








