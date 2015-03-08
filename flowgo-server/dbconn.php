<?php
function init_db(){
	$dhost = 'localhost';
    $duser = 'root';
    $dpass = 'root';
    $dname = 'flowgo';
    $connect = mysqli_connect($dhost, $duser, $dpass, $dname);

        //echo "jalan";

    if(mysqli_connect_errno()){
           echo "failed".mysqli_connect_error();
    }

	return $connect;
}

function updateDataFlow($id_dev, $flow_dev, $batt_dev){
	$time_dev = time();

	echo $id_dev;
	echo $flow_dev;
	echo $batt_dev;

	$old_flow = getFlowDev($id_dev);
	$new_flow = $old_flow + $flow_dev;

	//konek dulu ke db
	$connect = init_db();

	//sequence cek udah ada belom device nya di db
	$check = "SELECT id FROM dataflow where id = '$id_dev'";
	$res = mysqli_query($connect,$check);
	$row = mysqli_fetch_array($res);
	
	$res2 = $row["id"];
	echo $res2;
	
	$command = "";


	//jika udah ada, update aja
	if(!is_null($res2)){
		echo "id sudah ada<br>";
		$command = "UPDATE dataflow SET flow='$new_flow', batt='$batt_dev', time='$time_dev' WHERE id='$id_dev' ";
		echo "success";
	} 
	//belum ada, ya gabisa masuk laah wkwkw
	else{
		/*
		echo "id belum ada";
		$command = "INSERT INTO dataflow (id, name, flow, batt, time) values('$id_dev', '', '$flow_dev', '$batt_dev', '$time_dev')"; 
		*/
	}
	
	//kirim
	if(mysqli_query($connect,$command)) return 1;
	else return 0;
}

function showDataFlow(){

	$db = init_db();
	$sql = "SELECT id, name, flow, batt, time FROM dataflow";
	$res = mysqli_query($db,$sql);

	echo "
	<table style='width:100%'>
	<tr>	
		<td><b>ID Alat</b></td>
		<td><b>Keterangan Alat</b></td>
		<td><b>Volume yang dideteksi</b></td>
		<td><b>Status baterai alat</b></td>
		<td><b>Timestamp</b></td>
		
	</tr>";
	$temp = $row["time"];


	if($res->num_rows > 0){
		while($row = $res->fetch_assoc()){
			echo "<tr>";
			echo "<td>".$row["id"]."</td>
				<td>".$row["name"]."</td>
				<td>".$row["flow"]."</td>
				<td>".$row["batt"]."</td>
				<td>".date("d/m/y : H:i:s", $row["time"])."</td>";
			echo "</tr>";
		}
	}else{
		echo "0";
	}

	echo "</table>";

}

function addAlat($id_dev, $name_dev){
	//konek dulu ke db
	$connect = init_db();

	//sequence cek udah ada belom device nya di db
	$check = "SELECT id FROM dataflow where id = '$id_dev'";
	$res = mysqli_query($connect,$check);
	$row = mysqli_fetch_array($res);
	
	$res2 = $row["id"];
	echo $res2;
	
	$command = "";


	//jika udah ada, gausah bikin
	if(!is_null($res2)){
	} 
	//belum ada, bikin baru
	else{
		echo "id belum ada";
		$command = "INSERT INTO dataflow (id, name, flow, batt, time) values('$id_dev', '$name_dev', '', '', '$time_dev')"; 
	}

	//kirim
	if(mysqli_query($connect,$command)) return 1;
	else return 0;
}

function deleteAlat($id_dev){
	//konek dulu ke db
	$connect = init_db();

	//sequence cek udah ada belom device nya di db
	$check = "SELECT id FROM dataflow where id = '$id_dev'";
	$res = mysqli_query($connect,$check);
	$row = mysqli_fetch_array($res);
	
	$res2 = $row["id"];
	echo $res2;
	
	$command = "";


	//jika udah ada, hapus
	if(!is_null($res2)){
		$command = "DELETE FROM dataflow WHERE id = '$id_dev'"; 
	} 
	//belum ada, bikin baru
	else{		
		echo "device tidak ada";
	}

	//kirim
	if(mysqli_query($connect,$command)) return 1;
	else return 0;
}

