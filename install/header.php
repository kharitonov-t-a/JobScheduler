<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script type="text/javascript" src="jquery-ui-1.10.4.custom/js/jquery-1.10.2.js"></script>
		<script type="text/javascript" src="jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.js"></script>
		<link type="text/css" href="jquery-ui-1.10.4.custom/css/smoothness/jquery-ui.css" rel="Stylesheet" />	
		<link type="text/css" href="/css/style.css" rel="Stylesheet" />	
		<script type="text/javascript" src="script.js"></script>
	</head>
<body>
	<div id="header-container">
	<?php 
		include "./classes/user.php";
		$authUser = new User();

		$error = array();
		$alert = array();

		$login = $authUser->isAuth();

		if(!$login){
			if(!empty($_REQUEST['login']) && !empty($_REQUEST['password'])){
				if(!empty($_REQUEST['log_in'])){
					if($authUser->auth($_REQUEST['login'], $_REQUEST['password'])){
						$login = $authUser->isAuth();
					}else{
						$error[] = "Такого пользователя не существует или введен не веный логин и пароль.";
					}
				}elseif(!empty($_REQUEST['reg'])){

					if($authUser->regUser($_REQUEST['login'], $_REQUEST['password'])){
						$alert[] = "Ваш логин " . $_REQUEST['login'] . "<br>Ваш пароль " . $_REQUEST['password'];
						$authUser->auth($_REQUEST['login'], $_REQUEST['password']);
						$login = $authUser->isAuth();
					}else{
						$error[] = "Регистрация не удалась. Возможно пользователь с указанными логином и паролем уже существует.";
					}
					
				}
			}else{
				$error[] = "Логин и пароль обязательные к заполнению поля.";
			}	
		}else{
			if(!empty($_REQUEST['exit'])){
				$authUser->out();
				$login = $authUser->isAuth();
			}elseif(!empty($_REQUEST['del'])){
				if($authUser->delUser($_SESSION['login'])){
					$alert[] = "Пользователь удален.";
					$login = $authUser->isAuth();
				}else{
					$error[] = "Ошибка удаления пользователя.";
				}
			}
		}
	?>
		<div id="auth-container">
			<form action="/" method="post">
				<?php if(!$login){ ?>
					Логин: <input class="ui-corner-all text" type="text" name="login" />
					Пароль: <input class="ui-corner-all text" type="password" name="password" />
					<input type="submit" value="Войти" name="log_in" />
					<input type="submit" value="Зарегистрироваться" name="reg" />
				<?php }else{ ?>
					<input type="submit" value="Выход" name="exit" />
					<?php if(!$authUser->isAdmin()){ ?>
						<input type="submit" value="Удалить пользователя" name="del" />
					<?php } ?>
				<?php } ?>
			</form>
		</div>
	</div>
	<div id="main-container">
		
			<?php if(!empty($error)){ ?>
				<div class="ui-widget">
					<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
						<?php foreach($error as $messeg){ ?>
							<p><?php echo $messeg ?></p>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
			
			<?php if(!empty($alert)){ ?>
				<div class="ui-widget">
					<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
						<?php foreach($alert as $messeg){ ?>
							<p><?php echo $messeg ?></p>
						<?php } ?>
					</div>
				</div>
			<?php } ?>