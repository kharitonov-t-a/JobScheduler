<?php
 //Запускаем сессии

/** 
 * Класс для авторизации
 * @author дизайн студия ox2.ru 
 */ 
class Importances{
	
	public function AddImportances($title, $value, $loginId){

		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		$queryResult = $mysqli->query("INSERT INTO todo_importances (title, value, user_id) VALUES('" . $mysqli->real_escape_string($title) . "', '" . $mysqli->real_escape_string($value) . "', '" . $mysqli->real_escape_string($loginId) . "');");
		$mysqli->close();
		return $queryResult;
    }
    
	public function delImportances($id, $loginId){
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		$queryResult = $mysqli->query("DELETE FROM todo_importances WHERE user_id='" . $mysqli->real_escape_string($loginId) . "' AND id='" . $mysqli->real_escape_string($id) . "';");
		$mysqli->close();
		if($queryResult){ //Если логин и пароль введены правильно
			return true;
		}else{ //Логин и пароль не подошел
			return false; 
		}
		
    }
	
    public function listImportances($loginId){
		include $_SERVER['DOCUMENT_ROOT']."/config.php";

		$queryResult = $mysqli->query("SELECT * FROM todo_importances WHERE user_id='" . $mysqli->real_escape_string($loginId) . "' ORDER by ID asc;");
		$mysqli->close();
		if($queryResult->num_rows>0){
			return $queryResult;
		}else{
			return false;
		}
    }
	
	
	
    public function getImportances($Id){
		include $_SERVER['DOCUMENT_ROOT']."/config.php";

		$queryResult = $mysqli->query("SELECT * FROM todo_importances WHERE id='" . $mysqli->real_escape_string($Id) . "';");
		$mysqli->close();
		if($queryResult->num_rows>0){
			return $queryResult;
		}else{
			return false;
		}
    }
	
	
	
	
	
	
}

?>