<?php
 //Запускаем сессии

/** 
 * Класс для авторизации
 * @author дизайн студия ox2.ru 
 */ 
class User{
    /**
     * Проверяет, авторизован пользователь или нет
     * Возвращает true если авторизован, иначе false
     * @return boolean 
     */
    public function isAuth(){
        if (!empty($_SESSION["is_auth"])){ //Если сессия существует
            return $_SESSION["is_auth"]; //Возвращаем значение переменной сессии is_auth (хранит true если авторизован, false если не авторизован)
        }else 
			return false; //Пользователь не авторизован, т.к. переменная is_auth не создана
    }
	
	public function isAdmin(){
        if ($_SESSION["login"] == 'admin'){
            return true;
        }else
			return false;
    }
    
    /**
     * Авторизация пользователя
     * @param string $login
     * @param string $passwors 
     */
    public function auth($login, $password){
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		$queryResult = $mysqli->query("SELECT * FROM todo_users WHERE login='" . $mysqli->real_escape_string($login) . "' AND password='" . MD5($mysqli->real_escape_string($password)) . "';");
		$mysqli->close();

		if($queryResult->num_rows == 1){ //Если логин и пароль введены правильно
            $_SESSION["is_auth"] = true; //Делаем пользователя авторизованным
            $_SESSION["login"] = $login; //Записываем в сессию логин пользователя
            return true;
        }else{ //Логин и пароль не подошел
            $_SESSION["is_auth"] = false;
            return false; 
        }
    }
	
	
	
	public function regUser($login, $password){
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
	
		$queryResult = $mysqli->query("INSERT INTO todo_users (login, password) VALUES('" . $mysqli->real_escape_string($login) . "', '" . MD5($mysqli->real_escape_string($password)) . "');");
		$mysqli->close();

		return $queryResult;
    }
    
    /**
     * Метод возвращает логин авторизованного пользователя 
     */
    public function getLogin() {
        if ($this->isAuth()) { //Если пользователь авторизован
            return $_SESSION["login"]; //Возвращаем логин, который записан в сессию
        }
    }
	
    public function getIdUser() {
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		$login = $this->getLogin();
		$queryResult = $mysqli->query("SELECT id FROM todo_users WHERE login='" . $mysqli->real_escape_string($login) . "';");
		$mysqli->close();
		if($queryResult->num_rows>0){
			return $queryResult;
		}else{
			return false;
		}
	}
	
	
    
    
    public function out(){
        $_SESSION = array(); //Очищаем сессию
        session_destroy(); //Уничтожаем
    }
	
	
	public function delUser($login){
		if($_SESSION["login"] == $login || $this->isAdmin()){
			include $_SERVER['DOCUMENT_ROOT']."/config.php";
			$queryResult = $mysqli->query("DELETE FROM todo_users WHERE login='" . $mysqli->real_escape_string($login) . "';");
			$mysqli->close();
			if($queryResult){ //Если логин и пароль введены правильно
				if($_SESSION["login"] == $login)
					$this->out();
				return true;
			}else{ //Логин и пароль не подошел
				return false; 
			}
		}else{
			return false;
		}
    }
	
    public function listUser(){
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		$queryResult = $mysqli->query("SELECT login FROM todo_users WHERE login<>'admin' ORDER by ID ;");
		$mysqli->close();
		if($queryResult->num_rows>0){
			return $queryResult;
		}else{
			return false;
		}
		// while ($row = $queryResult->fetch_row()) {
			// echo"<pre>";
			// print_r($row);
			// echo"</pre>";
		// }
		// $row = $queryResult->fetch_array();
		// $row = $queryResult->fetch_array();
		// $row = $queryResult->fetch_array(MYSQLI_NUM);
		// $row = $queryResult->fetch_array(MYSQLI_NUM);
		// $row = $queryResult->fetch_array(MYSQLI_NUM);
		// while($row = $queryResult->fetch_row()){

		// }

    }
	
	
	
	
}

?>