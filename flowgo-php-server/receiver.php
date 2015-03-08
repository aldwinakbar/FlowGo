<html>
<body>



<?php
	include 'dbconn.php';

	$id_dev = $_POST["id"];
	$flow_dev = $_POST["flow"];
	$batt_dev = $_POST["batt"];

	//$time = time();

/*
	echo $id_dev;
	echo "<br>";
	echo $flow_dev;
	echo "<br>";
	echo $batt_dev;
	echo "<br>";
	echo $time;
	echo "<br>";
*/
	$result = updateDataFlow($id_dev,$flow_dev,$batt_dev);
//	echo $result;
//	echo "<br>";

	showDataFlow();

	header('Location: dashboard.php');  

?>

</body>
</html> 