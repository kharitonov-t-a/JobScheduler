<?php


class Master{
	public function AddTable($key, $query){
		include $_SERVER['DOCUMENT_ROOT']."/config.php";
		$queryResult = $mysqli->query($query);
		$mysqli->close();
		$error = !$queryResult ? "<p class=red>Таблица $key не создана, ошибка.</p>" : "<p class=green>Таблица $key успешно создана.</p>";
		return $error;
    }
	
	public function Lowering($dirname, $dirdestination){
		$dir = opendir($dirname);
		while (($file = readdir($dir)) !== false){
			if(is_file($dirname."/".$file)){
				copy($dirname."/".$file, $dirdestination."/".$file);
			}
			if(is_dir($dirname."/".$file) && $file != "." && $file != ".."){
				if(!mkdir($dirdestination."/".$file)){
					echo "Can't create ".$dirdestination."/".$file."\n";
				}
				$this->Lowering("$dirname/$file","$dirdestination/$file");
			}
		}
		closedir($dir);
	}


	
	
	
}
?>