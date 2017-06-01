Query result
<?php
// echo json_encode($_GET['data']);
// $message=shell_exec("ls");
// $query = $this->db->query("select * from ref_outlet_menu_class");
foreach ($rows as $row) {
	?>
	<div value="apaan-<?php echo $row->id ?>" <?php echo $row->id == $this->uri->segment(4) ? 'selected' : ''; ?>><?php echo $row->name ?></div>
	<?php
}
// echo $message;