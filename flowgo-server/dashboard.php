<!DOCTYPE HTML>
<html>
<body>

<h2><center>FlowGo Control System</center></h2>
<h3><center>Dashboard</center></h3>
<center>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<button type="submit" name="deviceMan">Pengaturan Device</button>
	<button type="submit" name="cekBill">Cek Penggunaan Air Total</button><br>
	</form>
</center>

<?php
	include 'dbconn.php';

	echo "<br>";

	showDataFlow();

	if(isset($_POST["deviceMan"])){
		header('Location: devman.php');  
	}

	if(isset($_POST["cekBill"])){
		header('Location: cekbill.php');  
	}


?>

</body>
</html>