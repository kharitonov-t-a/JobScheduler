<?php
			$HOST = "";
			$USER = "";
			$PASS = "";
			$DB = "";

			$mysqli = new mysqli($HOST, $USER, $PASS, $DB);

			if($mysqli->connect_errno){
				printf("Connect failed: %s\n", $mysqli->connect_error);
				exit();
			}
		?>