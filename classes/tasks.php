<?php
class Tasks{
	
	public function AddTasks($title, $loginId, $importance_id=0, $description='', $link_task=null){ 
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		$query = "INSERT INTO todo_tasks ";
		
		if(!empty($title)) $query .= "SET title='" . $mysqli->real_escape_string($title) . "'";
		if(!empty($description)) $query .= ", description='" . $mysqli->real_escape_string($description) . "'";
		if(!empty($loginId)) $query .= ", user_id='" . $mysqli->real_escape_string($loginId) . "'";
		if(!empty($importance_id)) $query .= ", importance_id='" . $importance_id . "'";
		if(!empty($link_task)) $query .= ", link_task='" . $link_task . "'";
		$query .= ", date_create=CURDATE()";
		$query .= ";";
		$queryResult = $mysqli->query($query);

		$mysqli->close();
		return $queryResult;
    }
    
	public function editTasks($loginId, $idTask, $title, $importance_id, $description, $date_create, $link_task){ 
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		
		$query = "UPDATE todo_tasks ";
		
		if(!empty($title)) $query .= "SET title='" . $mysqli->real_escape_string($title) . "'";
		if(!empty($importance_id)) $query .= ", importance_id='" . $importance_id . "'";
		if(!empty($description)) $query .= ", description='" . $mysqli->real_escape_string($description) . "'";
		if(!empty($date_create)) $query .= ", date_create='" . $date_create . "'";
		if(!empty($link_task)) $query .= ", link_task='" . $link_task . "'";
		$query .= "WHERE user_id='" . $loginId . "' AND id='" . $idTask . "'";
		$query .= ";";

		$queryResult = $mysqli->query($query);

		$mysqli->close();
		return $queryResult;
		}
    
	public function doneTasks($done){ 
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		
		$query = "UPDATE todo_tasks ";
		
		$query .= "SET done=true ";
		$query .= "WHERE id='" . $done . "'";
		$query .= ";";

		$queryResult = $mysqli->query($query);

		$mysqli->close();
		return $queryResult;
		}
    
	public function delTasks($id, $loginId){
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		$queryResult = $mysqli->query("DELETE FROM todo_tasks WHERE user_id='" . $mysqli->real_escape_string($loginId) . "' AND id='" . $mysqli->real_escape_string($id) . "';");
		$mysqli->close();
		if($queryResult){ //Если логин и пароль введены правильно
			return true;
		}else{ //Логин и пароль не подошел
			return false; 
		}
		
    }
	
    public function listTasks($loginId, $sort='id', $side='asc', $filter='filter2', $search='', $filterImportances='', $filterDateCreate='', $currentPage=0, $countTask=0, $parrent='parrent2'){
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		if(!$sort) $sort='id';
		if(!$side) $side='asc';
		if($filter=='filter1') $filt="<>'1'";
		if($filter=='filter3') $filt="='1'";
		if($parrent=='parrent1') $link_task="='0'";
		if($parrent=='parrent3') $link_task="<>'0'";

		$query = "SELECT * FROM todo_tasks WHERE (user_id='" . $mysqli->real_escape_string($loginId) . "'";
		if(!empty($filt)) $query .= " AND done" . $filt;
		if(!empty($search)) $query .= ") AND ((title LIKE '%" . $search . "%') OR (description LIKE '%" . $search . "%')";
		if(!empty($filterImportances)) $query .= ") AND (importance_id='" . $filterImportances . "'";
		if(!empty($filterDateCreate)) $query .= ") AND (date_create='" . $filterDateCreate . "'";
		if(!empty($link_task)) $query .= ") AND (link_task" . $link_task;
		$query .= ") ORDER by " . $sort . " " . $side;
		if($countTask>0){
			$currentTask = $currentPage*$countTask;
			$query .= " LIMIT " . $currentTask . ", " . $countTask;
		}
		$query .= ";";
		$queryResult = $mysqli->query($query);
		$mysqli->close();
		if($queryResult->num_rows>0){
			return $queryResult;
		}else{
			return false;
		}
    }
	
	
	
	
	
	
}

?>