<!DOCTYPE HTML>
<html>
<body>

<h2><center>FlowGo Control System</center></h2>
<h3><center>Device Manager</center></h3>
<center>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<button type="submit" name="dashboard">Kembali ke Dashboard</button><br>
	</form>
</center>


<table style="width:100%">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

<br><br>
<tr>
<td>
<b>Tambah Device<br></b>
ID Device: 
<br><input type="text" name="id_dev_add"><br>
Keterangan Alat : 
<br><textarea name="name_dev" rows="4" cols="50"></textarea> <br>
<button type="submit" name="add">Tambahkan Device</button><br>

</td>

<td>
<b>Hapus Device<br></b>
User ID : 
<br><input type="text" name="id_dev_del"><br>
<button type="submit" name="del">Hapus Device</button><br>
</td>

</form>
</table>




<?php
	include 'dbconn.php';
	include 'mtekaldwin.php';

	echo "<br>";

	if(isset($_POST["dashboard"])){
		header('Location: dashboard.php');  
	}


	if(isset($_POST["add"])){
		echo "add <br>";
		$id_in = $_POST['id_dev_add'];
		$name_in = $_POST['name_dev'];

		$gollib = lib();
		$gol = json_decode($gollib[0]['class']);

		echo $gol;

		addAlat($id_in, $name_in,$gol);
		//echo "add berhasil";

	}

	if(isset($_POST["del"])){
		echo "delete <br>";
		$id_del = $_POST['id_dev_del'];
		deleteAlat($id_del);
		//echo "delete berhasil";
	}

	echo "<h4>List device yang terpasang</h4>";
	echo "<center>";
	showDeviceList();
	echo "</center>";

?>

</body>
</html>