function showDeviceList(){

	$db = init_db();
	$sql = "SELECT id, name FROM dataflow";
	$res = mysqli_query($db,$sql);

	echo "
	<table style='width:100%'>
	<tr>	
		<td><b>ID Alat</b></td>
		<td><b>Keterangan Alat</b></td>		
	</tr>";

	if($res->num_rows > 0){
		while($row = $res->fetch_assoc()){
			echo "<tr>";
			echo "<td>".$row["id"]."</td>
				<td>".$row["name"]."</td>";
			echo "</tr>";
		}
	}else{
		echo "0";
	}

	echo "</table>";

}

function getFlowDev($id_dev){
	
	//konek dulu ke db
	$connect = init_db();

	//sequence cek udah ada belom device nya di db
	$check = "SELECT flow FROM dataflow where id = '$id_dev'";
	$res = mysqli_query($connect,$check);
	$row = mysqli_fetch_array($res);

	$res2 = $row["flow"];

	if(is_null($res2)) return -1;
	else return $res2;
}

function getFlowTotal(){
	$db = init_db();
	$sql = "SELECT id FROM dataflow";
	$res = mysqli_query($db,$sql);

	$total = 0;

	if($res->num_rows > 0){
		while($row = $res->fetch_assoc()){
			$id_now = $row["id"];
			$flow_now = getFlowDev($id_now);

			$total = $total + $flow_now;
		}
	}else{
		echo "0";
	}

	return $total;

}

function showChartFlow(){
	include "libchart/libchart/classes/libchart.php";

	$chart = new PieChart( 500, 300 );
	$dataSet = new XYDataSet();

	$db = init_db();
	$sql = "SELECT id, flow FROM dataflow";
	$res = mysqli_query($db,$sql);

	$total = 0;

	if($res->num_rows > 0){
		while($row = $res->fetch_assoc()){
			extract($row);
            $dataSet->addPoint(new Point("{$id} {$flow})", $flow));
		}
		$chart->setDataSet($dataSet);
		$chart->setTitle("Penggunaan Air Total");
		$chart->render("1.png");
		echo "<img alt='Pie chart'  src='1.png' style='border: 1px solid gray;'/>";


	}else{
		echo "0";
	}

	return $total;
}

function getDevArr($id_dev){
	
	//konek dulu ke db
	$connect = init_db();

	//sequence cek udah ada belom device nya di db
	$check = "SELECT id, flow, time FROM dataflow where id = '$id_dev'";
	$res = mysqli_query($connect,$check);
	$row = mysqli_fetch_array($res);

	$idx = $row["id"];
	$flowx = $row["flow"];
	$timex = $row["time"];

	$data = array($idx, $flowx, $timex);

	if(is_null($data)) return -1;
	else return $data;
}

function getArrTotal(){
	$db = init_db();
	$sql = "SELECT id FROM dataflow";
	$res = mysqli_query($db,$sql);

	$idx = 0;
	$arrT = array();

	if($res->num_rows > 0){
		while($row = $res->fetch_assoc()){
			$id_now = $row["id"];
			$arr = getDevArr($id_now);
			$arrT[$idx] = $arr;
			$idx++;
		}
	}else{
		echo "0";
	}

	return $arrT;

}

function showUserType(){

    $db = init_db();
    $sql = "SELECT id, flow, lat, log, watype, gol  FROM dataflow";
    $res = mysqli_query($db,$sql);

    echo "
    <table style='width:100%'>
    <tr>    
        <td><b>ID Alat</b></td>
        <td><b>Volume Pemakaian</b></td>
        <td><b>Koordinat Pengguna</b></td>
        <td><b>Air Tanah/PAM</b></td>
        <td><b>Golongan Pengguna</b></td>
        
    </tr>";


    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){
            echo "<tr>";
            echo "<td>".$row["id"]."</td>
                <td>".$row["flow"]."</td>
                <td>".$row["lat"].",".$row["log"]."</td>
                <td>".$row["watype"]."</td>
                <td>".$row["gol"]."</td>";
            echo "</tr>";
        }
    }else{
        echo "0";
    }

    echo "</table>";

}


?>