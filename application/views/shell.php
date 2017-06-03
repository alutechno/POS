<?php
	/**
	 * Created by IntelliJ IDEA.
	 * User: rappresent
	 * Date: 6/1/17
	 * Time: 10:59 PM
	 */
	echo json_encode($_GET['base']);
	$base = $_GET['base'];
	echo $_SERVER['REQUEST_URI'];
	$message = shell_exec("ls");
	$query = $this->db->query("select * from ref_outlet_menu_class");
	foreach ($query->result() as $row) {
		echo json_encode($row);
	}
	echo $message;
	//header('Location: '.$base);
	echo '<script language="javascript">';
	echo 'alert("order successfully print");';
	echo 'location.href = ' . json_encode($_GET['base']);
	echo '</script>';
?>
