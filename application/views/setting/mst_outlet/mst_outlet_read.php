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
<h2 style="margin-top:0px">Mst_outlet Read</h2>
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
		<td>Address</td>
		<td><?php echo $address; ?></td>
	</tr>
	<tr>
		<td>Bill Footer</td>
		<td><?php echo $bill_footer; ?></td>
	</tr>
	<tr>
		<td>Status</td>
		<td><?php echo $status; ?></td>
	</tr>
	<tr>
		<td>Outlet Type Id</td>
		<td><?php echo $outlet_type_id; ?></td>
	</tr>
	<tr>
		<td>Cost Center Id</td>
		<td><?php echo $cost_center_id; ?></td>
	</tr>
	<tr>
		<td>Delivery Bill Footer</td>
		<td><?php echo $delivery_bill_footer; ?></td>
	</tr>
	<tr>
		<td>No Of Seats</td>
		<td><?php echo $no_of_seats; ?></td>
	</tr>
	<tr>
		<td>M2</td>
		<td><?php echo $m2; ?></td>
	</tr>
	<tr>
		<td>Last Meal Period</td>
		<td><?php echo $last_meal_period; ?></td>
	</tr>
	<tr>
		<td>Curr Meal Period</td>
		<td><?php echo $curr_meal_period; ?></td>
	</tr>
	<tr>
		<td>List Number</td>
		<td><?php echo $list_number; ?></td>
	</tr>
	<tr>
		<td>Num Of Employee</td>
		<td><?php echo $num_of_employee; ?></td>
	</tr>
	<tr>
		<td>Is Allow Cancel Tax</td>
		<td><?php echo $is_allow_cancel_tax; ?></td>
	</tr>
	<tr>
		<td>Fo Gl Journal Code</td>
		<td><?php echo $fo_gl_journal_code; ?></td>
	</tr>
	<tr>
		<td>Bill Image Uri</td>
		<td><?php echo $bill_image_uri; ?></td>
	</tr>
	<tr>
		<td>Bill Image Path</td>
		<td><?php echo $bill_image_path; ?></td>
	</tr>
	<tr>
		<td>Small Bill Image Uri</td>
		<td><?php echo $small_bill_image_uri; ?></td>
	</tr>
	<tr>
		<td>Small Bill Image Path</td>
		<td><?php echo $small_bill_image_path; ?></td>
	</tr>
	<tr>
		<td>Printed Bill Image Uri</td>
		<td><?php echo $printed_bill_image_uri; ?></td>
	</tr>
	<tr>
		<td>Printed Bill Image Path</td>
		<td><?php echo $printed_bill_image_path; ?></td>
	</tr>
	<tr>
		<td>Small Printed Bill Image Uri</td>
		<td><?php echo $small_printed_bill_image_uri; ?></td>
	</tr>
	<tr>
		<td>Small Printed Bill Image Path</td>
		<td><?php echo $small_printed_bill_image_path; ?></td>
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
		<td><a href="<?php echo site_url('mst_outlet') ?>" class="btn btn-default">Cancel</a></td>
	</tr>
</table>
</body>
</html>
