<?php
 //��������� ������

/** 
 * ����� ��� �����������
 * @author ������ ������ ox2.ru 
 */ 
class User{
    /**
     * ���������, ����������� ������������ ��� ���
     * ���������� true ���� �����������, ����� false
     * @return boolean 
     */
    public function isAuth(){
        if (!empty($_SESSION["is_auth"])){ //���� ������ ����������
            return $_SESSION["is_auth"]; //���������� �������� ���������� ������ is_auth (������ true ���� �����������, false ���� �� �����������)
        }else 
			return false; //������������ �� �����������, �.�. ���������� is_auth �� �������
    }
	
	public function isAdmin(){
        if ($_SESSION["login"] == 'admin'){
            return true;
        }else
			return false;
    }
    
    /**
     * ����������� ������������
     * @param string $login
     * @param string $passwors 
     */
    public function auth($login, $password){
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		$queryResult = $mysqli->query("SELECT * FROM todo_users WHERE login='" . $mysqli->real_escape_string($login) . "' AND password='" . MD5($mysqli->real_escape_string($password)) . "';");
		$mysqli->close();

		if($queryResult->num_rows == 1){ //���� ����� � ������ ������� ���������
            $_SESSION["is_auth"] = true; //������ ������������ ��������������
            $_SESSION["login"] = $login; //���������� � ������ ����� ������������
            return true;
        }else{ //����� � ������ �� �������
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
     * ����� ���������� ����� ��������������� ������������ 
     */
    public function getLogin() {
        if ($this->isAuth()) { //���� ������������ �����������
            return $_SESSION["login"]; //���������� �����, ������� ������� � ������
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
        $_SESSION = array(); //������� ������
        session_destroy(); //����������
    }
	
	
	public function delUser($login){
		if($_SESSION["login"] == $login || $this->isAdmin()){
			include $_SERVER['DOCUMENT_ROOT']."/config.php";
			$queryResult = $mysqli->query("DELETE FROM todo_users WHERE login='" . $mysqli->real_escape_string($login) . "';");
			$mysqli->close();
			if($queryResult){ //���� ����� � ������ ������� ���������
				if($_SESSION["login"] == $login)
					$this->out();
				return true;
			}else{ //����� � ������ �� �������
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