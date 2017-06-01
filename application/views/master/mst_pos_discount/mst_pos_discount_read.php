<!doctype html>
<html>
<head>
	<title>harviacode.com - codeigniter crud generator</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
	<style>
		body {
			padding: 15px;
		}
	</style>
</head>
<body>
<h2 style="margin-top:0px">Mst_pos_discount Read</h2>
<table class="table">
	<tr>
		<td>Code</td>
		<td><?php echo $code; ?></td>
	</tr>
	<tr>
		<td>Name</td>
		<td><?php echo $name; ?></td>
	</tr>
	<tr>
		<td>Description</td>
		<td><?php echo $description; ?></td>
	</tr>
	<tr>
		<td>Status</td>
		<td><?php echo $status; ?></td>
	</tr>
	<tr>
		<td>Food</td>
		<td><?php echo $food; ?></td>
	</tr>
	<tr>
		<td>Beverage</td>
		<td><?php echo $beverage; ?></td>
	</tr>
	<tr>
		<td>Others</td>
		<td><?php echo $others; ?></td>
	</tr>
	<tr>
		<td>Created Date</td>
		<td><?php echo $created_date; ?></td>
	</tr>
	<tr>
		<td>Modified Date</td>
		<td><?php echo $modified_date; ?></td>
	</tr>
	<tr>
		<td>Created By</td>
		<td><?php echo $created_by; ?></td>
	</tr>
	<tr>
		<td>Modified By</td>
		<td><?php echo $modified_by; ?></td>
	</tr>
	<tr>
		<td></td>
		<td><a href="<?php echo site_url('mst_pos_discount') ?>" class="btn btn-default">Cancel</a>
		</td>
	</tr>
</table>
</body>
</html>
