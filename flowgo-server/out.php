<?php
	include 'dbconn.php';
	if(isset($_GET["showstat"])){
		$tmp = getFlowTotal();
		echo json_encode($tmp);
	}

	if(isset($_GET["arrdata"])){
		$tmp = getArrTotal();
		echo json_encode($tmp);
	}


?>