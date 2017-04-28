<?php header('Content-type: text/html; charset=windows-1251')?>
<link href="/create/st.css" rel="stylesheet" type="text/css">
<?php 
function crdb()
{
	$HOST = htmlspecialchars($_POST['name_server']); 
	$USER = htmlspecialchars($_POST['login']);
	$PASS = htmlspecialchars($_POST['pass']);
	$DB = htmlspecialchars($_POST['name_db']); 
	$CONFIG = "config.php"; 
    if(!empty($HOST) && !empty($USER) && !empty($DB))
	{

		$mysqli = new mysqli($HOST, $USER, $PASS);

		if($mysqli->connect_errno)
		{
			return "<strong>Невозможно подключение к серверу.</strong><br> <br>
                   <p align=left><b> Возможные причины:</b><br>
					1. Не правильно введён пароль. (по умолчанию пороль отсутствует)<br>
                    2. Имя сервера введено не верно.<br>
                    3. Логин доступа к серверу базы данных MySQL не идентифицирован.<br>
					Connect failed: %s\n" . $mysqli->connect_errno . "</p>";
		}
		$queryResult = $mysqli->query("CREATE DATABASE $DB");
		if(!$queryResult)
		{
			return "<strong>Невозможно создать базу данных.</strong><br> <br>
                   <p align=left><b> Возможные причины:</b><br>
					База данных уже существует, создана ранее.</p>";
		}
?>
<?php
		
		if (!$mysqli->select_db($DB))
		{
			return $mysqli->error();
		}
		$mysqli->query('SET NAMES cp1251;');
		// Создаём конфигурационный файл		
		$data = '<?php
			$HOST = "' . $HOST . '";
			$USER = "' . $USER . '";
			$PASS = "' . $PASS . '";
			$DB = "' . $DB . '";

			$mysqli = new mysqli($HOST, $USER, $PASS, $DB);

			if($mysqli->connect_errno){
				printf("Connect failed: %s\n", $mysqli->connect_error);
				exit();
			}
		?>';
		$hd = fopen($CONFIG,"w");
		$e = fwrite($hd, $data);
		if($e == -1)
		{
		   return "Ошибка. Конфигурационный файл не создан.";	
		}
		return "<span class='green'>База данных \"$DB\" успешно создана.</span><br>
                                    <a href='create-tab.php?step=1'>Далее</a>";
	}
	else
	{
	   return "Не все поля заполнены.";	
	}
}
if($_POST['button'] == "Создать")
{
 $err = crdb();
}
?>



<form method="post" action="">
  <div class="centers">
    <br>
     <table align="center" width="483" border="0" cellpadding="5" cellspacing="5">
      <tr>
        <td colspan="2" align="center"><strong> СОЗДАТЬ БАЗУ ДАННЫХ </strong></td>
      </tr>
      <tr>
        <td width="224" align="right"><span class='red'>*</span>Имя SQL сервера:</td>
        <td width="227" align="left"><input name="name_server" type="text" value="localhost" size="30" maxlength="45"></td>
      </tr>
      <tr>
        <td align="right"><span class='red'>*</span>Логин :</td>
        <td><input name="login" type="text"  value="root"  size="20" maxlength="25"></td>
      </tr>
      <tr>
        <td align="right">Пароль:</td>
        <td><input name="pass" type="text" size="20" maxlength="20"></td>
      </tr>
      <tr>
        <td align="right"><span class='red'>*</span>Имя БД:</td>
        <td><input name="name_db" type="text" value="<?php echo $DB; ?>" size="30" maxlength="30"></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td><label>
          <input type="submit" name="button" id="button" value="Создать" class="buts">
        </label></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><span class='red'><?php echo $err; ?></span></td>
      </tr>
    </table>
  </div>
</form>
