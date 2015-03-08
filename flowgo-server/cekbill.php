<!DOCTYPE HTML>
<html>
<body>

<h2><center>FlowGo Control System</center></h2>
<h3><center>Water Usage Data</center></h3>
<center>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<button type="submit" name="dashboard">Kembali ke Dashboard</button><br>
	</form>
</center>
<br><br>
<center>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<button type="submit" name="showStat">Tampilkan Log Pemakaian Total</button>
	<button type="submit" name="showBill">Tampilkan Persebaran Pengguna</button><br>
	</form>
</center>


<?php
	include 'dbconn.php';

	echo "<br>";

	if(isset($_POST["dashboard"])){
		header('Location: dashboard.php');  
	}

	if(isset($_POST["showStat"])){
		echo "Statistik Penggunaan Air";
		echo "<br>";
		showChartFlow();
		echo "<br>";
		echo "Total penggunaan air : ";
		echo getFlowTotal();
	}

	if(isset($_POST["showBill"])){
		echo "Statistik Tipe Pengguna <br><br>";
		echo showUserType();
		echo "<br>Preferensi Sistem :  <br>";
		echo "Pengguna A_4 disarankan menggunakan air PAM karena berada di lokasi dengan tingkat air tanah rendah dan penggunaan air yang tinggi";

	}



?>

</body>
</html>